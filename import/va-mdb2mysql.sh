#!/bin/sh

DB="$(readlink "$1")"

if [ ! -e "$DB" ]; then
	echo "File does not exist."
	exit 1
fi
if [ ! -r "$DB" ]; then
	echo "File is not readable."
	exit 1
fi
if [ "$(file --brief "$DB")" != "Microsoft Access Database" ]; then
	echo "File type mismatch."
	exit 1
fi

mdb-tables -1 -t table "$DB" | while read table
do
	mdb-schema --drop-table -T "$table" "$DB" mysql | sed \
		's,varchar .510.,text,g
		;1,9 d'
	mdb-export -I mysql -X \\ "$DB" "$table" | sed \
		's,\(.\)\.\(.\)\(.\)00000000000000e+01,\1\2.\3,g
		;s,\(.\)\.0000000000000000e+00,\1.0,g
		;s,"\(..\)/\(..\)/\([012345].\) 00:00:00","20\3-\1-\2",g
		;s,"\(..\)/\(..\)/\(..\) 00:00:00","19\3-\1-\2",g'
done

cat <<-HEREDOC

ALTER TABLE \`TblLkp_Casting\`
  ADD PRIMARY KEY (\`casID\`);
ALTER TABLE \`TblLkp_Faction\`
  ADD PRIMARY KEY (\`facID\`);
ALTER TABLE \`TblLkp_Mana\`
  ADD PRIMARY KEY (\`mnaID\`);
ALTER TABLE \`Tbl_CharacterCasting\`
  ADD PRIMARY KEY (\`ccChaIDFK\`,\`ccCasIDFK\`);
ALTER TABLE \`Tbl_CharacterPowers\`
  ADD PRIMARY KEY (\`ciChaIDFK\`,\`ciPwrIDFK\`);
ALTER TABLE \`Tbl_Characters\`
  ADD PRIMARY KEY (\`chaID\`)
, ADD KEY \`chaPLINIDFK\` (\`chaPLINIDFK\`,\`chaCHIN\`)
, ADD KEY \`chaGroupIDFK\` (\`chaGroupIDFK\`)
, ADD KEY \`chaBeliefIDFK\` (\`chaBeliefIDFK\`)
, ADD KEY \`chaWorldIDFK\` (\`chaWorldIDFK\`);
ALTER TABLE \`Tbl_CharacterSkills\`
  ADD PRIMARY KEY (\`csChaIDFK\`,\`csSkiIDFK\`);
ALTER TABLE \`Tbl_Groups\`
  ADD PRIMARY KEY (\`grpID\`);
ALTER TABLE \`Tbl_Items\`
  ADD PRIMARY KEY (\`itmID\`)
, ADD KEY \`itmchaIDFK\` (\`itmchaIDFK\`);
ALTER TABLE \`Tbl_LoreSkills\`
  ADD PRIMARY KEY (\`lorID\`);
ALTER TABLE \`Tbl_Players\`
  ADD PRIMARY KEY (\`plaPLIN\`);
ALTER TABLE \`Tbl_Powers\`
  ADD PRIMARY KEY (\`pwrID\`);
ALTER TABLE \`Tbl_Skills\`
  ADD PRIMARY KEY (\`skiID\`);
ALTER TABLE \`Tbl_Worlds\`
  ADD PRIMARY KEY (\`wldID\`);
HEREDOC

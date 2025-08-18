#!/bin/sh -e
SIDES="single"
SIDES="double"

HERE="$(dirname "$(readlink -f "$0")")"
DATE="$(date +"%Y-%m")"

CAKE="$HERE/../bin/cake"
LOG="$HERE/log.$DATE"
PDF="$HERE/pdfs/$DATE/$SIDES.$(date +"%F_%T").pdf"

{
	ID=$("$CAKE" queue)
	if [ $ID -eq 0 ]; then
	#	echo "$(date +"%FT%T") Queue is empty"
		exit 0
	fi

	mkdir -p "$(dirname "$PDF")"
	"$CAKE" queue $SIDES $ID > "$PDF"
	lpr "$PDF"

	nr=$("$CAKE" queue printed $ID)
	echo "$(date +"%FT%T") Printed $nr lammies to '$(basename "$PDF")'"
} 2>&1 | tee -a "$LOG"

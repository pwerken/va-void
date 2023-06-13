<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class SkillsLoreBlanks extends AbstractMigration
{
    public function up(): void
    {
        $skills = $this->table('skills');

        // add columns
        $skills
            ->addColumn('loresheet', 'boolean', [
                'after' => 'mana_amount',
                'default' => null,
                'limit' => 1,
                'null' => false,
            ])
            ->addColumn('blanks', 'boolean', [
                'after' => 'loresheet',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->update();

        // fill columns based on skill name
        $query = $this->getQueryBuilder();
        $query->select('*')->from('skills')
            ->where($query->newExpr()->or([])
                      ->add($query->newExpr()->like('name', '% (blanks)'))
                      ->add($query->newExpr()->like('name', '% (lore)'))
                      ->add($query->newExpr()->like('name', '% (lore & blanks)'))
                    );
        foreach($query->execute() as $row)
        {
            $name = $row['name'];
            $hasLore = false;
            $hasBlanks = false;

            preg_match('/^(.*) (\\(.*\\))$/', $row['name'], $matches);
            $name = $matches[1];
            $hasLore =   (int)(substr($matches[2], 0, 5) == '(lore');
            $hasBlanks = (int)(substr($matches[2], -7) == 'blanks)');

            $this->getQueryBuilder()
                ->update('skills')
                ->set(['name' => $name])
                ->set(['loresheet' => $hasLore])
                ->set(['blanks' => $hasBlanks])
                ->where(['id' => $row['id']])
                ->execute();
        }
    }

    public function down(): void
    {
        // add loresheet and blanks back to skill name
        $query = $this->getQueryBuilder();
        $query->select('*')->from('skills')
            ->where($query->newExpr()->or([])
                      ->add(['loresheet' => true])
                      ->add(['blanks' => true])
                    );
        foreach($query->execute() as $row)
        {
            $name = $row['name'];
            if($row['loresheet'] and $row['blanks']) {
                $name .= ' (lore & blanks)';
            } elseif($row['loresheet']) {
                $name .= ' (lore)';
            } else {
                $name .= ' (blanks)';
            }
            $query = $this->getQueryBuilder()
                ->update('skills')
                ->set(['name' => $name])
                ->where(['id' => $row['id']])
                ->execute();
        }

        // drop columns
        $this->table('skills')
            ->removeColumn('loresheet')
            ->removeColumn('blanks')
            ->update();
    }
}

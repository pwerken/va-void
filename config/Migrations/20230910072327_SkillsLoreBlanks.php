<?php
declare(strict_types=1);

use App\Migrations\Migration;

class SkillsLoreBlanks extends Migration
{
    public function up(): void
    {
        $this->table('skills')
            ->addColumnBoolean('loresheet', ['after' => 'mana_amount'])
            ->addColumnBoolean('blanks', ['after' => 'loresheet'])
            ->update();

        // fill columns based on skill name
        $query = $this->getQueryBuilder('select');
        $query->select('*')->from('skills')
            ->where($query->newExpr()->or([])
                      ->add($query->newExpr()->like('name', '% (blanks)'))
                      ->add($query->newExpr()->like('name', '% (lore)'))
                      ->add($query->newExpr()->like('name', '% (lore & blanks)')));

        foreach ($query->execute() as $row) {
            $name = $row['name'];
            $hasLore = false;
            $hasBlanks = false;

            preg_match('/^(.*) (\\(.*\\))$/', $row['name'], $matches);
            $name = $matches[1];
            $hasLore = (int)(substr($matches[2], 0, 5) == '(lore');
            $hasBlanks = (int)(substr($matches[2], -7) == 'blanks)');

            $this->getQueryBuilder('update')
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
        $query = $this->getQueryBuilder('select');
        $query->select('*')->from('skills')
            ->where($query->newExpr()->or([])
                      ->add(['loresheet' => true])
                      ->add(['blanks' => true]));
        foreach ($query->execute() as $row) {
            $name = $row['name'];
            if ($row['loresheet'] && $row['blanks']) {
                $name .= ' (lore & blanks)';
            } elseif ($row['loresheet']) {
                $name .= ' (lore)';
            } else {
                $name .= ' (blanks)';
            }
            $this->getQueryBuilder('update')
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

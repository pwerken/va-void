<?php
declare(strict_types=1);

use Cake\I18n\FrozenTime;
use Migrations\AbstractMigration;

class SkillMultipleTimes extends AbstractMigration
{
    protected $_when;

    public function up(): void
    {
       // add columns
       $this->table('characters_skills')
           ->addColumn('times', 'integer', [
               'after' => 'skill_id',
               'default' => 1,
               'limit' => 11,
               'null' => false,
           ])
           ->update();
       $this->table('skills')
           ->addColumn('times_max', 'integer', [
               'after' => 'cost',
               'default' => 1,
               'limit' => 11,
               'null' => false,
           ])
           ->update();

        // converting old skills entries that are multiples...
        $query = $this->getQueryBuilder()
                    ->select('*')
                    ->from('skills')
                    ->where(['name LIKE' => '% (%x)', 'deprecated' => False]);

        $mapping = [];  // the times > 1 list of skill to modify
        $base = [];     // the 'base' times=1 skills to map to
        foreach($query->execute() as $row)
        {
            preg_match('/^(.*) \\((.*)x\\)$/', $row['name'], $matches);
            $name = $matches[1];
            $times = (int)$matches[2];

            if($times > 1) {
                // these we need to convert, we do that later...
                $mapping[$row['id']] = ['name' => $name, 'times' => $times];
                continue;
            }

            // rename & remember $name - $id relation
            $base[$name] = $row['id'];
            $query = $this->getQueryBuilder()
                        ->update('skills')
                        ->set('name', $name)
                        ->where(['id' => $row['id']])
                        ->execute();
        }

        // make sure $base[] is complete
        foreach($mapping as $skill) {
            if(isset($base[$skill['name']]))
                continue;

            $result = $this->getQueryBuilder()
                        ->select('id')
                        ->from('skills')
                        ->where(['name LIKE' => $skill['name']])
                        ->execute()->fetch();
            $base[$skill['name']] = $result[0];
        }

        // do the converting...
        foreach($mapping as $key => $skill) {
            $replace_id = $base[$skill['name']];

            $query = $this->getQueryBuilder()
                        ->update('skills')
                        ->set('deprecated', true)
                        ->where(['id' => $key])
                        ->execute();
            $query = $this->getQueryBuilder()
                        ->update('skills')
                        ->set('times_max', $skill['times'])
                        ->where(['id' => $replace_id
                                , 'times_max <' => $skill['times']])
                        ->execute();

            // update characters that have the skill
            $query = $this->getQueryBuilder()
                        ->select('*')
                        ->from('characters_skills')
                        ->where(['skill_id' => $key]);
            foreach($query as $row) {
                $current = [];
                $current['entity'] = 'CharactersSkill';
                $current['key1'] = $row['character_id'];
                $current['key2'] = $row['skill_id'];
                $current['data'] = json_encode(['times' => $row['times']]);
                $current['modified'] = $row['modified'];
                $current['modifier_id'] = $row['modifier_id'];

                $delete = $current;
                $delete['data'] = NULL;
                $delete['modified'] = $this->when();
                $delete['modifier_id'] = -2;

                $replacement = [];
                $replacement['character_id'] = $row['character_id'];
                $replacement['skill_id'] = $replace_id;
                $replacement['times'] = $skill['times'];
                $replacement['modified'] = $this->when();
                $replacement['modifier_id'] = -2;

                // log the changes
                $query = $this->getQueryBuilder()
                        ->into('history')
                        ->insert(array_keys($current))
                        ->values($current)
                        ->values($delete)
                        ->execute();
                // remove the old skill
                $query = $this->getQueryBuilder()
                        ->delete('characters_skills')
                        ->where(['character_id' => $row['character_id']
                                , 'skill_id' => $key])
                        ->execute();

                // check if base skill already present
                $query = $this->getQueryBuilder()
                        ->select('times')
                        ->from('characters_skills')
                        ->where(['character_id' => $row['character_id']
                            , 'skill_id' => $replace_id])
                        ->execute();
                if($query->count() > 0) {
                    // if it does, add to existing skill
                    $existing = $query->fetch();
                    $times = $existing[0] + $skill['times'];
                    $query = $this->getQueryBuilder()
                                ->update('characters_skills')
                                ->set('times', $times)
                                ->where(['character_id' => $row['character_id']
                                    , 'skill_id' => $replace_id])
                                ->execute();
                } else {
                    // else insert base skill
                    $query = $this->getQueryBuilder()
                            ->into('characters_skills')
                            ->insert(array_keys($replacement))
                            ->values($replacement)
                            ->execute();
                }
            }
        }
    }

    public function down(): void
    {
        // NOT converting multiples back to seperate skill entries...

        // drop columns
        $this->table('characters_skills')
            ->removeColumn('times')
            ->update();
        $this->table('skills')
            ->removeColumn('times_max')
            ->update();
    }

    protected function when(): string
    {
        if($this->_when === null) {
            $now = new FrozenTime();
            $this->_when = $now->toDateTimeString();
        }

        return $this->_when;
    }
}

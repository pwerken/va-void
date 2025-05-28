<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Character;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;

/**
 * @property \App\Model\Table\CharactersConditionsTable $CharactersConditions;
 * @property \App\Model\Table\CharactersPowersTable $CharactersPowers;
 * @property \App\Model\Table\CharactersSkillsTable $CharactersSkills;
 * @property \App\Model\Table\ItemsTable $Items;
 */
class CharactersTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->belongsTo('Players');
        $this->belongsTo('Factions')->setProperty('faction_object');

        $this->hasMany('Items');
        $this->hasMany('CharactersConditions')->setProperty('conditions');
        $this->hasMany('CharactersPowers')->setProperty('powers');
        $this->hasMany('CharactersSkills')->setProperty('skills');

        $this->hasMany('MyStudents', ['className' => 'Teachings'])
                ->setForeignKey('teacher_id')->setProperty('students');
        $this->hasOne('MyTeacher', ['className' => 'Teachings'])
                ->setForeignKey('student_id')->setProperty('teacher');
    }

    public function beforeMarshal(EventInterface $event, ArrayObject $data, ArrayObject $options): void
    {
        if (isset($data['plin'])) {
            $plin = $data['player_id'] = $data['plin'];
            unset($data['plin']);

            if (!isset($data['chin'])) {
                $chin = $this->find()
                            ->select(['maxChin' => 'MAX(chin)'])
                            ->where(['player_id' => $plin])
                            ->enableHydration(false)
                            ->first();
                $chin = $chin ? $chin['maxChin'] + 1 : 1;
                $data['chin'] = $chin;
            }
        }

        if (isset($data['faction'])) {
            $data['faction_id'] = $this->nameToId('Factions', $data['faction']);
        }

        parent::beforeMarshal($event, $data, $options);
    }

    public function afterMarshal(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        parent::afterMarshal($event, $entity, $options);

        if (!$entity->isNew()) {
            if ($entity->isDirty('player_id')) {
                $entity->setError('player_id', ['key' => 'Cannot change primary key field']);
            }
            if ($entity->isDirty('chin')) {
                $entity->setError('chin', ['key' => 'Cannot change primary key field']);
            }
        }
    }

    public function afterSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        if ($entity->isDirty('status') && $entity->status == 'active') {
            $chars = $this->findByPlayerId($entity->player_id);
            foreach ($chars as $char) {
                if ($char->id == $entity->id || $char->status != 'active') {
                    continue;
                }
                $char->status = 'inactive';
                $this->save($char);
            }
        }
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->addCreate($rules->isUnique(['chin', 'player_id']));
        $rules->addCreate($rules->isUnique(['name', 'player_id']));

        $rules->add($rules->existsIn('faction_id', 'Factions'));

        $rules->addDelete([$this, 'ruleNoConditions']);
        $rules->addDelete([$this, 'ruleNoItems']);
        $rules->addDelete([$this, 'ruleNoPowers']);
        $rules->addDelete([$this, 'ruleNoSkills']);

        return $rules;
    }

    public function findBelief(SelectQuery $query): SelectQuery
    {
        return $query->select(['name' => 'belief'])
                    ->distinct(['belief'])
                    ->orderBy(['belief'], true);
    }

    public function findGroup(SelectQuery $query): SelectQuery
    {
        return $query->select(['name' => 'group'])
                    ->distinct(['group'])
                    ->orderBy(['group'], true);
    }

    public function findWorld(SelectQuery $query): SelectQuery
    {
        return $query->select(['name' => 'world'])
                    ->distinct(['world'])
                    ->orderBy(['world'], true);
    }

    public function ruleNoConditions(EntityInterface $entity, array $options): bool
    {
        $query = $this->CharactersConditions->find();
        $query->where(['character_id' => $entity->id]);

        if ($query->count() > 0) {
            $entity->setError('conditions', $this->consistencyError);

            return false;
        }

        return true;
    }

    public function ruleNoItems(EntityInterface $entity, array $options): bool
    {
        $query = $this->Items->find();
        $query->where(['character_id' => $entity->id]);

        if ($query->count() > 0) {
            $entity->setError('items', $this->consistencyError);

            return false;
        }

        return true;
    }

    public function ruleNoPowers(EntityInterface $entity, array $options): bool
    {
        $query = $this->CharactersPowers->find();
        $query->where(['character_id' => $entity->id]);

        if ($query->count() > 0) {
            $entity->setError('powers', $this->consistencyError);

            return false;
        }

        return true;
    }

    public function ruleNoSkills(EntityInterface $entity, array $options): bool
    {
        $query = $this->CharactersSkills->find();
        $query->where(['character_id' => $entity->id]);

        if ($query->count() > 0) {
            $entity->setError('skills', $this->consistencyError);

            return false;
        }

        return true;
    }

    protected function contain(): array
    {
        return [
            'Factions',
            'Players',
            'Items',
            'CharactersConditions.Conditions',
            'CharactersPowers.Powers',
            'CharactersSkills.Skills.Manatypes',
            'MyTeacher' => ['Teacher', 'Student', 'Skills.Manatypes', 'Started', 'Updated'],
            'MyStudents' => ['Teacher', 'Student', 'Skills.Manatypes', 'Started', 'Updated'],
        ];
    }

    protected function orderBy(): array
    {
        return ['player_id' => 'ASC', 'chin' => 'DESC'];
    }

    protected function nameToId(string $model, string $name): int
    {
        if (empty($name)) {
            $name = '-';
        }

        $result = $this->$model->findByName($name)
                    ->select('id', true)
                    ->enableHydration(false)
                    ->first();

        if (is_null($result)) {
            return 0;
        }

        return $result['id'];
    }
}

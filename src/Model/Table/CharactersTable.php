<?php
declare(strict_types=1);

namespace App\Model\Table;

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
        $this->belongsToManyThrough('Conditions', 'CharactersConditions');
        $this->belongsToManyThrough('Powers', 'CharactersPowers');
        $this->belongsToManyThrough('Skills', 'CharactersSkills');

        $this->hasOne('MyTeacher', [
            'className' => 'Teachings',
            'foreignKey' => 'student_id',
            'propertyName' => 'teacher',
        ]);
        $this->hasMany('MyStudents', [
            'className' => 'Teachings',
            'foreignKey' => 'teacher_id',
            'propertyName' => 'students',
        ]);
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
        if ($entity->isDirty('status') && $entity->get('status') === 'active') {
            $chars = $this->findByPlayerId($entity->get('player_id'));
            foreach ($chars as $char) {
                if ($char->id === $entity->get('id') || $char->status !== 'active') {
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

        $rules->addDelete([$this, 'ruleNoSkills']);
        $rules->addDelete([$this, 'ruleNoItems']);
        $rules->addDelete([$this, 'ruleNoConditions']);
        $rules->addDelete([$this, 'ruleNoPowers']);

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
        $this->loadInto($entity, ['Conditions']);

        if (count($entity->get('conditions')) > 0) {
            $entity->setError('conditions', $this->consistencyError);

            return false;
        }

        return true;
    }

    public function ruleNoItems(EntityInterface $entity, array $options): bool
    {
        $this->loadInto($entity, ['Items']);

        if (count($entity->get('items')) > 0) {
            $entity->setError('items', $this->consistencyError);

            return false;
        }

        return true;
    }

    public function ruleNoPowers(EntityInterface $entity, array $options): bool
    {
        $this->loadInto($entity, ['Powers']);

        if (count($entity->get('powers')) > 0) {
            $entity->setError('powers', $this->consistencyError);

            return false;
        }

        return true;
    }

    public function ruleNoSkills(EntityInterface $entity, array $options): bool
    {
        $this->loadInto($entity, ['Skills']);

        if (count($entity->get('skills')) > 0) {
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
            'Conditions',
            'Powers',
            'Skills.Manatypes',
            'MyTeacher' => ['Teacher', 'Skill.Manatypes'],
            'MyStudents' => ['Student', 'Skill.Manatypes'],
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

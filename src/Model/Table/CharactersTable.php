<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Enum\CharacterStatus;
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

        $this->setColumnEnumType('status', CharacterStatus::class);

        $this->belongsTo('Players')->setForeignKey('plin');
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
        if (isset($data['plin']) && !isset($data['chin'])) {
            $query = $this->find();
            $chin = $query->select(['maxChin' => $query->func()->max('chin')])
                        ->where(['plin' => $data['plin']])
                        ->enableHydration(false)
                        ->first();
            $data['chin'] = ($chin ? $chin['maxChin'] + 1 : 1);
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
            if ($entity->isDirty('plin')) {
                $entity->setError('plin', ['key' => 'Cannot change primary key field']);
            }
            if ($entity->isDirty('chin')) {
                $entity->setError('chin', ['key' => 'Cannot change primary key field']);
            }
        }
    }

    public function afterSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        if ($entity->isDirty('status') && $entity->get('status') === CharacterStatus::Active) {
            $this->updateQuery()
                ->set(['status' => CharacterStatus::Inactive])
                ->where([
                    'plin' => $entity->get('plin'),
                    'chin !=' => $entity->get('chin'),
                    'status' => CharacterStatus::Active,
                ])
                ->execute();
        }
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->addCreate($rules->isUnique(['plin', 'chin']));
        $rules->addCreate($rules->isUnique(['plin', 'name']));

        $rules->add($rules->existsIn('faction_id', 'Factions'));

        $rules->addDelete([$this, 'ruleNoAssociation'], ['skills']);
        $rules->addDelete([$this, 'ruleNoAssociation'], ['conditions']);
        $rules->addDelete([$this, 'ruleNoAssociation'], ['powers']);
        $rules->addDelete([$this, 'ruleNoAssociation'], ['items']);

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
            'MyStudents' => ['Student.Skills', 'Skill.Manatypes'],
        ];
    }

    protected function orderBy(): array
    {
        return ['plin' => 'ASC', 'chin' => 'DESC'];
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

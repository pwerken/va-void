<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Enum\CharacterStatus;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;

class CharactersTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setColumnEnumType('status', CharacterStatus::class);

        $this->belongsTo('Players')->setForeignKey('plin');
        $this->belongsTo('Factions')->setProperty('faction_object');

        $this->hasMany('Items');

        $this->belongsToManyThrough('Skills', 'CharactersSkills');
        $this->belongsToManyThrough('Imbues', 'CharactersImbues');
        $this->belongsToManyThrough('Powers', 'CharactersPowers');
        $this->belongsToManyThrough('Conditions', 'CharactersConditions');

        $this->belongsToMany('RuneImbues', [
            'propertyName' => 'runeimbues',
            'className' => 'Imbues',
            'targetForeignKey' => 'imbue_id',
            'through' => 'CharactersRuneImbues',
        ]);
        $this->belongsToMany('GlyphImbues', [
            'propertyName' => 'glyphimbues',
            'className' => 'Imbues',
            'targetForeignKey' => 'imbue_id',
            'through' => 'CharactersGlyphImbues',
        ]);

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
        $rules = parent::buildRules($rules);

        $rules->addCreate($rules->isUnique(['plin', 'chin']));
        $rules->addCreate($rules->isUnique(['plin', 'name']));

        $rules->addUpdate([$this, 'ruleDisallowSetPlinChin']);

        $rules->addDelete([$this, 'ruleNoAssociation'], ['skills']);
        $rules->addDelete([$this, 'ruleNoAssociation'], ['powers']);
        $rules->addDelete([$this, 'ruleNoAssociation'], ['conditions']);
        $rules->addDelete([$this, 'ruleNoAssociation'], ['items']);

        $rules->add($rules->existsIn('faction_id', 'Factions'));

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

    public function ruleDisallowSetPlinChin(EntityInterface $entity, array $options): bool
    {
        $allowed = true;

        if ($entity->isDirty('plin')) {
            $entity->setError('plin', ['key' => 'Cannot change primary key field']);
            $allowed = false;
        }
        if ($entity->isDirty('chin')) {
            $entity->setError('chin', ['key' => 'Cannot change primary key field']);
            $allowed = false;
        }

        return $allowed;
    }

    protected function contain(): array
    {
        return [
            'Factions',
            'Players',
            'Skills' => ['Manatypes'],
            'GlyphImbues' => ['Manatypes'],
            'RuneImbues' => ['Manatypes'],
            'Powers' => ['Manatypes'],
            'Conditions' => ['Manatypes'],
            'Items' => ['Manatypes'],
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

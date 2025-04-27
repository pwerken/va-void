<?php
declare(strict_types=1);

namespace App\Model\Table;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

use App\Model\Entity\Character;

class CharactersTable
    extends AppTable
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

    public function plinChin($plin, $chin)
    {
        return $this->findByPlayerIdAndChin($plin, $chin)->firstOrFail();
    }

    public function afterMarshal(EventInterface $event, EntityInterface $entity, ArrayObject $data, ArrayObject $options): void
    {
        parent::afterMarshal($event, $entity, $data, $options);

        if(!$entity->isNew())
        {
            if($entity->isDirty('player_id')) {
                $entity->setError('player_id',
                    ['key' => 'Cannot change primary key field']);
            }
            if($entity->isDirty('chin')) {
                $entity->setError('chin',
                    ['key' => 'Cannot change primary key field']);
            }
        }
    }

    public function afterSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        if($entity->isDirty('status') && $entity->status == 'active') {
            $chars = $this->findByPlayerId($entity->player_id);
            foreach($chars as $char) {
                if($char->id == $entity->id || $char->status != 'active') {
                    continue;
                }
                $char->status = 'inactive';
                $this->save($char);
            }
        }
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

        $data['faction_id'] = $this->nameToId('Factions', @$data['faction']);

        parent::beforeMarshal($event, $data, $options);
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

    public function findBelief(SelectQuery $query)
    {
        return $query->select(['name' => 'belief'])
                    ->distinct(['belief'])
                    ->orderBy(['belief'], true);
    }

    public function findGroup(SelectQuery $query)
    {
        return $query->select(['name' => 'group'])
                    ->distinct(['group'])
                    ->orderBy(['group'], true);
    }

    public function findWorld(SelectQuery $query)
    {
        return $query->select(['name' => 'world'])
                    ->distinct(['world'])
                    ->orderBy(['world'], true);
    }

    public function ruleNoConditions($entity, $options)
    {
        $query = $this->CharactersConditions->find();
        $query->where(['character_id' => $entity->id]);

        if($query->count() > 0) {
            $entity->setError('conditions', $this->consistencyError);
            return false;
        }

        return true;
    }

    public function ruleNoItems($entity, $options)
    {
        $query = $this->Items->find();
        $query->where(['character_id' => $entity->id]);

        if($query->count() > 0) {
            $entity->setError('items', $this->consistencyError);
            return false;
        }

        return true;
    }

    public function ruleNoPowers($entity, $options)
    {
        $query = $this->CharactersPowers->find();
        $query->where(['character_id' => $entity->id]);

        if($query->count() > 0) {
            $entity->setError('powers', $this->consistencyError);
            return false;
        }

        return true;
    }

    public function ruleNoSkills($entity, $options)
    {
        $query = $this->CharactersSkills->find();
        $query->where(['character_id' => $entity->id]);

        if($query->count() > 0) {
            $entity->setError('skills', $this->consistencyError);
            return false;
        }

        return true;
    }

    protected function contain(): array
    {
        return
            [ 'Factions', 'Players', 'Items'
            , 'CharactersConditions.Conditions'
            , 'CharactersPowers.Powers'
            , 'CharactersSkills.Skills.Manatypes'
            , 'MyTeacher'  =>   [ 'Teacher', 'Student', 'Skills.Manatypes'
                                , 'Started', 'Updated' ]
            , 'MyStudents' =>   [ 'Teacher', 'Student', 'Skills.Manatypes'
                                , 'Started', 'Updated' ]
            ];
    }

    protected function orderBy(): array
    {
        return ['player_id' => 'ASC', 'chin' => 'DESC'];
    }

    protected function nameToId($model, $name)
    {
        if(empty($name)) {
            $name = "-";
        }

        $result = $this->$model->findByName($name)
                    ->select('id', true)
                    ->enableHydration(false)
                    ->first();
        if(is_null($result)) {
            return 0;
        }
        return $result['id'];
    }
}

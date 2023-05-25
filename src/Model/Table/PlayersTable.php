<?php
declare(strict_types=1);

namespace App\Model\Table;

use ArrayObject;
use Cake\Event\EventInterface;
use Cake\ORM\RulesChecker;

class PlayersTable
    extends AppTable
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->hasMany('Characters');
    }

    public function beforeMarshal(EventInterface $event, ArrayObject $data, ArrayObject $options): void
    {
        parent::beforeMarshal($event, $data, $options);

        if(isset($data['plin'])) {
            $data['id'] = $data['plin'];
        }
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->addCreate([$this, 'rulePlinInUse'],
            'unique', [ 'errorField' => 'plin' ]);
        $rules->addDelete([$this, 'ruleNoCharacters'],
            'consistency', [ 'errorField' => 'characters' ]);
        return $rules;
    }

    public function rulePlinInUse($entity, $options)
    {
        if($this->exists(['id' => $entity->plin])) {
            return 'PLIN already in use';
        }

        return true;
    }

    public function ruleNoCharacters($entity, $options)
    {
        $query = $this->Characters->find();
        $query->where(['player_id' => $entity->id]);

        if($query->count() > 0) {
            return 'Character reference(s) present';
        }

        return true;
    }

    protected function contain(): array
    {
        return [ 'Characters' ];
    }

    protected function orderBy(): array
    {
        return  [ 'id' => 'ASC' ];
    }
}

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
        $rules->addCreate([$this, 'rulePlinInUse']);

        $rules->addDelete([$this, 'ruleNoCharacters']);

        return $rules;
    }

    public function ruleNoCharacters($entity, $options)
    {
        $query = $this->Characters->find();
        $query->where(['player_id' => $entity->id]);

        if($query->count() > 0) {
            $entity->setError('characters', $this->consistencyError);
            return false;
        }

        return true;
    }

    public function rulePlinInUse($entity, $options)
    {
        if($this->exists(['id' => $entity->plin])) {
            $entity->setError('plin', ['unique' => 'PLIN already in use']);
            return false;
        }

        return true;
    }

    protected function contain(): array
    {
        return ['Characters'];
    }

    protected function orderBy(): array
    {
        return ['id' => 'ASC'];
    }
}

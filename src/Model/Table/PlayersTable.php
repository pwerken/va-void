<?php
declare(strict_types=1);

namespace App\Model\Table;

use ArrayObject;
use Cake\Event\EventInterface;
use Cake\ORM\RulesChecker;
use Cake\Routing\Router;

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
        if(isset($data['plin'])) {
            $data['id'] = $data['plin'];
            unset($data['plin']);
        }

        parent::beforeMarshal($event, $data, $options);
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->addCreate($rules->isUnique(['id']));

        $rules->add($rules->isUnique(['email']));
        $rules->add([$this, 'ruleAuthCheck']);

        $rules->addDelete([$this, 'ruleNoCharacters']);

        return $rules;
    }

    public function ruleAuthCheck($entity, $options)
    {
        if(!$entity->isDirty('role')) {
            return true;
        }

        $user = Router::getRequest()->getAttribute('identity');
        if(!$user->hasAuth($entity->getOriginal('role'))) {
            $entity->setError('role', ['authorization' =>
                'Cannot demote user with more authorization than you']);
            return false;
        }

        if(!$user->hasAuth($entity->get('role'))) {
            $entity->setError('role', ['authorization' =>
                'Cannot promote user above your own authorization']);
            return false;
        }

        return true;
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

    protected function contain(): array
    {
        return ['Characters'];
    }

    protected function orderBy(): array
    {
        return ['id' => 'ASC'];
    }
}

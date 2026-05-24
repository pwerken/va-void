<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Player;
use App\Model\Enum\PlayerRole;
use Cake\ORM\RulesChecker;
use Cake\Routing\Router;

/**
 * @extends \App\Model\Table\Table<\App\Model\Entity\Player>
 *
 * Relations:
 * @property \App\Model\Table\CharactersTable     $Characters;
 * @property \App\Model\Table\SocialProfilesTable $SocialProfiles;
 */
class PlayersTable extends Table
{
    protected bool $allowSetPrimaryOnCreate = true;

    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setPrimaryKey('plin');

        $this->setColumnEnumType('role', PlayerRole::class);

        $this->hasMany('Characters')->setForeignKey('plin');
        $this->hasMany('SocialProfiles')
                ->setProperty('socials')
                ->setForeignKey('user_id');
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules = parent::buildRules($rules);

        $rules->addCreate($rules->isUnique(['plin']));

        $rules->addDelete([$this, 'ruleNoAssociation'], ['characters']);

        $rules->add([$this, 'ruleAuthCheck']);

        return $rules;
    }

    public function ruleAuthCheck(Player $entity, array $options): bool
    {
        if (!$entity->isDirty('role')) {
            return true;
        }

        $user = Router::getRequest()->getAttribute('identity');
        $prevAuth = $entity->getOriginal('role')->toAuth();
        if (!$user->hasAuth($prevAuth)) {
            $entity->setError('role', ['authorization' => 'Cannot demote user with more authorization than you']);

            return false;
        }

        if (!$user->hasAuth($entity->role->toAuth())) {
            $entity->setError('role', ['authorization' => 'Cannot promote user above your own authorization']);

            return false;
        }

        return true;
    }

    protected function contain(): array
    {
        return ['Characters', 'SocialProfiles'];
    }

    protected function orderBy(): array
    {
        return ['plin' => 'ASC'];
    }
}

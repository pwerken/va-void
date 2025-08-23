<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use App\Model\Entity\Condition;
use App\Model\Entity\Entity;
use App\Model\Enum\Authorization;
use Authorization\IdentityInterface as User;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;

class ConditionPolicy extends EntityPolicy
{
    use LocatorAwareTrait;

    public function __construct()
    {
        parent::__construct();

        $this->showFieldAuth('notes', Authorization::ReadOnly);
        $this->showFieldAuth('referee_notes', Authorization::ReadOnly);
        $this->showFieldAuth('characters', Authorization::ReadOnly);
    }

    public function canAdd(User $identity, Condition $obj): bool
    {
        return $this->hasAuthObj($obj, Authorization::Referee);
    }

    public function canDelete(User $identity, Condition $obj): bool
    {
        return $this->canAdd($identity, $obj);
    }

    public function canEdit(User $identity, Condition $obj): bool
    {
        return $this->canAdd($identity, $obj);
    }

    public function canView(User $identity, Condition $obj): bool
    {
        return $this->hasAuthObj($obj, Authorization::ReadOnly, Authorization::Owner);
    }

    protected function hasRoleUser(int $plin, ?Entity $obj): bool
    {
        if (is_null($obj)) {
            return false;
        }

        $coin = $obj->get('coin');

        $query = $this->getTableLocator()->get('Characters')->find();
        $query->where(['Characters.plin' => $plin]);
        $query->matching(
            'CharactersConditions',
            function (Query $query) use ($coin) {
                return $query->where(['CharactersConditions.condition_id' => $coin]);
            },
        );

        return $query->count() > 0;
    }
}

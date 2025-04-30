<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use App\Model\Entity\Condition;
use App\Model\Entity\Entity;
use Authorization\IdentityInterface as User;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;

class ConditionPolicy extends EntityPolicy
{
    use LocatorAwareTrait;

    public function __construct()
    {
        parent::__construct();

        $this->showFieldAuth('notes', ['read-only']);
        $this->showFieldAuth('referee_notes', ['read-only']);
        $this->showFieldAuth('characters', ['read-only']);
    }

    public function canAdd(User $identity, Condition $obj): bool
    {
        return $this->hasAuth(['referee'], $obj);
    }

    public function canView(User $identity, Condition $obj): bool
    {
        return $this->hasAuth(['read-only', 'user'], $obj);
    }

    public function canEdit(User $identity, Condition $obj): bool
    {
        return $this->canAdd($identity, $obj);
    }

    public function canDelete(User $identity, Condition $obj): bool
    {
        return $this->canAdd($identity, $obj);
    }

    protected function hasRoleUser(int $plin, Entity $obj): bool
    {
        $coin = $obj->id;

        $query = $this->getTableLocator()->get('Characters')->find();
        $query->where(['Characters.player_id' => $plin]);
        $query->matching(
            'CharactersConditions',
            function (Query $query) use ($coin) {
                return $query->where(['CharactersConditions.condition_id' => $coin]);
            },
        );

        return $query->count() > 0;
    }
}

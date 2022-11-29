<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use Authorization\IdentityInterface as User;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;

use App\Model\Entity\AppEntity;
use App\Model\Entity\Condition;

class ConditionPolicy
    extends AppEntityPolicy
{
    use LocatorAwareTrait;

    public function __construct()
    {
        parent::__construct();

        $this->showFieldAuth('notes', ['read-only']);
        $this->showFieldAuth('referee_notes', ['read-only']);
        $this->showFieldAuth('characters', ['read-only']);
    }

    public function canView(User $identity, Condition $condition): bool
    {
        return $this->hasAuth(['read-only', 'user'], $condition);
    }

    protected function hasRoleUser(int $plin, AppEntity $obj): bool
    {
        $coin = $obj->id;

        $query = $this->getTableLocator()->get('Characters')->find();
        $query->where(['Characters.player_id' => $plin]);
        $query->matching('CharactersConditions',
            function (Query $query) use ($coin) {
                return $query->where(['CharactersConditions.condition_id' => $coin]);
            }
        );
        return $query->count() > 0;
    }
}

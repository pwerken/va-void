<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use Authorization\IdentityInterface as User;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;

use App\Model\Entity\AppEntity;
use App\Model\Entity\Power;

class PowerPolicy
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

    public function canAdd(User $identity, Power $obj): bool
    {
        return $this->hasAuth(['referee'], $obj);
    }

    public function canView(User $identity, Power $obj): bool
    {
        return $this->hasAuth(['read-only', 'user'], $obj);
    }

    public function canEdit(User $identity, Power $obj): bool
    {
        return $this->canAdd($identity, $obj);
    }

    public function canDelete(User $identity, Power $obj): bool
    {
        return $this->canAdd($identity, $obj);
    }

    protected function hasRoleUser(int $plin, AppEntity $obj): bool
    {
        $poin = $obj->id;

        $query = $this->getTableLocator()->get('Characters')->find();
        $query->where(['Characters.player_id' => $plin]);
        $query->matching('CharactersPowers',
            function (Query $query) use ($poin) {
                return $query->where(['CharactersPowers.power_id' => $poin]);
            }
        );
        return $query->count() > 0;
    }
}

<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use App\Model\Entity\Entity;
use App\Model\Entity\Imbue;
use App\Model\Enum\Authorization;
use Authorization\IdentityInterface as User;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;

class ImbuePolicy extends EntityPolicy
{
    use LocatorAwareTrait;

    public function __construct()
    {
        parent::__construct();

        $this->showFieldAuth('notes', Authorization::ReadOnly);
        $this->showFieldAuth('characters', Authorization::ReadOnly);
    }

    public function canAdd(User $identity, Imbue $obj): bool
    {
        return $this->hasAuthObj($obj, Authorization::Referee);
    }

    public function canDelete(User $identity, Imbue $obj): bool
    {
        return $this->canAdd($identity, $obj);
    }

    public function canEdit(User $identity, Imbue $obj): bool
    {
        return $this->canAdd($identity, $obj);
    }

    public function canView(User $identity, Imbue $obj): bool
    {
        return $this->hasAuthObj($obj, Authorization::ReadOnly, Authorization::Owner);
    }

    protected function hasRoleUser(int $plin, ?Entity $obj): bool
    {
        $id = $obj?->get('id');
        if (is_null($id)) {
            return false;
        }

        $query = $this->getTableLocator()->get('Characters')->find();
        $query->where(['Characters.plin' => $plin]);
        $query->matching(
            'Imbues',
            function (Query $query) use ($id) {
                return $query->where(['CharactersImbues.imbue_id' => $id]);
            },
        );

        return $query->count() > 0;
    }
}

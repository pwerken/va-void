<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use Authorization\IdentityInterface as User;

use App\Model\Entity\Lammy;

class LammyPolicy
    extends AppEntityPolicy
{
    public function __construct()
    {
        parent::__construct();

        $this->showFieldAuth('characters', ['read-only']);
    }

    public function canAdd(User $identity, Lammy $obj)
    {
        return $this->hasAuth(['super'], $obj);
    }

    public function canView(User $identity, Lammy $obj)
    {
        return $this->hasAuth(['read-only'], $obj);
    }

    public function canEdit(User $identity, Lammy $obj)
    {
        return $this->canAdd($identity, $obj);
    }

    public function canDelete(User $identity, Lammy $obj)
    {
        return $this->canAdd($identity, $obj);
    }
}

<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use App\Model\Entity\Attribute;
use Authorization\IdentityInterface as User;

class AttributePolicy extends EntityPolicy
{
    public function __construct()
    {
        parent::__construct();

        $this->showFieldAuth('name', ['read-only']);
    }

    public function canView(User $identity, Attribute $obj): bool
    {
        return $this->hasAuth(['read-only'], $obj);
    }
}

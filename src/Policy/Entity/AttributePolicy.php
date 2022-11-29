<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use Authorization\IdentityInterface as User;

use App\Model\Entity\Attribute;

class AttributePolicy
    extends AppEntityPolicy
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

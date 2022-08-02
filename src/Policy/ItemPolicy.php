<?php
declare(strict_types=1);

namespace App\Policy;

use Authorization\IdentityInterface;

use App\Model\Entity\Item;

class ItemPolicy
    extends AppEntityPolicy
{

    public function __construct()
    {
        parent::__construct();

        $this->showFieldAuth('attributes', ['read-only']);
        $this->showFieldAuth('notes', ['read-only']);
        $this->showFieldAuth('referee_notes', ['read-only']);
    }

    public function canView(IdentityInterface $identity, Item $item)
    {
        $this->identity = $identity;
        return $this->hasAuth(['read-only', 'user'], $item);
    }

}

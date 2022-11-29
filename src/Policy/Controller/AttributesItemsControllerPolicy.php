<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class AttributesItemsControllerPolicy
    extends AppControllerPolicy
{
    public function attributesIndex(): bool
    {
        return $this->hasAuth('read-only');
    }

    public function itemsAdd(): bool
    {
        return $this->hasAuth('referee');
    }

    public function itemsDelete(): bool
    {
        return $this->hasAuth('referee');
    }

    public function itemsEdit(): bool
    {
        return false;
    }

    public function itemsIndex(): bool
    {
        return $this->hasAuth('read-only');
    }

    public function itemsView(): bool
    {
        return $this->itemsIndex();
    }
}

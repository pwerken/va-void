<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class AttributesItemsControllerPolicy
    extends AppControllerPolicy
{
    public function attributesIndex()
    {
        return $this->hasAuth('read-only');
    }

    public function itemsAdd()
    {
        return $this->hasAuth('referee');
    }

    public function itemsDelete()
    {
        return $this->hasAuth('referee');
    }

    public function itemsEdit()
    {
        return false;
    }

    public function itemsIndex()
    {
        return $this->hasAuth('read-only');
    }

    public function itemsView()
    {
        return $this->itemsIndex();
    }
}

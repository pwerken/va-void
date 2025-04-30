<?php
declare(strict_types=1);

namespace App\Policy\Controller;

class AttributesItemsControllerPolicy extends ControllerPolicy
{
    // GET /attributes/:id/items

    public function attributesIndex(): bool
    {
        return $this->hasAuth('read-only');
    }

    // GET /items/:itin/attributes

    public function itemsIndex(): bool
    {
        return $this->hasAuth('read-only');
    }

    // PUT /items/:itin/attributes

    public function itemsAdd(): bool
    {
        return $this->hasAuth('referee');
    }

    // GET /items/:itin/attributes/:id

    public function itemsView(): bool
    {
        return $this->itemsIndex();
    }

    // PUT /items/:itin/attributes/:id

    public function itemsEdit(): bool
    {
        return false;
    }

    // DELETE /items/:itin/attributes/:id
    public function itemsDelete(): bool
    {
        return $this->itemsAdd();
    }
}

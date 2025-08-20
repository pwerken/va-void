<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use App\Model\Entity\Entity;
use App\Model\Enum\Authorization;
use App\Policy\Policy;
use Authorization\IdentityInterface as User;

abstract class EntityPolicy extends Policy
{
    private array $showFieldAuth = [];
    private array $editFieldAuth = [];

    public function __construct()
    {
        $this->showFieldAuth('creator_id', Authorization::ReadOnly);
        $this->showFieldAuth('modifier_id', Authorization::ReadOnly);
    }

    public function scopeAccessible(User $identity, Entity $obj): void
    {
        $audit_fields = ['created', 'creator_id', 'modified', 'modifier_id'];
        $obj->setAccess($audit_fields, false);

        foreach ($this->editFieldAuth as $field => $access) {
            $obj->setAccess($field, $this->hasAuthObj($obj, ...$access));
        }
    }

    public function scopeVisible(User $identity, Entity $obj): void
    {
        foreach ($this->showFieldAuth as $field => $access) {
            if ($this->hasAuthObj($obj, ...$access)) {
                continue;
            }
            $obj->setHidden([$field], true);
        }
    }

    protected function editFieldAuth(string $field, Authorization ...$auth): void
    {
        $this->editFieldAuth[$field] = $auth;
    }

    protected function showFieldAuth(string $field, Authorization ...$auth): void
    {
        $this->showFieldAuth[$field] = $auth;
    }
}

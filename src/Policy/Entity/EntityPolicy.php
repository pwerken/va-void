<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use App\Model\Entity\Entity;
use App\Policy\Policy;
use Authorization\IdentityInterface as User;

abstract class EntityPolicy extends Policy
{
    private array $showFieldAuth = [];
    private array $editFieldAuth = [];

    public function __construct()
    {
        $this->showFieldAuth('creator_id', 'read-only');
        $this->showFieldAuth('modifier_id', 'read-only');
    }

    public function scopeAccessible(User $identity, Entity $obj): void
    {
        $audit_fields = ['created', 'creator_id', 'modified', 'modifier_id'];
        $obj->setAccess($audit_fields, false);

        foreach ($this->editFieldAuth as $field => $access) {
            $obj->setAccess($field, $this->hasAuth($access, $obj));
        }
    }

    public function scopeVisible(User $identity, Entity $obj): void
    {
        foreach ($this->showFieldAuth as $field => $access) {
            if ($this->hasAuth($access, $obj)) {
                continue;
            }
            $obj->setHidden([$field], true);
        }
    }

    protected function editFieldAuth(string $field, string|array $auth): void
    {
        if (is_string($auth)) {
            $auth = [ $auth ];
        }
        $this->editFieldAuth[$field] = $auth;
    }

    protected function showFieldAuth(string $field, string|array $auth): void
    {
        if (is_string($auth)) {
            $auth = [ $auth ];
        }
        $this->showFieldAuth[$field] = $auth;
    }
}

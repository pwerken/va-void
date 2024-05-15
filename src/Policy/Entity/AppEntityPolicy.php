<?php
declare(strict_types=1);

namespace App\Policy\Entity;

use Authorization\IdentityInterface as User;
use Authorization\Policy\BeforePolicyInterface;

use App\Model\Entity\AppEntity;
use App\Policy\AppPolicy;

abstract class AppEntityPolicy
    extends AppPolicy
    implements BeforePolicyInterface
{
    private array $showFieldAuth = [];
    private array $editFieldAuth = [];

    public function __construct()
    {
        $this->showFieldAuth('creator_id', 'read-only');
        $this->showFieldAuth('modifier_id', 'read-only');
    }

    /**
     * This method is called just prior to the 'can{$action}' check.
     */
    public function before(?User $identity, $resource, $action): void
    {
        $this->setIdentity($identity);
    }

    public function scopeAccesible(User $identity, AppEntity $obj): void
    {
        $this->setIdentity($identity);

        $audit_fields = ['created', 'creator_id', 'modified', 'modifier_id'];
        $obj->setAccess($audit_fields, false);

        foreach($this->editFieldAuth as $field => $access)
        {
            $obj->setAccess($field, $this->hasAuth($access, $obj));
        }
    }

    public function scopeVisible(User $identity, AppEntity $obj): void
    {
        $this->setIdentity($identity);

        foreach($this->showFieldAuth as $field => $access)
        {
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

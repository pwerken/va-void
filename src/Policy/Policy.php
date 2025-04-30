<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Entity;
use Authorization\IdentityInterface as User;

abstract class Policy
{
    private ?User $identity;

    protected function setIdentity(?User $identity): void
    {
        $this->identity = $identity;
    }

    protected function hasAuth(string|array $roles, ?Entity $obj = null): bool
    {
        if (is_null($this->identity)) {
            return false;
        }

        if (is_string($roles)) {
            $roles = [$roles];
        }

        foreach ($roles as $role) {
            if (strcasecmp($role, 'user') == 0) {
                if (is_null($obj)) {
                    continue;
                }
                $plin = (int)$this->identity->getIdentifier();
                if ($this->hasRoleUser($plin, $obj)) {
                    return true;
                }
            } elseif ($this->identity->hasAuth($role)) {
                return true;
            }
        }

        return false;
    }

    protected function hasRoleUser(int $plin, Entity $obj): bool
    {
        return false;
    }
}

<?php
declare(strict_types=1);

namespace App\Policy;

use Authorization\IdentityInterface as User;

use App\Model\Entity\AppEntity;

abstract class AppPolicy
{
    private $identity;

    protected function setIdentity(?User $identity): void
    {
        $this->identity = $identity;
    }

    protected function hasAuth(string|array $roles, ?AppEntity $obj = NULL): bool
    {
        if (is_null($this->identity))
            return false;

        if (is_string($roles))
            $roles = [$roles];

        foreach($roles as $role) {
            if (strcasecmp($role, 'user') == 0) {
                if (is_null($obj)) {
                    continue;
                }
                $plin = (int)$this->identity->getIdentifier();
                if ($this->hasRoleUser($plin, $obj)) {
                    return true;
                }
            }
            elseif ($this->identity->hasAuth($role)) {
                return true;
            }
        }
        return false;
    }

    protected function hasRoleUser(int $plin, AppEntity $obj): bool
    {
        return false;
    }
}

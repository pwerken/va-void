<?php
declare(strict_types=1);

namespace App\Policy;

use Authorization\IdentityInterface as User;

use App\Model\Entity\AppEntity;
use App\Model\Entity\Player;

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
            $roles = [ $roles ];

        foreach($roles as $role) {
            if ($this->hasRole($role, $obj)) {
                return true;
            }
        }
        return false;
    }

    protected function hasRoleUser(int $plin, AppEntity $obj): bool
    {
        return false;
    }

    private function hasRole(string $role, ?AppEntity $obj): bool
    {
        if (strcasecmp($role, 'user') == 0) {
            if (is_null($obj)) {
                return false;
            }
            $plin = (int)$this->identity->getIdentifier();
            return $this->hasRoleUser($plin, $obj);
        }

        $need = self::roleToInt($role);
        $have = self::roleToInt($this->identity->get('role'));
        return $need <= $have;
    }

    private function roleToInt(string $role): int
    {
        foreach(Player::roleValues() as $key => $value) {
            if(strcasecmp($role, $value) == 0)
                return $key + 1;
        }
        return $key + 2;
    }
}

<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Entity;
use App\Model\Entity\Player;
use Authorization\IdentityInterface as User;
use Authorization\Policy\BeforePolicyInterface;
use Authorization\Policy\BeforeScopeInterface;

abstract class Policy implements BeforePolicyInterface, BeforeScopeInterface
{
    private ?Player $identity = null;

    public function before(?User $identity, mixed $resource, string $action): null
    {
        $this->setIdentity($identity);

        return null;
    }

    public function beforeScope(?User $identity, mixed $resource, string $action): null
    {
        return $this->before($identity, $resource, $action);
    }

    protected function getPlin(): int
    {
        return $this->identity->id;
    }

    protected function setIdentity(?User $identity): void
    {
        if ($identity instanceof Player) {
            $this->identity = $identity;
        }
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
                if ($this->hasRoleUser($this->getPlin(), $obj)) {
                    return true;
                }
            } elseif ($this->identity->hasAuth($role)) {
                return true;
            }
        }

        return false;
    }

    protected function hasRoleUser(int $plin, ?Entity $obj): bool
    {
        return false;
    }
}

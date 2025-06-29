<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Entity;
use Authorization\IdentityInterface as User;
use Authorization\Policy\BeforePolicyInterface;
use Authorization\Policy\BeforeScopeInterface;

abstract class Policy implements BeforePolicyInterface, BeforeScopeInterface
{
    private ?User $identity;

    public function before(?User $identity, mixed $resource, string $action): null
    {
        $this->setIdentity($identity);

        return null;
    }

    public function beforeScope(?User $identity, mixed $resource, string $action): null
    {
        $this->setIdentity($identity);

        return null;
    }

    protected function getPlin(): ?int
    {
        if (is_null($this->identity)) {
            return null;
        }

        return $this->identity->getOriginalData()['id'];
    }

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

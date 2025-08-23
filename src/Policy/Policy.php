<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Entity;
use App\Model\Entity\Player;
use App\Model\Enum\Authorization;
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
        return $this->identity->get('plin');
    }

    protected function setIdentity(?User $identity): void
    {
        if ($identity instanceof Player) {
            $this->identity = $identity;
        }
    }

    protected function hasAuth(Authorization ...$roles): bool
    {
        return $this->hasAuthObj(null, ...$roles);
    }

    protected function hasAuthObj(?Entity $obj, Authorization ...$roles): bool
    {
        if (is_null($this->identity)) {
            return false;
        }

        $authorization = $this->identity->get('role')->toAuth();
        foreach ($roles as $role) {
            if ($role === Authorization::Owner) {
                if ($this->hasRoleUser($this->getPlin(), $obj)) {
                    return true;
                }
            } elseif ($authorization->hasAuth($role)) {
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

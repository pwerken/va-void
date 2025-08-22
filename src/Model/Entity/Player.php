<?php
declare(strict_types=1);

namespace App\Model\Entity;

use App\Model\Enum\Authorization;
use App\Model\Enum\PlayerRole;
use ArrayAccess;
use Authentication\IdentityInterface as AuthenticationIdentity;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Authorization\AuthorizationServiceInterface;
use Authorization\IdentityInterface as AuthorizationIdentity;
use Authorization\Policy\ResultInterface;

class Player extends Entity implements AuthenticationIdentity, AuthorizationIdentity
{
    protected array $_defaults = [
        'role' => PlayerRole::Player,
    ];

    protected AuthorizationServiceInterface $authorization;

    public function __construct(array $properties = [], array $options = [])
    {
        parent::__construct($properties, $options);

        $this->setVirtual(['name']);
    }

    protected function _setPassword(?string $password): ?string
    {
        if (empty($password)) {
            return null;
        }

        return (new DefaultPasswordHasher())->hash($password);
    }

    public static function labelPassword(mixed $value = null): bool
    {
        return isset($value);
    }

    protected function _getName(): string
    {
        $name = [$this->get('first_name'), $this->get('insertion'), $this->get('last_name')];

        return implode(' ', array_filter($name));
    }

    public function hasAuth(Authorization $role): bool
    {
        return $this->get('role')->toAuth()->hasAuth($role);
    }

    public function setAuthorization(AuthorizationServiceInterface $service): self
    {
        $this->authorization = $service;

        return $this;
    }

    public function getIdentifier(): int
    {
        return $this->id;
    }

    public function can(string $action, mixed $resource): bool
    {
        return $this->authorization->can($this, $action, $resource);
    }

    public function canResult(string $action, mixed $resource): ResultInterface
    {
        return $this->authorization->canResult($this, $action, $resource);
    }

    public function applyScope(string $action, mixed $resource, mixed ...$optionalArgs): mixed
    {
        return $this->authorization->applyScope($this, $action, $resource, $optionalArgs);
    }

    public function getOriginalData(): ArrayAccess|array
    {
        return $this;
    }
}

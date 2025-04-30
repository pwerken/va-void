<?php
declare(strict_types=1);

namespace App\Model\Entity;

use ArrayAccess;
use Authentication\IdentityInterface;
use Authentication\PasswordHasher\DefaultPasswordHasher;

class Player extends Entity implements IdentityInterface
{
    protected array $_defaults = [ 'role' => 'Player' ];

    public function __construct(array $properties = [], array $options = [])
    {
        parent::__construct($properties, $options);

        $this->setCompact(['id', 'full_name']);
        $this->setVirtual(['full_name']);
    }

    protected function _setPassword(?string $password): ?string
    {
        if (empty($password)) {
            return null;
        }

        return (new DefaultPasswordHasher())->hash($password);
    }

    public function getIdentifier(): string
    {
        return (string)$this->id;
    }

    public function getOriginalData(): ArrayAccess|array
    {
        return $this;
    }

    public static function labelPassword(mixed $value = null): bool
    {
        return isset($value);
    }

    public static function roleValues(): array
    {
        static $data = null;
        if (is_null($data)) {
            $data = ['Player', 'Read-only', 'Referee', 'Infobalie', 'Event Control', 'Super'];
        }

        return $data;
    }

    protected function _getFullName(): string
    {
        $name = [$this->get('first_name'), $this->get('insertion'), $this->get('last_name')];

        return implode(' ', array_filter($name));
    }

    public function hasAuth(string $role): bool
    {
        $want = self::roleToInt($role);
        $have = self::roleToInt($this->get('role'));

        return $want > 0 && $want <= $have;
    }

    private function roleToInt(string $role): int
    {
        foreach (self::roleValues() as $key => $value) {
            if (strcasecmp($role, $value) == 0) {
                return $key + 1;
            }
        }

        return 0;
    }
}

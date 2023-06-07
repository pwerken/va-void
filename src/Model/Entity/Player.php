<?php
namespace App\Model\Entity;

use Authentication\IdentityInterface;
use Cake\Auth\DefaultPasswordHasher;

class Player
    extends AppEntity
    implements IdentityInterface
{
    protected $_defaults =
            [ 'role'        => 'Player'
            ];

    public function __construct($properties = [], $options = [])
    {
        parent::__construct($properties, $options);

        $this->setCompact(['id', 'full_name']);
        $this->setVirtual(['full_name']);
    }

    public function _setPassword($password)
    {
        if(empty($password))
            return NULL;

        return (new DefaultPasswordHasher())->hash($password);
    }

    public function getIdentifier()
    {
        return (string)$this->id;
    }

    public function getOriginalData()
    {
        return $this;
    }

    public static function labelPassword($value = null)
    {
        return isset($value);
    }

    public static function roleValues()
    {
        static $data = null;
        if(is_null($data))
            $data = ['Player', 'Read-only', 'Referee', 'Infobalie', 'Super'];
        return $data;
    }
    public static function genderValues()
    {
        static $data = null;
        if(is_null($data))
            $data = ['F', 'M'];
        return $data;
    }

    protected function _getFullName()
    {
        $name = [$this->first_name, $this->insertion, $this->last_name];
        return implode(' ', array_filter($name));
    }

    public function hasAuth(string $role): bool
    {
        $want = self::roleToInt($role);
        $have = self::roleToInt($this->get('role'));

        return $want <= $have;
    }

    private function roleToInt(string $role): int
    {
        foreach(self::roleValues() as $key => $value) {
            if(strcasecmp($role, $value) == 0)
                return $key + 1;
        }
        return $key + 2;
    }
}

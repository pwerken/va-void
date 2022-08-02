<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Player;

class AppPolicy
{
    protected $identity;

    protected function getOwner($object)
    {
        return -2;
    }

    protected function hasAuth($roles, $object = NULL)
    {
        if (is_null($this->identity))
            return false;

        if (is_string($roles))
            $roles = [ $roles ];

        foreach($roles as $role) {
            if ($this->hasRole($role, $object)) {
                return true;
            }
        }
        return false;
    }

    private function hasRole($role, $object)
    {
        if (strcasecmp($role, 'user') == 0) {
            return $this->identity->getIdentifier() == $this->getOwner($object);
        }

        $need = self::roleToInt($role);
        $have = self::roleToInt($this->identity->get('role'));
        return $need <= $have;
    }

    private function roleToInt($role)
    {
        foreach(Player::roleValues() as $key => $value) {
            if(strcasecmp($role, $value) == 0)
                return $key + 1;
        }
        return $key + 2;
    }
}

<?php
declare(strict_types=1);

namespace App\Policy;

use Authorization\IdentityInterface;

use App\Model\Entity\AppEntity;

abstract class AppEntityPolicy
    extends AppPolicy
{

    protected $_showFieldAuth = [];

    public function __construct()
    {
        $this->showFieldAuth('modifier_id', 'read-only');
    }

    public function scopeVisible(IdentityInterface $identity, AppEntity $object)
    {
        $this->identity = $identity;
        foreach($this->_showFieldAuth as $field => $access)
        {
            if ($this->hasAuth($access, $object)) {
                continue;
            }
            $object->setHidden([$field], true);
        }
    }

    protected function showFieldAuth($field, $auth)
    {
        if (is_string($auth)) {
            $auth = [$auth];
        }
        $this->_showFieldAuth[$field] = $auth;
    }
}

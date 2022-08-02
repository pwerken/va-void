<?php
declare(strict_types=1);

namespace App\Policy;

use Authorization\IdentityInterface;

use App\Model\Entity\AppEntity;
use App\Model\Entity\Player;

class PlayerPolicy
    extends AppEntityPolicy
{

    public function __construct()
    {
        parent::__construct();

        $this->showFieldAuth('password', ['user', 'infobalie']);
    }

    public function canEdit(IdentityInterface $identity, Player $player)
    {
        $this->identity = $identity;
        return $this->hasAuth(['infobalie', 'user'], $player);
    }

    public function canView(IdentityInterface $identity, Player $player)
    {
        $this->identity = $identity;
        return $this->hasAuth(['read-only', 'user'], $player);
    }

    protected function getOwner($player)
    {
        return $player->get('id');
    }
}

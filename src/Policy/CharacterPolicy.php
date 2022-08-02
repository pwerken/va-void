<?php
declare(strict_types=1);

namespace App\Policy;

use Authorization\IdentityInterface;

use App\Model\Entity\Character;

class CharacterPolicy
    extends AppEntityPolicy
{

    public function __construct()
    {
        parent::__construct();

        $this->showFieldAuth('notes', 'read-only');
        $this->showFieldAuth('referee_notes', 'read-only');
    }

    public function canView(IdentityInterface $identity, Character $char)
    {
        $this->identity = $identity;
        return $this->hasAuth(['read-only', 'user'], $char);
    }

    public function getOwner($char)
    {
        return $char->get('player_id');
    }
}

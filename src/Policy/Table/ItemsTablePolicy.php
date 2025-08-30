<?php
declare(strict_types=1);

namespace App\Policy\Table;

use App\Model\Enum\Authorization;
use App\Policy\Policy;
use Authorization\IdentityInterface as User;
use Cake\ORM\Query;

class ItemsTablePolicy extends Policy
{
    public function scopeIndex(User $identity, Query $query): void
    {
        if (!$this->hasAuth(Authorization::ReadOnly)) {
            $query->where(['Characters.plin' => $this->getPlin()]);
        }
    }

    public function scopeCharactersIndex(User $identity, Query $query): void
    {
    }
}

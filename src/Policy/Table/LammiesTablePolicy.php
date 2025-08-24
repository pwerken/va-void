<?php
declare(strict_types=1);

namespace App\Policy\Table;

use App\Policy\Policy;
use Authorization\IdentityInterface as User;
use Cake\ORM\Query;

class LammiesTablePolicy extends Policy
{
    public function scopeIndex(User $identity, Query $query): void
    {
    }
}

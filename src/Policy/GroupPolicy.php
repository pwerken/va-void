<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\AppEntity;

class GroupPolicy
    extends AppEntityPolicy
{

    public function __construct()
    {
        parent::__construct();

        $this->showFieldAuth('characters', ['read-only']);
    }
}

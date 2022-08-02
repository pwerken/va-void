<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\AppEntity;

class BeliefPolicy
    extends AppEntityPolicy
{

    public function __construct()
    {
        parent::__construct();

        $this->showFieldAuth('characters', ['read-only']);
    }
}

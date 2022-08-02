<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\AppEntity;

class AttributesItemPolicy
    extends AppEntityPolicy
{

    public function __construct()
    {
        parent::__construct();
    }
}

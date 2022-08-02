<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\AppEntity;

class PowerPolicy
    extends AppEntityPolicy
{

    public function __construct()
    {
        parent::__construct();

        $this->showFieldAuth('notes', ['read-only']);
        $this->showFieldAuth('referee_notes', ['read-only']);
    }
}

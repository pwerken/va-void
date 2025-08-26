<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\Traits\CRUDTrait;

class SkillsController extends AdminController
{
    use CRUDTrait;

    public function initialize(): void
    {
        parent::initialize();

        $this->defaultTable = 'Skills';
    }
}

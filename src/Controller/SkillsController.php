<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Traits\ViewTrait;

class SkillsController extends Controller
{
    use ViewTrait; // GET /skills/{id}

    /**
     * GET /skills
     */
    public function index(): void
    {
        $this->set('_serialize', $this->fetchTable()->find('withContain')->all());
    }
}

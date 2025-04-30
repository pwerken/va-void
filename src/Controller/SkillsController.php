<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Traits\ViewTrait;

/**
 * @property \App\Model\Table\SkillsTable $Skills
 */
class SkillsController extends Controller
{
    use ViewTrait; // GET /skills/{id}

    /**
     * GET /skills
     */
    public function index(): void
    {
        $query = $this->Skills->findWithContain();
        $this->set('_serialize', $query->all());
    }
}

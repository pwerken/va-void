<?php
declare(strict_types=1);

namespace App\Controller;

class SkillsController
    extends AppController
{
    use \App\Controller\Trait\View;     // GET /skills/{id}

    // GET /skills
    public function index(): void
    {
        $query = $this->Skills->find();
        $this->doRawIndex($query, 'Skills', '/skills/', 'id');
    }
}

<?php
declare(strict_types=1);

namespace App\Controller;

class SkillsController
    extends AppController
{

    public function index()
    {
        $query = $this->Skills->find();
#       $this->Authorization->applyScope($query);

        $this->doRawIndex($query, 'Skills', '/skills/', 'id');
    }
}

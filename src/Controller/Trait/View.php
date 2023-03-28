<?php
declare(strict_types=1);

namespace App\Controller\Trait;

trait View
{
    public function view(int $id)
    {
        $this->Read->action($id);
    }
}

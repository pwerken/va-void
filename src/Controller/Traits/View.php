<?php
declare(strict_types=1);

namespace App\Controller\Traits;

trait View
{
    public function view(int $id): void
    {
        $this->View->action($id);
    }
}

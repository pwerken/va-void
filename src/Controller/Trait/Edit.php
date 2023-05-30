<?php
declare(strict_types=1);

namespace App\Controller\Trait;

trait Edit
{
    public function edit(int $id)
    {
        $this->Update->action($id);
    }
}

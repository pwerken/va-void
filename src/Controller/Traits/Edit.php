<?php
declare(strict_types=1);

namespace App\Controller\Traits;

trait Edit
{
    public function edit(int $id)
    {
        $this->Edit->action($id);
    }
}

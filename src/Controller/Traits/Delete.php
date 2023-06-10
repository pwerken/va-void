<?php
declare(strict_types=1);

namespace App\Controller\Traits;

trait Delete
{
    public function delete(int $id)
    {
        $this->Delete->action($id);
    }
}

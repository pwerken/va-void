<?php
declare(strict_types=1);

namespace App\Controller\Traits;

/**
 * @property \App\Controller\Component\DeleteComponent $Delete
 */
trait DeleteTrait
{
    /**
     * call DeleteComponent->action($id)
     */
    public function delete(int $id): void
    {
        $this->loadComponent('Delete');
        $this->Delete->action($id);
    }
}

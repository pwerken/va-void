<?php
declare(strict_types=1);

namespace App\Controller\Traits;

/**
 * @property \App\Controller\Component\ViewComponent $View
 */
trait ViewTrait
{
    /**
     * call ViewComponent->action($id)
     */
    public function view(int $id): void
    {
        $this->loadComponent('View');
        $this->View->action($id);
    }
}

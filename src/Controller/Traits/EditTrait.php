<?php
declare(strict_types=1);

namespace App\Controller\Traits;

/**
 * @property \App\Controller\Component\EditComponent $Edit
 */
trait EditTrait
{
    /**
     * call EditComponent->action($id)
     */
    public function edit(int $id)
    {
        $this->loadComponent('Edit');
        $this->Edit->action($id);
    }
}

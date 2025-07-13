<?php
declare(strict_types=1);

namespace App\Controller\Traits;

/**
 * @property \App\Controller\Component\AddComponent $Add
 */
trait AddTrait
{
    /**
     * call AddComponent->action()
     */
    public function add(): void
    {
        $this->loadComponent('Add');
        $this->Add->action();
    }
}

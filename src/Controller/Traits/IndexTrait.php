<?php
declare(strict_types=1);

namespace App\Controller\Traits;

/**
 * @property \App\Controller\Component\IndexComponent $Index
 */
trait IndexTrait
{
    /**
     * call IndexComponent->action()
     */
    public function index(): void
    {
        $this->loadComponent('Index');
        $this->Index->action();
    }
}

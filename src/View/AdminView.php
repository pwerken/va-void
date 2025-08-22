<?php
declare(strict_types=1);

namespace App\View;

use Cake\View\View as CakeView;

/**
 * @property \App\View\Helper\AdminHistoryHelper $Helper
 */
class AdminView extends CakeView
{
    public function initialize(): void
    {
        parent::initialize();

        $this->loadHelper('Helper', ['className' => 'AdminHistory']);
    }
}

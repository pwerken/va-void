<?php
declare(strict_types=1);

namespace App\Controller\Admin;

class RoutesController extends AdminController
{
    /**
     * GET /admin/routes
     */
    public function index(): void
    {
        $this->getRequest()->allowMethod(['get']);
    }
}

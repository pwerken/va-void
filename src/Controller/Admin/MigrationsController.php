<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Migrations\Migrations;

class MigrationsController extends AdminController
{
    /**
     * GET /admin/migrations
     */
    public function index(): void
    {
        $migrations = new Migrations();
        $this->set('migrations', array_reverse($migrations->status()));
    }
}

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
        $this->getRequest()->allowMethod(['get']);

        $migrations = new Migrations();
        $rows = [];
        foreach (array_reverse($migrations->status()) as $migration) {
            $status = $migration['status'] === 'up' ? 'up' : 'down';
            $name = $migration['name'] ?: '** MISSING **';
            $id = sprintf('%14.0f', $migration['id']);

            $rows[] = [$status, $id, $name];
        }

        $this->set('migrations', $rows);
    }
}

<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Command\Traits\BackupTrait;

class BackupsController extends AdminController
{
    use BackupTrait;

    /**
     * GET /admin/backups
     */
    public function index(): void
    {
        $this->set('backups', array_reverse($this->getBackupFiles()));
    }
}

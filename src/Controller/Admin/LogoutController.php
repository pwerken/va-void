<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Cake\Http\Response;

class LogoutController extends AdminController
{
    /**
     * GET /admin/logout
     */
    public function index(): Response
    {
        $this->Authentication->logout();

        return $this->redirect('/admin');
    }
}

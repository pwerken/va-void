<?php
declare(strict_types=1);

namespace App\Policy\Controller\Admin;

use App\Model\Enum\Authorization;
use App\Policy\Controller\ControllerPolicy;

class SocialControllerPolicy extends ControllerPolicy
{
    /**
     * GET /admin/social
     * POST /admin/social
     */
    public function index(): bool
    {
        return $this->hasAuth(Authorization::Infobalie);
    }

    /**
     * GET /admin/social/all
     * POST /admin/social/all
     */
    public function all(): bool
    {
        return $this->index();
    }

    /**
     * GET /admin/social/login/$providerName
     */
    public function login(): bool
    {
        return true;
    }

    /**
     * GET /admin/social/callback
     */
    public function callback(): bool
    {
        return true;
    }
}

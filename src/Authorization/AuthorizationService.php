<?php
declare(strict_types=1);

namespace App\Authorization;

use Authorization\AuthorizationService as BaseAuthorizationService;
use Authorization\Policy\ResolverCollection;

class AuthorizationService extends BaseAuthorizationService
{
    public function __construct()
    {
        $collection = new ResolverCollection();
        $collection->add(new ControllerPolicyResolver());
        $collection->add(new EntityPolicyResolver());
        $collection->add(new TablePolicyResolver());

        parent::__construct($collection);
    }
}

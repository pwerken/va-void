<?php
declare(strict_types=1);

namespace App\Authorization;

use Authorization\AuthorizationService;
use Authorization\Policy\OrmResolver;
use Authorization\Policy\ResolverCollection;

class AppAuthorizationService
    extends AuthorizationService
{
    /**
     * Constructor
     *
     * @param \Authorization\Policy\ResolverInterface $resolver Authorization
     * policy resolver.
     */
    public function __construct()
    {
        $collection = new ResolverCollection();
        $collection->add(new ControllerPolicyResolver());
        $collection->add(new OrmResolver());

        parent::__construct($collection);
    }
}

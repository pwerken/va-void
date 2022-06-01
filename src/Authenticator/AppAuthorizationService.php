<?php
declare(strict_types=1);

namespace App\Authenticator;

use Authorization\AuthorizationService;
use Authorization\Policy\OrmResolver;

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
		parent::__construct(new OrmResolver());
	}
}

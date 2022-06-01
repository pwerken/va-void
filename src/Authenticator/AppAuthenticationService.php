<?php
declare(strict_types=1);

namespace App\Authenticator;

use Authentication\AuthenticationService;
use Authentication\Identifier\IdentifierInterface;
use Cake\Routing\Router;

class AppAuthenticationService
	extends AuthenticationService
{
    /**
     * Constructor
     *
     * @param array $config Configuration options.
     */
    public function __construct(array $config = [])
    {
		parent::setConfig($config);

		$fields = [
			IdentifierInterface::CREDENTIAL_USERNAME => 'id',
			IdentifierInterface::CREDENTIAL_PASSWORD => 'password',
		];
		$resolver = [
			'className' => 'Authentication.Orm',
			'userModel' => 'Players'
		];

		// Load the authenticators. Session should be first.
		$this->loadAuthenticator('Authentication.Session');
		$this->loadAuthenticator('Authentication.Jwt', [
#			'queryParam' => null,
			'returnPayload' => false,
		]);
		$this->loadAuthenticator('App.PutPostData', [
			'fields' => $fields,
			'returnPayload' => false,
			'loginUrl' => [
				Router::url('/admin'),
				Router::url('/auth/login'),
			],
		]);

		// Load identifiers
		$this->loadIdentifier('Authentication.JwtSubject', [
			'resolver' => $resolver
		]);
		$this->loadIdentifier('Authentication.Password', [
			'fields' => $fields,
			'resolver' => $resolver,
		]);
	}
}

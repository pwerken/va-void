<?php
declare(strict_types=1);

namespace App\Authentication;

use Authentication\AuthenticationService;
use Authentication\Authenticator\JwtAuthenticator;
use Authentication\Authenticator\SessionAuthenticator;
use Authentication\Identifier\IdentifierInterface;
use Authentication\Identifier\JwtSubjectIdentifier;
use Authentication\Identifier\PasswordIdentifier;
use Authentication\Identifier\Resolver\OrmResolver;
use Cake\Routing\Router;
use Cake\Http\ServerRequest;
Use Psr\Http\Message\ServerRequestInterface;

use App\Model\Entity\Player;

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
        $config['identityClass'] = Player::class;

        parent::setConfig($config);

        $fields = [
            IdentifierInterface::CREDENTIAL_USERNAME => 'id',
            IdentifierInterface::CREDENTIAL_PASSWORD => 'password',
        ];
        $resolver = [
            'className' => OrmResolver::class,
            'userModel' => 'Players'
        ];

        // Load the authenticators. Session should be first.
        $this->loadAuthenticator(SessionAuthenticator::class);
        $this->loadAuthenticator(JwtAuthenticator::class, [
            'returnPayload' => false,
        ]);
        $this->loadAuthenticator(PutPostDataAuthenticator::class, [
            'fields' => $fields,
            'returnPayload' => false,
            'loginUrl' => [
                Router::url('/admin'),
                Router::url('/auth/login'),
            ],
        ]);

        // Load identifiers
        $this->loadIdentifier(JwtSubjectIdentifier::class, [
            'resolver' => $resolver
        ]);
        $this->loadIdentifier(PasswordIdentifier::class, [
            'fields' => $fields,
            'resolver' => $resolver,
        ]);
    }

    /**
     * Return the URL to redirect unauthenticated users to.
     *
     * If $request is to /admin* redirect to /admin, with a return query
     * parameter.  Otherwise it returns null.
     */
    public function getUnauthenticatedRedirectUrl(ServerRequestInterface $request): ?string
    {
        if($request->getParam('controller') != 'Admin')
            return NULL;

        $this->setConfig('queryParam', 'redirect');
        $this->setConfig('unauthenticatedRedirect', Router::url('/admin'));

        return parent::getUnauthenticatedRedirectUrl($request);
    }
}

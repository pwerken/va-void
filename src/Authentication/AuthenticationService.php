<?php
declare(strict_types=1);

namespace App\Authentication;

use App\Model\Entity\Player;
use Authentication\AuthenticationService as BaseAuthenticationService;
use Authentication\Authenticator\JwtAuthenticator;
use Authentication\Authenticator\SessionAuthenticator;
use Authentication\Identifier\AbstractIdentifier;
use Authentication\Identifier\JwtSubjectIdentifier;
use Authentication\Identifier\PasswordIdentifier;
use Authentication\Identifier\Resolver\OrmResolver;
use Cake\Routing\Router;
use Psr\Http\Message\ServerRequestInterface;

class AuthenticationService extends BaseAuthenticationService
{
    public function __construct(array $config = [])
    {
        $config['identityClass'] = Player::class;

        parent::setConfig($config);

        $fields = [
            AbstractIdentifier::CREDENTIAL_USERNAME => 'id',
            AbstractIdentifier::CREDENTIAL_PASSWORD => 'password',
        ];
        $resolver = [
            'className' => OrmResolver::class,
            'userModel' => 'Players',
        ];

        // Load the authenticators. Session should be first.
        $this->loadAuthenticator(SessionAuthenticator::class, [
            'fields' => [ 'sub' => 'id' ],
            'identify' => true,
        ]);
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
            'resolver' => $resolver,
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
        if ($request->getParam('prefix') != 'Admin') {
            return null;
        }

        $this->setConfig('queryParam', 'redirect');
        $this->setConfig('unauthenticatedRedirect', Router::url('/admin'));

        return parent::getUnauthenticatedRedirectUrl($request);
    }
}

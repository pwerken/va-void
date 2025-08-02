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
    public function __construct(bool $isAdmin, array $config = [])
    {
        $fields = [
            AbstractIdentifier::CREDENTIAL_USERNAME => 'id',
            AbstractIdentifier::CREDENTIAL_PASSWORD => 'password',
        ];

        $resolver = [
            'className' => OrmResolver::class,
            'userModel' => 'Players',
        ];

        $defaults = ['identityClass' => Player::class];
        if ($isAdmin) {
            $defaults['authenticators'][SessionAuthenticator::class] = [
                'identifier' => [
                    JwtSubjectIdentifier::class => [
                        'resolver' => $resolver,
                    ],
                ],
                'fields' => [ 'sub' => 'id' ],
                'identify' => true,
            ];
        }
        $defaults['authenticators'][JwtAuthenticator::class] = [
            'identifier' => [
                JwtSubjectIdentifier::class => [
                    'resolver' => $resolver,
                ],
            ],
            'returnPayload' => false,
        ];
        $defaults['authenticators'][PutPostDataAuthenticator::class] = [
            'identifier' => [
                PasswordIdentifier::class => [
                    'fields' => $fields,
                    'resolver' => $resolver,
                ],
            ],
            'fields' => $fields,
            'returnPayload' => false,
            'loginUrl' => [
                Router::url('/admin'),
                Router::url('/auth/login'),
            ],
        ];

        parent::setConfig($config + $defaults);
    }

    /**
     * Return the URL to redirect unauthenticated users to.
     *
     * If $request is to /admin* redirect to /admin, with a return query
     * parameter.  Otherwise it returns null.
     */
    public function getUnauthenticatedRedirectUrl(ServerRequestInterface $request): ?string
    {
        $params = $request->getAttribute('params');
        if (!isset($params['prefix']) || $params['prefix'] !== 'Admin') {
            return null;
        }

        $this->setConfig('queryParam', 'redirect');
        $this->setConfig('unauthenticatedRedirect', Router::url('/admin'));

        return parent::getUnauthenticatedRedirectUrl($request);
    }
}

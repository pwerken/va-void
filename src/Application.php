<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.3.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App;

use Authentication\Middleware\AuthenticationMiddleware;
use Authorization\Middleware\AuthorizationMiddleware;
use Authorization\Middleware\RequestAuthorizationMiddleware;
use Cake\Core\Configure;
use Cake\Core\ContainerInterface;
use Cake\Datasource\FactoryLocator;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\ORM\Locator\TableLocator;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;

use App\Authentication\AppAuthenticationService;
use App\Authorization\AppAuthorizationService;
use App\Routing\Middleware\CorsMiddleware;
use App\Routing\Middleware\JsonInputMiddleware;
use App\Routing\Middleware\PlinChinMiddleware;

/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 */
class Application
    extends BaseApplication
{
    /**
     * {@inheritDoc}
     */
    public function bootstrap(): void
    {
        // Call parent to load bootstrap from files.
        parent::bootstrap();

        if (PHP_SAPI === 'cli') {
            $this->bootstrapCli();
        } else {
            FactoryLocator::add(
                'Table',
                (new TableLocator())->allowFallbackClass(false)
            );
        }

        $this->addPlugin('Authentication');
        $this->addPlugin('Authorization');
    }

    /**
     * Setup the middleware queue your application will use.
     *
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue The middleware queue to setup.
     * @return \Cake\Http\MiddlewareQueue The updated middleware queue.
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $middlewareQueue
            // Catch any exceptions in the lower layers,
            // and make an error page/response
            ->add(new ErrorHandlerMiddleware(Configure::read('Error')))

            // Handle plugin/theme assets like CakePHP normally does.
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime')
            ]))

            // Support Cross-Origin Request Sharing
            ->add(new CorsMiddleware())

            // Add routing middleware.
            // If you have a large number of routes connected, turning on routes
            // caching in production could improve performance. For that when
            // creating the middleware instance specify the cache config name by
            // using it's second constructor argument:
            ->add(new RoutingMiddleware($this, '_cake_routes_'))

            // Force PUT/POST to 'Content-Type: application/json'
            ->add(new JsonInputMiddleware())

            // Parse various types of encoded request bodies so that they are
            // available as array through $request->getData()
            // https://book.cakephp.org/4/en/controllers/middleware.html#body-parser-middleware
            ->add(new BodyParserMiddleware())

            // Convert url's :plin/:chin to :character_id
            ->add(new PlinChinMiddleware())

#            // Cross Site Request Forgery (CSRF) Protection Middleware
#            // https://book.cakephp.org/4/en/controllers/middleware.html#cross-site-request-forgery-csrf-middleware
#            ->add(new CsrfProtectionMiddleware([
#                'httponly' => true,
#            ]))

            // Add the AuthenticationMiddleware.
            // It should be after routing and body parser.
            ->add(new AuthenticationMiddleware(new AppAuthenticationService()))

            // Add the AuthenticationMiddleware.
            // It should be after routing and body parser.
            ->add(new AuthorizationMiddleware(new AppAuthorizationService()))
            ->add(new RequestAuthorizationMiddleware())
            ;

        return $middlewareQueue;
    }

    /**
     * Register application container services.
     *
     * @param \Cake\Core\ContainerInterface $container The Container to update.
     * @return void
     * @link https://book.cakephp.org/4/en/development/dependency-injection.html#dependency-injection
     */
    public function services(ContainerInterface $container): void
    {
    }

    /**
     * {@inheritDoc}
     */
    protected function bootstrapCli(): void
    {
        $this->addPlugin('Migrations');
    }
}

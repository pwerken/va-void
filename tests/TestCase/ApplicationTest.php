<?php
declare(strict_types=1);

namespace App\Test\TestCase;

use App\Application;
use App\Middleware\CharacterIdFromPlinChinMiddleware;
use App\Middleware\CharacterIdNotNullMiddleware;
use App\Middleware\CorsMiddleware;
use App\Middleware\JsonInputMiddleware;
use App\Middleware\LoginWithPlinMiddleware;
use App\Test\TestSuite\TestCase;
use Authentication\Middleware\AuthenticationMiddleware;
use Authorization\Middleware\AuthorizationMiddleware;
use Authorization\Middleware\RequestAuthorizationMiddleware;
use Cake\Core\Configure;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Cake\TestSuite\IntegrationTestTrait;

/**
 * ApplicationTest class
 */
class ApplicationTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Test bootstrap in production.
     *
     * @return void
     */
    public function testBootstrap()
    {
        Configure::write('debug', false);
        $app = new Application(dirname(__DIR__, 2) . '/config');
        $app->bootstrap();
        $plugins = $app->getPlugins();

        $this->assertTrue($plugins->has('Authentication'), 'plugins has Authentication?');
        $this->assertTrue($plugins->has('Authorization'), 'plugins has Authorization?');
        $this->assertTrue($plugins->has('Migrations'), 'plugins has Migrations?');
    }

    /**
     * testMiddleware
     *
     * @return void
     */
    public function testMiddleware()
    {
        $app = new Application(dirname(__DIR__, 2) . '/config');
        $middleware = new MiddlewareQueue();

        $middleware = $app->middleware($middleware);

        $this->assertInstanceOf(CorsMiddleware::class, $middleware->current());
        $middleware->seek(1);
        $this->assertInstanceOf(ErrorHandlerMiddleware::class, $middleware->current());
        $middleware->seek(2);
        $this->assertInstanceOf(AssetMiddleware::class, $middleware->current());
        $middleware->seek(3);
        $this->assertInstanceOf(RoutingMiddleware::class, $middleware->current());
        $middleware->seek(4);
        $this->assertInstanceOf(CharacterIdFromPlinChinMiddleware::class, $middleware->current());
        $middleware->seek(5);
        $this->assertInstanceOf(JsonInputMiddleware::class, $middleware->current());
        $middleware->seek(6);
        $this->assertInstanceOf(BodyParserMiddleware::class, $middleware->current());
        $middleware->seek(7);
        $this->assertInstanceOf(LoginWithPlinMiddleware::class, $middleware->current());
        $middleware->seek(8);
        $this->assertInstanceOf(AuthenticationMiddleware::class, $middleware->current());
        $middleware->seek(9);
        $this->assertInstanceOf(AuthorizationMiddleware::class, $middleware->current());
        $middleware->seek(10);
        $this->assertInstanceOf(RequestAuthorizationMiddleware::class, $middleware->current());
        $middleware->seek(11);
        $this->assertInstanceOf(CharacterIdNotNullMiddleware::class, $middleware->current());
    }
}

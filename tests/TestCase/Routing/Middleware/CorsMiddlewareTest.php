<?php
namespace App\Test\TestCase\Routing\Middleware;

use App\Routing\Middleware\CorsMiddleware;
use Cake\Http\Response;
use Cake\Http\ServerRequestFactory;
use Cake\TestSuite\TestCase;

/**
 * Test for CorsMiddleware
 */
class CorsMiddlewareTest extends TestCase
{
	/**
	 * test with a simple request
	 *
	 * @return void
	 */
	public function testRegularRequest()
	{
		$request = ServerRequestFactory::fromGlobals([
			'REQUEST_URI' => '/',
		]);
		$response = new Response();
		$next = function ($req, $res) {
			return $res->withHeader('X-Test', 'ok');
		};
		$middleware = new CorsMiddleware();
		$res = $middleware($request, $response, $next);

		$this->assertNotNull($res);
		$this->assertEquals(200, $res->getStatusCode());
		$this->assertEquals('ok', $res->getHeaderLine('X-Test'));
		$this->assertEmpty($res->getHeaderLine('Access-Control-Allow-Origin'));
	}

	/**
	 * test that a OPTIONS request is returned immediately
	 *
	 * @return void
	 */
	public function testHttpOptionsRequest()
	{
		$request = ServerRequestFactory::fromGlobals([
			'REQUEST_URI' => '/',
			'REQUEST_METHOD' => 'OPTIONS'
		]);
		$response = new Response();
		$next = function ($req, $res) {
			return $res->withHeader('X-Test', 'nok');
		};
		$middleware = new CorsMiddleware();
		$res = $middleware($request, $response, $next);

		$this->assertNotNull($res);
		$this->assertEquals(200, $res->getStatusCode());
		$this->assertNotEquals('nok', $res->getHeaderLine('X-Test'));
		$this->assertEmpty($res->getHeaderLine('Access-Control-Allow-Origin'));
	}

	/**
	 * test that passses an invalid domain in the origin header
	 *
	 * @return void
	 */
	public function testRequestWithInvalidOriginHeader()
	{
		$request = ServerRequestFactory::fromGlobals([
			'REQUEST_URI' => '/',
			'Origin' => 'http://example.com'
		]);
		$response = new Response();
		$next = function ($req, $res) {
			return $res->withHeader('X-Test', 'ok');
		};
		$middleware = new CorsMiddleware(['allowOrigin' => ['localhost']]);
		$res = $middleware($request, $response, $next);

		$this->assertNotNull($res);
		$this->assertEquals(200, $res->getStatusCode());
		$this->assertEquals('ok', $res->getHeaderLine('X-Test'));
		$this->assertEmpty($res->getHeaderLine('Access-Control-Allow-Origin'));
	}

	/**
	 * test that a invalid domain in the origin header
	 *
	 * @return void
	 */
	public function testRequestWithValidOriginHeader()
	{
		$request = ServerRequestFactory::fromGlobals([
			'REQUEST_URI' => '/',
			'Origin' => 'http://localhost'
		]);
		$response = new Response();
		$next = function ($req, $res) {
			return $res->withHeader('X-Test', 'ok');
		};
		$middleware = new CorsMiddleware(['allowOrigin' => ['localhost']]);
		$res = $middleware($request, $response, $next);

		$this->assertNotNull($res);
		$this->assertEquals(200, $res->getStatusCode());
		$this->assertEquals('ok', $res->getHeaderLine('X-Test'));
		$this->assertEmpty($res->getHeaderLine('Access-Control-Allow-Origin'));

		$middleware = new CorsMiddleware(['allowOrigin' => ['*']]);
		$res = $middleware($request, $response, $next);

		$this->assertNotNull($res);
		$this->assertEquals(200, $res->getStatusCode());
		$this->assertEquals('ok', $res->getHeaderLine('X-Test'));
		$this->assertEmpty($res->getHeaderLine('Access-Control-Allow-Origin'));
	}
}

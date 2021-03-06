<?php
namespace App\Routing\Middleware;

use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PlinChinMiddleware
{

	public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
	{
		$controller = $request->getParam('controller');
		$action = $request->getParam('action');

		$hasPlinChin = strcmp($controller, 'Characters') == 0;
		$hasPlinChin |= strcmp(substr($action, 0, 10), 'characters') == 0;

		$pass = $request->getParam('pass');
		if($hasPlinChin && count($pass) >= 2) {
			$table = TableRegistry::get('Characters');
			$char = $table->plinChin($pass[0], $pass[1])->id;

			array_shift($pass);
			$pass[0] = $char;

			$request = $request->withParam('character_id', $char);
			$request = $request->withParam('pass', $pass);
		}

		return $next($request, $response);
	}

}

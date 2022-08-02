<?php
namespace App\Routing\Middleware;

use Cake\ORM\TableRegistry;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PlinChinMiddleware implements MiddlewareInterface
{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
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

        return $handler->handle($request);
    }
}

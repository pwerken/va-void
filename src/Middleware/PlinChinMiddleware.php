<?php
declare(strict_types=1);

namespace App\Middleware;

use Cake\ORM\TableRegistry;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class PlinChinMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $controller = $request->getParam('controller');
        $action = $request->getParam('action');

        $hasPlinChin = strcmp($controller, 'Characters') == 0;
        $hasPlinChin |= strcmp(substr($action, 0, 10), 'characters') == 0;

        $pass = $request->getParam('pass');
        if ($hasPlinChin && count($pass) >= 2) {
            $table = TableRegistry::getTableLocator()->get('Characters');
            $char = $table->plinChin($pass[0], $pass[1])->id;
            $request = $request->withParam('character_id', $char);

            array_shift($pass);
            $pass[0] = $char;
        }
        foreach ($pass as $key => $value) {
            if (is_numeric($value)) {
                $pass[$key] = (int)$value;
            }
        }

        $request = $request->withParam('pass', $pass);

        return $handler->handle($request);
    }
}

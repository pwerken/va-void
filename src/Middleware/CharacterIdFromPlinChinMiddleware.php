<?php
declare(strict_types=1);

namespace App\Middleware;

use Cake\ORM\TableRegistry;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class CharacterIdFromPlinChinMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $params = $request->getAttribute('params');

        $hasPlinChin = ($params['controller'] === 'Characters');
        $hasPlinChin |= (substr($params['action'], 0, 10) === 'characters');

        $pass = $params['pass'];
        if ($hasPlinChin && count($pass) >= 2) {
            $table = TableRegistry::getTableLocator()->get('Characters');
            $char = $table->findByPlinAndChin($pass[0], $pass[1])->first()?->id;
            $params['character_id'] = $char;

            array_shift($pass);
            $pass[0] = $char;
        }
        foreach ($pass as $key => $value) {
            if (is_numeric($value)) {
                $pass[$key] = (int)$value;
            }
        }
        $params['pass'] = $pass;

        $request = $request->withAttribute('PlinChin', $hasPlinChin);
        $request = $request->withAttribute('params', $params);

        return $handler->handle($request);
    }
}

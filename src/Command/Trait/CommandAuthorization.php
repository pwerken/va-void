<?php
declare(strict_types=1);

namespace App\Command\Trait;

use Cake\Http\ServerRequest;
use Cake\Routing\Router;

use App\Model\Entity\Player;

trait CommandAuthorization
{
    public function initialize(): void
    {
        $cli = new Player();
        $cli->set('id', -2);
        $cli->set('role', 'Super');

        $request = (new ServerRequest())->withAttribute('identity', $cli);
        Router::setRequest($request);
    }
}

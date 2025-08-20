<?php
declare(strict_types=1);

namespace App\Command\Traits;

use App\Model\Entity\Player;
use App\Model\Enum\PlayerRole;
use Cake\Http\ServerRequest;
use Cake\Routing\Router;

trait CommandAuthorizationTrait
{
    public function initialize(): void
    {
        $cli = new Player();
        $cli->set('id', -2);
        $cli->set('role', PlayerRole::Super);

        $request = (new ServerRequest())->withAttribute('identity', $cli);
        Router::setRequest($request);
    }
}

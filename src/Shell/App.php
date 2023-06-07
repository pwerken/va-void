<?php
namespace App\Shell;

use Cake\Console\Shell;
use Cake\Http\ServerRequest;
use Cake\Routing\Router;

use App\Model\Entity\Player;

class App
    extends Shell
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

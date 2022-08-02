<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Exception\NotFoundException;

class PlayersController
    extends AppController
{

    public function index()
    {
        $query = $this->Players->find()
                    ->select([], true)
                    ->select('Players.id')
                    ->select('Players.first_name')
                    ->select('Players.insertion')
                    ->select('Players.last_name');

        $this->Authorization->applyScope($query);

        $content = [];
        foreach($this->doRawQuery($query) as $row) {
            $name = $row[1];
            if(!empty($row[2]))
                $name .= ' '.$row[2];
            $name .= ' '.$row[3];

            $content[] =
                [ 'class' => 'Player'
                , 'url' => '/players/'.$row[0]
                , 'plin' => (int)$row[0]
                , 'full_name' => $name
                ];
        }
        $this->set('_serialize',
            [ 'class' => 'List'
            , 'url' => rtrim($this->request->getPath(), '/')
            , 'list' => $content
            ]);
    }

    public function view(int $plin)
    {
        $player = $this->Players->findWithContainById($plin)->first();
        if (is_null($player)) {
            throw new NotFoundException();
        }
        $this->Authorization->authorize($player);

        $this->set('_serialize', $player);
    }
}

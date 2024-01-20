<?php
declare(strict_types=1);

namespace App\Controller;

class PlayersController
    extends AppController
{
    use \App\Controller\Traits\Add;      // PUT /players
    use \App\Controller\Traits\Edit;     // PUT /players/{plin}
    use \App\Controller\Traits\Delete;   // DELETE /players/{plin}

    // GET /players
    public function index(): void
    {
        $query = $this->Players->find()
                    ->select([], true)
                    ->select('Players.id')
                    ->select('Players.first_name')
                    ->select('Players.insertion')
                    ->select('Players.last_name')
                    ->select('Players.modified');
        $this->Authorization->applyScope($query);

        $content = [];
        $modified_max = null;
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

            $modified_max = max($modified_max, $row[4]);
        }

        $this->checkModified($modified_max);
        $this->set('_serialize',
            [ 'class' => 'List'
            , 'url' => rtrim($this->request->getPath(), '/')
            , 'list' => $content
            ]);
    }

    // GET /players/{plin}
    public function view($id): void
    {
        $this->View->action($id);

        $player = $this->viewBuilder()->getVar('_serialize');
        if(!$player) {
            return;
        }

        $modified_max = $player->modified;
        foreach($player->socials as $obj) {
            $modified_max = max($modified_max, $obj->modified);
        }
        foreach($player->characters as $obj) {
            $modified_max = max($modified_max, $obj->modified);
        }

        $this->checkModified($modified_max);
    }

}

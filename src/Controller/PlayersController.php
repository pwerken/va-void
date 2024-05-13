<?php
declare(strict_types=1);

namespace App\Controller;

class PlayersController
    extends AppController
{
    use \App\Controller\Traits\Add;      // PUT /players
    use \App\Controller\Traits\View;     // GET /players/{plin}
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
}

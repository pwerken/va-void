<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Traits\AddTrait;
use App\Controller\Traits\DeleteTrait;
use App\Controller\Traits\EditTrait;
use App\Controller\Traits\ViewTrait;

class PlayersController extends Controller
{
    use AddTrait; // PUT /players
    use DeleteTrait; // DELETE /players/{plin}
    use EditTrait; // PUT /players/{plin}
    use ViewTrait; // GET /players/{plin}

    /**
     * GET /players
     */
    public function index(): void
    {
        $query = $this->fetchTable()->find()
                    ->select([], true)
                    ->select('Players.id')
                    ->select('Players.first_name')
                    ->select('Players.insertion')
                    ->select('Players.last_name');
        $this->Authorization->applyScope($query);

        $content = [];
        foreach ($this->doRawQuery($query) as $row) {
            $name = $row[1];
            if (!empty($row[2])) {
                $name .= ' ' . $row[2];
            }
            $name .= ' ' . $row[3];

            $content[] = [
                'class' => 'Player',
                'url' => '/players/' . $row[0],
                'plin' => (int)$row[0],
                'full_name' => $name,
            ];
        }

        $this->set('_serialize', [
            'class' => 'List',
            'url' => rtrim($this->request->getPath(), '/'),
            'list' => $content,
        ]);
    }
}

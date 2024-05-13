<?php
declare(strict_types=1);

namespace App\Controller;

class ConditionsController
    extends AppController
{
    use \App\Controller\Traits\Add;      // PUT /conditions
    use \App\Controller\Traits\View;     // GET /conditions/{coin}
    use \App\Controller\Traits\Edit;     // PUT /conditions/{coin}
    use \App\Controller\Traits\Delete;   // DELETE /conditions/{coin}

    // GET /conditions
    public function index(): void
    {
        $query = $this->Conditions->find()
                    ->select([], true)
                    ->select('Conditions.id')
                    ->select('Conditions.name')
                    ->select('Conditions.deprecated');
        $this->Authorization->applyScope($query);

        $content = [];
        foreach($this->doRawQuery($query) as $row) {
            $content[] = [ 'class' => 'Condition'
                , 'url' => '/conditions/'.$row[0]
                , 'coin' => (int)$row[0]
                , 'name' => $row[1]
                , 'deprecated' => (boolean)$row[2]
                ];
        }

        $this->set('_serialize',
            [ 'class' => 'List'
            , 'url' => rtrim($this->request->getPath(), '/')
            , 'list' => $content
            ]);
    }

    // POST /conditions/{coin}/print
    public function queue(int $coin): void
    {
        $this->QueueLammy->action($coin);
    }
}

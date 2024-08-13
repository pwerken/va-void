<?php
declare(strict_types=1);

namespace App\Controller;

class PowersController
    extends AppController
{
    use \App\Controller\Traits\Add;      // PUT /powers
    use \App\Controller\Traits\View;     // GET /powers/{poin}
    use \App\Controller\Traits\Edit;     // PUT /powers/{poin}

    // GET /powers
    public function index(): void
    {
        $query = $this->Powers->find()
                    ->select([], true)
                    ->select('Powers.id')
                    ->select('Powers.name')
                    ->select('Powers.deprecated');
        $this->Authorization->applyScope($query);

        $content = [];
        foreach($this->doRawQuery($query) as $row) {
            $content[] = [ 'class' => 'Power'
                , 'url' => '/powers/'.$row[0]
                , 'poin' => (int)$row[0]
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

    // POST /powers/{poin}/print
    public function queue(int $poin): void
    {
        if(array_key_exists('all', $this->getRequest()->getData())) {
            $power = $this->fetchModel()->getWithContain($poin);
            $table = $this->fetchModel('Lammies');
            foreach($power->characters as $character) {
                $lammy = $table->newEmptyEntity();
                $lammy->set('target', $character);
                $table->saveOrFail($lammy);
            }
            return;
        }

        $this->QueueLammy->action($poin);
    }
}

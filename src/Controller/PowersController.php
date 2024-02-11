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
                    ->select('Powers.modified');
        $this->Authorization->applyScope($query);

        $this->doRawIndex($query, 'Powers', '/powers/', 'poin');
    }

    // POST /powers/{poin}/print
    public function queue(int $poin): void
    {
        $this->QueueLammy->action($poin);
    }
}

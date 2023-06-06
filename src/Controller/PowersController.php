<?php
declare(strict_types=1);

namespace App\Controller;

class PowersController
    extends AppController
{
    use \App\Controller\Trait\Add;      // PUT /powers
    use \App\Controller\Trait\View;     // GET /powers/{poin}
    use \App\Controller\Trait\Edit;     // PUT /powers/{poin}

    // GET /powers
    public function index(): void
    {
        $query = $this->Powers->find()
                    ->select(['Powers.id', 'Powers.name'], true);
        $this->Authorization->applyScope($query);

        $this->doRawIndex($query, 'Powers', '/powers/', 'poin');
    }

    // POST /powers/{poin}/print
    public function queue(int $poin): void
    {
        $this->QueueLammy->action($poin);
    }
}

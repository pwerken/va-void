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
                    ->select(['Conditions.id', 'Conditions.name', 'Conditions.modified'], true);
        $this->Authorization->applyScope($query);

        $this->doRawIndex($query, 'Condition', '/conditions/', 'coin');
    }

    // POST /conditions/{coin}/print
    public function queue(int $coin): void
    {
        $this->QueueLammy->action($coin);
    }
}

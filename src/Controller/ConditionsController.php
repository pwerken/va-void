<?php
declare(strict_types=1);

namespace App\Controller;

class ConditionsController
    extends AppController
{
    use \App\Controller\Trait\Add;      // PUT /conditions
    use \App\Controller\Trait\View;     // GET /conditions/{coin}
    use \App\Controller\Trait\Edit;     // PUT /conditions/{coin}
    use \App\Controller\Trait\Delete;   // DELETE /conditions/{coin}

    // GET /conditions
    public function index(): void
    {
        $query = $this->Conditions->find()
                    ->select(['Conditions.id', 'Conditions.name'], true);
        $this->Authorization->applyScope($query);

        $this->doRawIndex($query, 'Condition', '/conditions/', 'coin');
    }

    // POST /conditions/{coin}/print
    public function queue(int $coin): void
    {
        $this->QueueLammy->action($coin);
    }
}

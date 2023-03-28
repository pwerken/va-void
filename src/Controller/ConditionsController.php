<?php
declare(strict_types=1);

namespace App\Controller;

class ConditionsController
    extends AppController
{
    use \App\Controller\Trait\View;

    public function index()
    {
        $query = $this->Conditions->find()
                    ->select(['Conditions.id', 'Conditions.name'], true);
        $this->Authorization->applyScope($query);

        $this->doRawIndex($query, 'Condition', '/conditions/', 'coin');
    }
}

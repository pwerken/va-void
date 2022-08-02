<?php
declare(strict_types=1);

namespace App\Controller;

class ConditionsController
    extends AppController
{

    public function index()
    {
        $query = $this->Conditions->find()
                    ->select(['Conditions.id', 'Conditions.name'], true);
#       $this->Authorization->applyScope($query);

        $this->doRawIndex($query, 'Condition', '/conditions/', 'coin');
    }

    public function view($coin)
    {
        $condition = $this->Conditions->findWithContainById($coin)->first();
#        $this->Authorization->authorize($condition);

        $this->set('_serialize', $condition);
    }
}

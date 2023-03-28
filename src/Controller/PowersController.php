<?php
declare(strict_types=1);

namespace App\Controller;

class PowersController
    extends AppController
{
    use \App\Controller\Trait\View;

    public function index()
    {
        $query = $this->Powers->find()
                    ->select(['Powers.id', 'Powers.name'], true);
        $this->Authorization->applyScope($query);

        $this->doRawIndex($query, 'Powers', '/powers/', 'poin');
    }
}

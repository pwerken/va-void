<?php
declare(strict_types=1);

namespace App\Controller;

class EventsController
    extends AppController
{

    public function index()
    {
        $query = $this->Events->find();
#       $this->Authorization->applyScope($query);

        $this->doRawIndex($query, 'Events', '/events/', 'id');
    }
}

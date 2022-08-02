<?php
declare(strict_types=1);

namespace App\Controller;

class SpellsController
    extends AppController
{

    public function index()
    {
        $query = $this->Spells->find();
#       $this->Authorization->applyScope($query);

        $this->doRawIndex($query, 'Spells', '/spells/', 'id');
    }

    public function view($spell_id)
    {
        $spell = $this->Spells->findWithContainById($spell_id)->first();
#        $this->Authorization->authorize($spell);

        $this->set('_serialize', $spell);
    }
}

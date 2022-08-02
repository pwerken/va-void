<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Exception\NotFoundException;
use Cake\Utility\Inflector;

class CharactersController
    extends AppController
{

    public function index()
    {
        $query = $this->Characters->find()
                    ->select([], true)
                    ->select('Characters.player_id')
                    ->select('Characters.chin')
                    ->select('Characters.name')
                    ->select('Characters.status');

#       $this->Authorization->applyScope($query);

        if(isset($this->parent)) {
            $a = Inflector::camelize($this->parent->getSource());
            $key = $this->Characters->getAssociation($a)->getForeignKey();
            $value = $this->parent->id;

            $query = $query->andWhere(["Characters.$key" => $value]);
            $this->set('parent', $this->parent);
        }

        $content = [];
        foreach($this->doRawQuery($query) as $row) {
            $content[] =
                [ 'class' => 'Character'
                , 'url' => '/characters/'.$row[0].'/'.$row[1]
                , 'plin' => (int)$row[0]
                , 'chin' => (int)$row[1]
                , 'name' => $row[2]
                , 'status' => $row[3]
                ];
        }
        $this->set('_serialize',
            [ 'class' => 'List'
            , 'url' => rtrim($this->request->getPath(), '/')
            , 'list' => $content
            ]);
    }

    public function view(int $id)
    {
        $character = $this->Characters->findWithContainById($id)->first();

        $this->Authorization->authorize($character);
        $this->set('_serialize', $character);
    }

    public function believesIndex(int $belief_id)
    {
        $this->parent = $this->loadModel('Believes')->get($belief_id);
        if (is_null($this->parent)) {
            throw new NotFoundException();
        }
#        $this->Authorization->authorize($this->parent, 'view');
        return $this->index();
    }

    public function factionsIndex(int $faction_id)
    {
        $this->parent = $this->loadModel('Factions')->get($faction_id);
        if (is_null($this->parent)) {
            throw new NotFoundException();
        }
#        $this->Authorization->authorize($this->parent , 'view');
        return $this->index();
    }

    public function groupsIndex(int $group_id)
    {
        $this->parent = $this->loadModel('Groups')->get($group_id);
        if (is_null($this->parent)) {
            throw new NotFoundException();
        }
#        $this->Authorization->authorize($this->parent, 'view');
        return $this->index();
    }

    public function playersIndex(int $plin)
    {
        $this->parent = $this->loadModel('Players')->get($plin);
        $this->Authorization->authorize($this->parent, 'view');
        return $this->index();
    }

    public function worldsIndex(int $world_id)
    {
        $this->parent = $this->loadModel('Worlds')->get($world_id);
        if (is_null($this->parent)) {
            throw new NotFoundException();
        }
#        $this->Authorization->authorize($this->parent, 'view');
        return $this->index();
    }
}

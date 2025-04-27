<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Utility\Inflector;

class ItemsController
    extends AppController
{
    use \App\Controller\Traits\View;     // GET /items/{itin}
    use \App\Controller\Traits\Delete;   // DELETE /items/{itin}

    // GET /items
    public function index(): void
    {
        $query = $this->Items->find()
                    ->select([], true)
                    ->select('Items.id')
                    ->select('Items.name')
                    ->select('Items.expiry')
                    ->select('Items.deprecated')
                    ->select('Characters.player_id')
                    ->select('Characters.chin')
                    ->select('Characters.name')
                    ->select('Characters.status')
                    ->leftJoin(['Characters' => 'characters'],
                            ['Characters.id = Items.character_id']);
        $this->Authorization->applyScope($query);

        $hasParent = isset($this->parent);
        if($hasParent) {
            $a = Inflector::camelize($this->parent->getSource());
            $key = $this->Items->getAssociation($a)->getForeignKey();
            $value = $this->parent->id;

            $query = $query->andWhere(["Items.$key" => $value]);
            $this->set('parent', $this->parent);
        }

        $content = [];
        foreach($this->doRawQuery($query) as $row) {
            $char = NULL;
            if(!is_null($row[4]) && !$hasParent) {
                $char = [ 'class' => 'Character'
                        , 'url' => '/characters/'.$row[5].'/'.$row[6]
                        , 'plin' => (int)$row[4]
                        , 'chin' => (int)$row[5]
                        , 'name' => $row[6]
                        , 'status' => $row[7]
                        ];
            }
            $contentEntry = [ 'class' => 'Item'
                , 'url' => '/items/'.$row[0]
                , 'itin' => (int)$row[0]
                , 'name' => $row[1]
                , 'expiry' => $row[2]
                , 'character' => $char
                , 'deprecated' => (boolean)$row[3]
                ];
            if($hasParent) {
                unset($contentEntry['character']);
            }
            $content[] = $contentEntry;
        }

        $this->set('_serialize',
            [ 'class' => 'List'
            , 'url' => rtrim($this->request->getPath(), '/')
            , 'list' => $content
            ]);
    }

    // PUT /items
    public function add(): void
    {
        $this->setCharacterId();
        $this->Add->action();
    }

    // PUT /items/{itin}
    public function edit(int $itin): void
    {
        $this->setCharacterId();
        $this->Edit->action($itin);
    }

    // POST /items/{itin}/print
    public function queue(int $itin): void
    {
        $this->QueueLammy->action($itin);
    }

    // GET /characters/{plin}/{chin}/items
    public function charactersIndex(int $char_id): void
    {
        $this->parent = $this->fetchTable('Characters')->get($char_id);
        $this->Authorization->authorize($this->parent, 'view');

        $this->index();
    }

    protected function setCharacterId()
    {
        $plin = $this->request->getData('plin');
        $chin = $this->request->getData('chin');
        $this->request = $this->request->withoutData('plin');
        $this->request = $this->request->withoutData('chin');

        if($plin || $chin) {
            $characters = $this->fetchTable('Characters');
            $char = $characters->findByPlayerIdAndChin($plin, $chin)->first();
            $char_id = $char ? $char->id : -1;
            $this->request = $this->request->withData('character_id', $char_id);
        } else {
            $this->request = $this->request->withData('character_id', null);
        }
    }
}

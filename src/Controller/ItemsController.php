<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Utility\Inflector;

class ItemsController
    extends AppController
{

    public function index()
    {
        $query = $this->Items->find()
                    ->select([], true)
                    ->select('Items.id')
                    ->select('Items.name')
                    ->select('Items.expiry')
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
            if(!is_null($row[3]) && !$hasParent) {
                $char = [ 'class' => 'Character'
                        , 'url' => '/characters/'.$row[3].'/'.$row[4]
                        , 'plin' => (int)$row[3]
                        , 'chin' => (int)$row[4]
                        , 'name' => $row[5]
                        , 'status' => $row[6]
                        ];
            }
            $contentEntry = [ 'class' => 'Item'
                , 'url' => '/items/'.$row[0]
                , 'itin' => (int)$row[0]
                , 'name' => $row[1]
                , 'expiry' => $row[2]
                , 'character' => $char
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

    public function charactersIndex(int $id)
    {
        $this->parent = $this->loadModel('characters')->get($id);
        $this->Authorization->authorize($this->parent, 'view');
        return $this->index();
    }
}

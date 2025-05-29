<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Traits\EditTrait;
use App\Controller\Traits\ViewTrait;
use Cake\Utility\Inflector;

/**
 * @property \App\Controller\Component\AddComponent $Add
 * @property \App\Model\Table\CharactersTable $Characters;
 */
class CharactersController extends Controller
{
    use ViewTrait; // GET /characters/{plin}/{chin}
    use EditTrait; // PUT /characters/{plin}/{chin}

    /**
     * GET /characters
     */
    public function index(): void
    {
        $query = $this->Characters->find()
                    ->select([], true)
                    ->select('Characters.player_id')
                    ->select('Characters.chin')
                    ->select('Characters.name')
                    ->select('Characters.status');
        $this->Authorization->applyScope($query);

        if (isset($this->parent)) {
            $this->Authorization->authorize($this->parent, 'charactersIndex');

            $a = Inflector::camelize($this->parent->getSource());
            $key = $this->Characters->getAssociation($a)->getForeignKey();
            $value = $this->parent->id;

            $query = $query->andWhere(["Characters.$key" => $value]);
            $this->set('parent', $this->parent);
        }

        $content = [];
        foreach ($this->doRawQuery($query) as $row) {
            $content[] = [
                'class' => 'Character',
                'url' => '/characters/' . $row[0] . '/' . $row[1],
                'plin' => (int)$row[0],
                'chin' => (int)$row[1],
                'name' => $row[2],
                'status' => $row[3],
            ];
        }

        $this->set('_serialize', [
            'class' => 'List',
            'url' => rtrim($this->request->getPath(), '/'),
            'list' => $content,
        ]);
    }

    /**
     * PUT /players/{plin}/characters
     */
    public function add(int $plin): void
    {
        $request = $this->getRequest();
        $request = $request->withData('plin', $plin);
        $this->setRequest($request);

        $this->loadComponent('Add');
        $this->Add->action();
    }

    /**
     * POST /characters/{plin}/{chin}/print
     */
    public function queue(int $char_id): void
    {
        $char = $this->Characters->getWithContain($char_id);

        $table = $this->fetchTable('Lammies');

        $lammy = $table->newEmptyEntity();
        $lammy->set('target', $char);
        $table->saveOrFail($lammy);
        $count = 1;

        if ((string)$this->getRequest()->getBody() === 'all') {
            foreach ($char->powers as $power) {
                $lammy = $table->newEmptyEntity();
                $lammy->set('target', $power);
                $table->saveOrFail($lammy);
                $count++;
            }

            foreach ($char->conditions as $condition) {
                $lammy = $table->newEmptyEntity();
                $lammy->set('target', $condition);
                $table->saveOrFail($lammy);
                $count++;
            }

            foreach ($char->items as $item) {
                $lammy = $table->newEmptyEntity();
                $lammy->set('target', $item);
                $table->saveOrFail($lammy);
                $count++;
            }
        }

        $this->set('_serialize', $count);
    }

    /**
     * GET /factions/{id}/characters
     */
    public function factionsIndex(int $faction_id): void
    {
        $this->parent = $this->fetchTable('Factions')->get($faction_id);
        $this->index();
    }

    /**
     * GET /players/{plin}/characters
     */
    public function playersIndex(int $plin): void
    {
        $this->parent = $this->fetchTable('Players')->get($plin);
        $this->index();
    }
}

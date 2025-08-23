<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Traits\DeleteTrait;
use App\Controller\Traits\EditTrait;
use App\Controller\Traits\ViewTrait;

/**
 * @property \App\Controller\Component\AddComponent $Add
 * @property \App\Controller\Component\LammyComponent $Lammy
 */
class CharactersController extends Controller
{
    use DeleteTrait; // DELETE /characters/{plin}/{chin}
    use EditTrait; // PUT /characters/{plin}/{chin}
    use ViewTrait; // GET /characters/{plin}/{chin}

    /**
     * GET /characters
     */
    public function index(): void
    {
        $query = $this->fetchTable()->find()
                    ->select([], true)
                    ->select('Characters.plin')
                    ->select('Characters.chin')
                    ->select('Characters.name')
                    ->select('Characters.status');
        $this->Authorization->applyScope($query);

        if (isset($this->parent)) {
            //FIXME missing 'parent' in output
            $this->Authorization->authorize($this->parent, 'charactersIndex');

            $assoc = $this->fetchTable()->getAssociation($this->parent->getSource());
            $key = $assoc->getForeignKey();
            $value = $this->parent->get($assoc->getBindingKey());

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
     * GET /characters/{plin}/{chin}/print
     * GET /characters/{plin}/{chin}/print?all&double
     */
    public function pdf(int $char_id): void
    {
        $all = !is_null($this->getRequest()->getQuery('all'));
        $double = !is_null($this->getRequest()->getQuery('double'));
        $lammies = $this->objectsForLammies($char_id, $all, false);
        $this->Lammy->outputPdf($lammies, $double);
    }

    /**
     * POST /characters/{plin}/{chin}/print
     */
    public function queue(int $char_id): void
    {
        $all = (string)$this->getRequest()->getBody() === 'all';
        $lammies = $this->objectsForLammies($char_id, $all, true);
        $this->Lammy->queueLammies($lammies);
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

    /**
     * Helper for pdf() and queue() methods.
     */
    protected function objectsForLammies(int $char_id, bool $all, bool $items): array
    {
        $this->loadComponent('Lammy');

        $char = $this->fetchTable()->get($char_id, 'withContain');
        $objs = [$this->Lammy->createLammy($char)];

        if ($all) {
            if ($char->get('teacher')) {
                $t = $char->get('teacher');
                $t->student = $char;
                $objs[] = $this->Lammy->createLammy($t);
            }
            foreach ($char->get('powers') as $power) {
                $c = $power->_joinData;
                $c->character = $char;
                $c->power = $power;
                $objs[] = $this->Lammy->createLammy($c);
            }
            foreach ($char->get('conditions') as $condition) {
                $c = $condition->_joinData;
                $c->character = $char;
                $c->condition = $condition;
                $objs[] = $this->Lammy->createLammy($c);
            }
            if ($items) {
                foreach ($char->get('items') as $item) {
                    $objs[] = $this->Lammy->createLammy($item);
                }
            }
        }

        return $objs;
    }
}

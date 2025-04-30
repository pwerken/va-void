<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * @property \App\Controller\Component\AddComponent $Add
 * @property \App\Controller\Component\DeleteComponent $Delete
 * @property \App\Controller\Component\EditComponent $Edit
 * @property \App\Controller\Component\IndexRelationComponent $IndexRelation
 * @property \App\Controller\Component\QueueLammyComponent $QueueLammy
 * @property \App\Controller\Component\ViewComponent $View
 * @property \App\Model\Table\CharactersConditionsTable $CharactersConditions;
 */
class CharactersConditionsController extends Controller
{
    /**
     * GET /characters/{plin}/{chin}/conditions
     */
    public function charactersIndex(int $char_id): void
    {
        $parent = $this->fetchTable('Characters')->get($char_id);

        $query = $this->CharactersConditions->findWithContain();
        $query->andWhere(['CharactersConditions.character_id' => $char_id]);

        $this->loadComponent('IndexRelation');
        $this->IndexRelation->action($parent, $query);
    }

    /**
     * PUT /characters/{plin}/{chin}/conditions
     */
    public function charactersAdd(int $char_id): void
    {
        $request = $this->getRequest();
        $request = $request->withData('character_id', $char_id);
        $this->setRequest($request);

        $this->loadComponent('Add');
        $this->Add->action();
    }

    /**
     * GET /characters/{plin}/{chin}/conditions/{coin}
     */
    public function charactersView(int $char_id, int $coin): void
    {
        $parent = $this->fetchTable('Characters')->get($char_id);
        $this->Authorization->authorize($parent, 'view');

        $this->loadComponent('View');
        $this->View->action([$char_id, $coin], false);
    }

    /**
     * PUT /characters/{plin}/{chin}/conditions/{coin}
     */
    public function charactersEdit(int $char_id, int $coin): void
    {
        $this->loadComponent('Edit');
        $this->Edit->action([$char_id, $coin]);
    }

    /**
     * DELETE /characters/{plin}/{chin}/conditions/{coin}
     */
    public function charactersDelete(int $char_id, int $coin): void
    {
        $this->loadComponent('Delete');
        $this->Delete->action([$char_id, $coin]);
    }

    /**
     * POST /characters/{plin}/{chin}/conditions/{coin}/print
     */
    public function charactersQueue(int $char_id, int $coin): void
    {
        $this->loadComponent('QueueLammy');
        $this->QueueLammy->action([$char_id, $coin]);
    }

    /**
     * GET /condition/{coin}/characters
     */
    public function conditionsIndex(int $coin): void
    {
        $parent = $this->fetchTable('Conditions')->get($coin);

        $query = $this->CharactersConditions->findWithContain();
        $query->andWhere(['CharactersConditions.condition_id' => $coin]);

        $this->loadComponent('IndexRelation');
        $this->IndexRelation->action($parent, $query);
    }
}

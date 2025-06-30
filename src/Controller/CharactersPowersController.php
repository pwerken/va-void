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
 * @property \App\Model\Table\CharactersPowersTable $CharactersPowers;
 */
class CharactersPowersController extends Controller
{
    /**
     * GET /characters/{plin}/{chin}/powers
     */
    public function charactersIndex(int $char_id): void
    {
        $parent = $this->fetchTable('Characters')->get($char_id);

        $query = $this->CharactersPowers->find('withContain');
        $query->andWhere(['CharactersPowers.character_id' => $char_id]);

        $this->loadComponent('IndexRelation');
        $this->IndexRelation->action($parent, $query);
    }

    /**
     * PUT /characters/{plin}/{chin}/powers
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
     * GET /characters/{plin}/{chin}/powers/{poin}
     */
    public function charactersView(int $char_id, int $poin): void
    {
        $parent = $this->fetchTable('Characters')->get($char_id);
        $this->Authorization->authorize($parent, 'view');

        $this->loadComponent('View');
        $this->View->action([$char_id, $poin], false);
    }

    /**
     * PUT /characters/{plin}/{chin}/powers/{poin}
     */
    public function charactersEdit(int $char_id, int $poin): void
    {
        $this->loadComponent('Edit');
        $this->Edit->action([$char_id, $poin]);
    }

    /**
     * DELETE /characters/{plin}/{chin}/powers/{poin}
     */
    public function charactersDelete(int $char_id, int $poin): void
    {
        $this->loadComponent('Delete');
        $this->Delete->action([$char_id, $poin]);
    }

    /**
     * POST /characters/{plin}/{chin}/powers/{poin}/print
     */
    public function charactersQueue(int $char_id, int $poin): void
    {
        $this->loadComponent('QueueLammy');
        $this->QueueLammy->action([$char_id, $poin]);
    }

    /**
     * GET /powers/{poin}/characters
     */
    public function powersIndex(int $poin): void
    {
        $parent = $this->fetchTable('Powers')->get($poin);

        $query = $this->CharactersPowers->find('withContain');
        $query->andWhere(['CharactersPowers.power_id' => $poin]);

        $this->loadComponent('IndexRelation');
        $this->IndexRelation->action($parent, $query);
    }
}

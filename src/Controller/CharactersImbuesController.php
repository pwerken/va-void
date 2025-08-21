<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * @property \App\Controller\Component\AddComponent $Add
 * @property \App\Controller\Component\DeleteComponent $Delete
 * @property \App\Controller\Component\EditComponent $Edit
 * @property \App\Controller\Component\IndexRelationComponent $IndexRelation
 * @property \App\Controller\Component\LammyComponent $Lammy
 * @property \App\Controller\Component\ViewComponent $View
 */
class CharactersImbuesController extends Controller
{
    /**
     * GET /characters/{plin}/{chin}/...imbues
     */
    public function charactersIndex(int $char_id): void
    {
        $parent = $this->fetchTable('Characters')->get($char_id);

        $query = $this->fetchTable()->find('withContain');
        $query->andWhere(['character_id' => $char_id]);

        $this->loadComponent('IndexRelation');
        $this->IndexRelation->action($parent, $query);
    }

    /**
     * PUT /characters/{plin}/{chin}/...imbues
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
     * GET /characters/{plin}/{chin}/...imbues/{id}
     */
    public function charactersView(int $char_id, int $id): void
    {
        $parent = $this->fetchTable('Characters')->get($char_id);
        $this->Authorization->authorize($parent, 'view');

        $this->loadComponent('View');
        $this->View->action([$char_id, $id], false);
    }

    /**
     * PUT /characters/{plin}/{chin}/...imbues/{id}
     */
    public function charactersEdit(int $char_id, int $id): void
    {
        $this->loadComponent('Edit');
        $this->Edit->action([$char_id, $id]);
    }

    /**
     * DELETE /characters/{plin}/{chin}/...imbues/{id}
     */
    public function charactersDelete(int $char_id, int $id): void
    {
        $this->loadComponent('Delete');
        $this->Delete->action([$char_id, $id]);
    }

    /**
     * GET /characters/{plin}/{chin}/...imbues/{id}/print
     */
    public function charactersPdf(int $char_id, int $id): void
    {
        $this->loadComponent('Lammy');
        $this->Lammy->actionPdf([$char_id, $id]);
    }

    /**
     * POST /characters/{plin}/{chin}/...imbues/{id}/print
     */
    public function charactersQueue(int $char_id, int $id): void
    {
        $this->loadComponent('Lammy');
        $this->Lammy->actionQueue([$char_id, $id]);
    }

    /**
     * GET /imbues/{id}/characters
     */
    public function imbuesIndex(int $id): void
    {
        $parent = $this->fetchTable('Imbues')->get($id);

        $query = $this->fetchTable('CharactersImbues')->find('withContain');
        $query->andWhere(['imbue_id' => $id]);

        $this->loadComponent('IndexRelation');
        $this->IndexRelation->action($parent, $query);
    }
}

<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Traits\AddTrait;
use App\Controller\Traits\DeleteTrait;
use App\Controller\Traits\EditTrait;
use App\Controller\Traits\IndexTrait;
use App\Controller\Traits\ViewTrait;

/**
 * @property \App\Controller\Component\IndexRelationComponent $IndexRelation
 * @property \App\Controller\Component\LammyComponent $Lammy
 */
class ItemsController extends Controller
{
    use IndexTrait; // GET /items
    use AddTrait; // PUT /items
    use ViewTrait; // GET /items/{itin}
    use EditTrait; // PUT /items/{itin}
    use DeleteTrait; // DELETE /items/{itin}

    /**
     * POST /items/{itin}/print
     */
    public function queue(int $itin): void
    {
        $this->loadComponent('Lammy');
        $this->Lammy->actionQueue($itin);
    }

    /**
     * GET /characters/{plin}/{chin}/items
     */
    public function charactersIndex(int $char_id): void
    {
        $parent = $this->fetchTable('Characters')->get($char_id);

        $query = $this->fetchTable()->find();
        $query->andWhere(['Items.character_id' => $char_id]);

        $this->loadComponent('IndexRelation');
        $this->IndexRelation->action($parent, $query);
    }
}

<?php
declare(strict_types=1);

namespace App\Controller;

class CharactersPowersController
    extends AppController
{
    // GET /characters/{plin}/{chin}/powers
    public function charactersIndex(int $char_id): void
    {
        $parent = $this->fetchModel('Characters')->get($char_id);

        $query = $this->CharactersPowers->findWithContain();
        $query->andWhere(['CharactersPowers.character_id' => $char_id]);

        $this->IndexRelation->action($parent, $query);
    }

    // PUT /characters/{plin}/{chin}/powers
    public function charactersAdd(int $char_id): void
    {
        $request = $this->getRequest();
        $request = $request->withData('character_id', $char_id);
        $this->setRequest($request);

        $this->Add->action();
    }

    // GET /characters/{plin}/{chin}/powers/{poin}
    public function charactersView(int $char_id, int $poin): void
    {
        $parent = $this->fetchModel('Characters')->get($char_id);
        $this->Authorization->authorize($parent, 'view');

        $this->View->action([$char_id, $poin], false);

        $obj = $this->viewBuilder()->getVar('_serialize');
        $modified = [];
        $modified[] = $obj->modified;
        $modified[] = $obj->character->modified;
        $modified[] = $obj->power->modified;

        $this->checkModified($modified);
    }

    // PUT /characters/{plin}/{chin}/powers/{poin}
    public function charactersEdit(int $char_id, int $poin): void
    {
        $this->Edit->action([$char_id, $poin]);
    }

    // DELETE /characters/{plin}/{chin}/powers/{poin}
    public function charactersDelete(int $char_id, int $poin): void
    {
        $this->Delete->action([$char_id, $poin]);
    }

    // POST /characters/{plin}/{chin}/powers/{poin}/print
    public function charactersQueue(int $char_id, int $poin): void
    {
        $this->QueueLammy->action([$char_id, $poin]);
    }

    // GET /powers/{poin}/characters
    public function powersIndex(int $poin): void
    {
        $parent = $this->fetchModel('Powers')->get($poin);

        $query = $this->CharactersPowers->findWithContain();
        $query->andWhere(['CharactersPowers.power_id' => $poin]);

        $this->IndexRelation->action($parent, $query);
    }
}

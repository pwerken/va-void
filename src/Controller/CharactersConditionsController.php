<?php
declare(strict_types=1);

namespace App\Controller;

class CharactersConditionsController
    extends AppController
{
    // GET /characters/{plin}/{chin}/conditions
    public function charactersIndex(int $char_id): void
    {
        $parent = $this->fetchModel('Characters')->get($char_id);

        $query = $this->CharactersConditions->findWithContain();
        $query->andWhere(['CharactersConditions.character_id' => $char_id]);

        $this->IndexRelation->action($parent, $query);
    }

    // PUT /characters/{plin}/{chin}/conditions
    public function charactersAdd(int $char_id): void
    {
        $request = $this->getRequest();
        $request = $request->withData('character_id', $char_id);
        $this->setRequest($request);

        $this->Add->action();
    }

    // GET /characters/{plin}/{chin}/conditions/{coin}
    public function charactersView(int $char_id, int $coin): void
    {
        $parent = $this->fetchModel('Characters')->get($char_id);
        $this->Authorization->authorize($parent, 'view');

        $this->View->action([$char_id, $coin], false);

        $obj = $this->viewBuilder()->getVar('_serialize');
        $modified = [];
        $modified[] = $obj->modified;
        $modified[] = $obj->character->modified;
        $modified[] = $obj->condition->modified;

        $this->checkModified($modified);
    }

    // PUT /characters/{plin}/{chin}/conditions/{coin}
    public function charactersEdit(int $char_id, int $coin): void
    {
        $this->Edit->action([$char_id, $coin]);
    }

    // DELETE /characters/{plin}/{chin}/conditions/{coin}
    public function charactersDelete(int $char_id, int $coin): void
    {
        $this->Delete->action([$char_id, $coin]);
    }

    // POST /characters/{plin}/{chin}/conditions/{coin}/print
    public function charactersQueue(int $char_id, int $coin): void
    {
        $this->QueueLammy->action([$char_id, $coin]);
    }

    // GET /condition/{coin}/characters
    public function conditionsIndex(int $coin): void
    {
        $parent = $this->fetchModel('Conditions')->get($coin);

        $query = $this->CharactersConditions->findWithContain();
        $query->andWhere(['CharactersConditions.condition_id' => $coin]);

        $this->IndexRelation->action($parent, $query);
    }
}

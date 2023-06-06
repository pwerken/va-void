<?php
declare(strict_types=1);

namespace App\Controller;

class CharactersConditionsController
    extends AppController
{
    // GET /characters/{plin}/{chin}/conditions
    public function charactersIndex(int $char_id): void
    {
        $this->parent = $this->loadModel('Characters')->get($char_id);
        $this->Authorization->authorize($this->parent, 'view');

        $query = $this->CharactersConditions->findWithContain();
        $query->andWhere(['CharactersConditions.character_id' => $char_id]);

        $this->set('parent', $this->parent);
        $this->set('_serialize', $query->all());
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
        $parent = $this->loadModel('Characters')->get($char_id);
        $this->Authorization->authorize($parent, 'view');

        $this->View->action([$char_id, $coin], false);
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
        $this->parent = $this->loadModel('Conditions')->get($coin);

        $query = $this->CharactersConditions->findWithContain();
        $query->andWhere(['CharactersConditions.condition_id' => $coin]);

        $this->set('parent', $this->parent);
        $this->set('_serialize', $query->all());
    }
}

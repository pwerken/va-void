<?php
declare(strict_types=1);

namespace App\Controller;

class CharactersSpellsController
    extends AppController
{
    // GET /characters/{plin}/{chin}/spells
    public function charactersIndex(int $char_id): void
    {
        $this->parent = $this->loadModel('Characters')->get($char_id);
        $this->Authorization->authorize($this->parent, 'view');

        $query = $this->CharactersSpells->findWithContain();
        $query->andWhere(['CharactersSpells.character_id' => $char_id]);

        $this->set('parent', $this->parent);
        $this->set('_serialize', $query->all());
    }

    // PUT /characters/{plin}/{chin}/spells
    public function charactersAdd(int $char_id): void
    {
        $request = $this->getRequest();
        $request = $request->withData('character_id', $char_id);
        $this->setRequest($request);

        $this->Add->action();
    }

    // GET /characters/{plin}/{chin}/spells/{id}
    public function charactersView(int $char_id, int $spell_id): void
    {
        $parent = $this->loadModel('Characters')->get($char_id);
        $this->Authorization->authorize($parent, 'view');

        $this->View->action([$char_id, $spell_id], false);
    }

    // PUT /characters/{plin}/{chin}/spells/{id}
    public function charactersEdit(int $char_id, int $spell_id): void
    {
        $this->Edit->action([$char_id, $spell_id]);
    }

    // DELETE /characters/{plin}/{chin}/spells/{id}
    public function charactersDelete(int $char_id, int $spell_id): void
    {
        $this->Delete->action([$char_id, $spell_id]);
    }

    // GET /spells/{id}/characters
    public function spellsIndex(int $spell_id): void
    {
        $this->parent = $this->loadModel('Spells')->get($spell_id);

        $query = $this->CharactersSpells->findWithContain();
        $query->andWhere(['CharactersSpells.spell_id' => $spell_id]);

        $this->set('parent', $this->parent);
        $this->set('_serialize', $query->all());
    }
}

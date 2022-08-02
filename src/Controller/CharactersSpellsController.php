<?php
declare(strict_types=1);

namespace App\Controller;

class CharactersSpellsController
    extends AppController
{

    public function charactersIndex($char_id)
    {
        $this->parent = $this->loadModel('Characters')->get($char_id);
        if (is_null($this->parent)) {
            throw new NotFoundException();
#        $this->Authorization->authorize($this->parent, 'view');
        }

        $query = $this->CharactersSpells->findWithContain();
        $query->andWhere(['CharactersSpells.character_id' => $char_id]);
#        $this->Authorization->applyScope($query);

        $this->set('parent', $this->parent);
        $this->set('_serialize', $query->all());
    }

    public function charactersView($char_id, $spell_id)
    {
        $query = $this->CharactersSpells->findWithContain();
        $query->andWhere(['CharactersSpells.character_id' => $char_id]);
        $query->andWhere(['CharactersSpells.spell_id' => $spell_id]);
        $obj = $query->first();
        if (is_null($obj)) {
            throw new NotFoundException();
        }

#        $this->Authorization->authorize($obj);
        $this->set('_serialize', $obj);
    }

    public function spellsIndex($spell_id)
    {
        $this->parent = $this->loadModel('Spells')->get($spell_id);
        if (is_null($this->parent)) {
            throw new NotFoundException();
        }
#        $this->Authorization->authorize($this->parent, 'view');

        $query = $this->CharactersSpells->findWithContain();
        $query->andWhere(['CharactersSpells.spell_id' => $spell_id]);
#        $this->Authorization->applyScope($query);

        $this->set('parent', $this->parent);
        $this->set('_serialize', $query->all());
    }
}

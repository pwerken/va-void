<?php
declare(strict_types=1);

namespace App\Controller;

class CharactersConditionsController
    extends AppController
{

    public function charactersIndex($char_id)
    {
        $this->parent = $this->loadModel('Characters')->get($char_id);
        $this->Authorization->authorize($this->parent, 'view');

        $query = $this->CharactersConditions->findWithContain();
        $query->andWhere(['CharactersConditions.character_id' => $char_id]);
#        $this->Authorization->applyScope($query);

        $this->set('parent', $this->parent);
        $this->set('_serialize', $query->all());
    }

    public function charactersView($char_id, $coin)
    {
        $parent = $this->loadModel('Characters')->get($char_id);
        $this->Authorization->authorize($parent, 'view');

        $query = $this->CharactersConditions->findWithContain();
        $query->andWhere(['CharactersConditions.character_id' => $char_id]);
        $query->andWhere(['CharactersConditions.condition_id' => $coin]);
        $obj = $query->firstOrFail();

        $this->set('_serialize', $obj);
    }

    public function conditionsIndex($coin)
    {
        $this->parent = $this->loadModel('Conditions')->get($coin);
        $this->Authorization->authorize($this->parent, 'view');

        $query = $this->CharactersConditions->findWithContain();
        $query->andWhere(['CharactersConditions.condition_id' => $coin]);
#        $this->Authorization->applyScope($query);

        $this->set('parent', $this->parent);
        $this->set('_serialize', $query->all());
    }
}

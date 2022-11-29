<?php
declare(strict_types=1);

namespace App\Controller;

class CharactersPowersController
    extends AppController
{

    public function charactersIndex($char_id)
    {
        $this->parent = $this->loadModel('Characters')->get($char_id);
        $this->Authorization->authorize($this->parent, 'view');

        $query = $this->CharactersPowers->findWithContain();
        $query->andWhere(['CharactersPowers.character_id' => $char_id]);
#       $this->Authorization->applyScope($query);

        $this->set('parent', $this->parent);
        $this->set('_serialize', $query->all());
    }

    public function charactersView($char_id, $poin)
    {
        $parent = $this->loadModel('Characters')->get($char_id);
        $this->Authorization->authorize($parent, 'view');

        $query = $this->CharactersPowers->findWithContain();
        $query->andWhere(['CharactersPowers.character_id' => $char_id]);
        $query->andWhere(['CharactersPowers.power_id' => $poin]);
        $obj = $query->firstOrFail();

        $this->set('_serialize', $obj);
    }

    public function powersIndex($poin)
    {
        $this->parent = $this->loadModel('Powers')->get($poin);
        $this->Authorization->authorize($this->parent, 'view');

        $query = $this->CharactersPowers->findWithContain();
        $query->andWhere(['CharactersPowers.power_id' => $poin]);
#       $this->Authorization->applyScope($query);

        $this->set('parent', $this->parent);
        $this->set('_serialize', $query->all());
    }
}

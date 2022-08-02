<?php
declare(strict_types=1);

namespace App\Controller;

class CharactersPowersController
    extends AppController
{

    public function charactersIndex($char_id)
    {
        $this->parent = $this->loadModel('Characters')->get($char_id);
        if (is_null($this->parent)) {
            throw new NotFoundException();
#        $this->Authorization->authorize($this->parent, 'view');
        }

        $query = $this->CharactersPowers->findWithContain();
        $query->andWhere(['CharactersPowers.character_id' => $char_id]);
#       $this->Authorization->applyScope($query);

        $this->set('parent', $this->parent);
        $this->set('_serialize', $query->all());
    }

    public function charactersView($char_id, $poin)
    {
        $query = $this->CharactersPowers->findWithContain();
        $query->andWhere(['CharactersPowers.character_id' => $char_id]);
        $query->andWhere(['CharactersPowers.power_id' => $poin]);
        $obj = $query->first();
        if (is_null($obj)) {
            throw new NotFoundException();
        }

#        $this->Authorization->authorize($obj);
        $this->set('_serialize', $obj);
    }

    public function powersIndex($poin)
    {
        $this->parent = $this->loadModel('Powers')->get($poin);
        if (is_null($this->parent)) {
            throw new NotFoundException();
        }
#        $this->Authorization->authorize($this->parent, 'view');

        $query = $this->CharactersPowers->findWithContain();
        $query->andWhere(['CharactersPowers.power_id' => $poin]);
#       $this->Authorization->applyScope($query);

        $this->set('parent', $this->parent);
        $this->set('_serialize', $query->all());
    }
}

<?php
declare(strict_types=1);

namespace App\Controller;

class CharactersConditionsController
    extends AppController
{

    public function charactersIndex($char_id)
    {
        $this->parent = $this->loadModel('Characters')->get($char_id);
        if (is_null($this->parent)) {
            throw new NotFoundException();
        }
#        $this->Authorization->authorize($this->parent, 'view');

        $query = $this->CharactersConditions->findWithContain();
        $query->andWhere(['CharactersConditions.character_id' => $char_id]);
#        $this->Authorization->applyScope($query);

        $this->set('parent', $this->parent);
        $this->set('_serialize', $query->all());
    }

    public function conditionsIndex($coin)
    {
        $this->parent = $this->loadModel('Conditions')->get($coin);
        if (is_null($this->parent)) {
            throw new NotFoundException();
        }
#        $this->Authorization->authorize($this->parent, 'view');

        $query = $this->CharactersConditions->findWithContain();
        $query->andWhere(['CharactersConditions.condition_id' => $coin]);
#        $this->Authorization->applyScope($query);

        $this->set('parent', $this->parent);
        $this->set('_serialize', $query->all());
    }

    public function charactersView($char_id, $coin)
    {
        $query = $this->CharactersConditions->findWithContain();
        $query->andWhere(['CharactersConditions.character_id' => $char_id]);
        $query->andWhere(['CharactersConditions.condition_id' => $coin]);
        $obj = $query->first();
        if (is_null($obj)) {
            throw new NotFoundException();
        }

#        $this->Authorization->authorize($obj);
        $this->set('_serialize', $obj);
    }
}

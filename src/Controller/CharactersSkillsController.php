<?php
declare(strict_types=1);

namespace App\Controller;

class CharactersSkillsController
    extends AppController
{

    public function charactersIndex($char_id)
    {
        $this->parent = $this->loadModel('Characters')->get($char_id);
        if (is_null($this->parent)) {
            throw new NotFoundException();
#        $this->Authorization->authorize($this->parent, 'view');
        }

        $query = $this->CharactersSkills->findWithContain();
        $query->andWhere(['CharactersSkills.character_id' => $char_id]);
#        $this->Authorization->applyScope($query);

        $this->set('parent', $this->parent);
        $this->set('_serialize', $query->all());
    }

    public function charactersView($char_id, $skill_id)
    {
        $query = $this->CharactersSkills->findWithContain();
        $query->andWhere(['CharactersSkills.character_id' => $char_id]);
        $query->andWhere(['CharactersSkills.skill_id' => $skill_id]);
        $obj = $query->first();
        if (is_null($obj)) {
            throw new NotFoundException();
        }

#        $this->Authorization->authorize($obj);
        $this->set('_serialize', $obj);
    }

    public function skillsIndex($skill_id)
    {
        $this->parent = $this->loadModel('Skills')->get($skill_id);
        if (is_null($this->parent)) {
            throw new NotFoundException();
        }
#        $this->Authorization->authorize($this->parent, 'view');

        $query = $this->CharactersSkills->findWithContain();
        $query->andWhere(['CharactersSkills.skill_id' => $skill_id]);
#        $this->Authorization->applyScope($query);

        $this->set('parent', $this->parent);
        $this->set('_serialize', $query->all());
    }
}

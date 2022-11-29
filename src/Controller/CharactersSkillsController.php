<?php
declare(strict_types=1);

namespace App\Controller;

class CharactersSkillsController
    extends AppController
{

    public function charactersIndex($char_id)
    {
        $this->parent = $this->loadModel('Characters')->get($char_id);
        $this->Authorization->authorize($this->parent, 'view');

        $query = $this->CharactersSkills->findWithContain();
        $query->andWhere(['CharactersSkills.character_id' => $char_id]);
#        $this->Authorization->applyScope($query);

        $this->set('parent', $this->parent);
        $this->set('_serialize', $query->all());
    }

    public function charactersView($char_id, $skill_id)
    {
        $parent = $this->loadModel('Characters')->get($char_id);
        $this->Authorization->authorize($parent, 'view');

        $query = $this->CharactersSkills->findWithContain();
        $query->andWhere(['CharactersSkills.character_id' => $char_id]);
        $query->andWhere(['CharactersSkills.skill_id' => $skill_id]);
        $obj = $query->firstOrFail();

        $this->set('_serialize', $obj);
    }

    public function skillsIndex($skill_id)
    {
        $this->parent = $this->loadModel('Skills')->get($skill_id);
        $this->Authorization->authorize($this->parent, 'view');

        $query = $this->CharactersSkills->findWithContain();
        $query->andWhere(['CharactersSkills.skill_id' => $skill_id]);
#        $this->Authorization->applyScope($query);

        $this->set('parent', $this->parent);
        $this->set('_serialize', $query->all());
    }
}

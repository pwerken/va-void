<?php
declare(strict_types=1);

namespace App\Controller;

class CharactersSkillsController
    extends AppController
{
    // GET /characters/{plin}/{chin}/skills
    public function charactersIndex(int $char_id): void
    {
        $parent = $this->fetchTable('Characters')->get($char_id);

        $query = $this->CharactersSkills->findWithContain();
        $query->andWhere(['CharactersSkills.character_id' => $char_id]);

        $this->IndexRelation->action($parent, $query);
    }

    // PUT /characters/{plin}/{chin}/skills
    public function charactersAdd(int $char_id): void
    {
        $request = $this->getRequest();
        $request = $request->withData('character_id', $char_id);
        $this->setRequest($request);

        $this->Add->action();
    }

    // GET /characters/{plin}/{chin}/skills/{id}
    public function charactersView(int $char_id, int $skill_id): void
    {
        $parent = $this->fetchTable('Characters')->get($char_id);
        $this->Authorization->authorize($parent, 'view');

        $this->View->action([$char_id, $skill_id], false);
    }

    // PUT /characters/{plin}/{chin}/skills/{id}
    public function charactersEdit(int $char_id, int $skill_id): void
    {
        $this->Edit->action([$char_id, $skill_id]);
    }

    // DELETE /characters/{plin}/{chin}/skills/{id}
    public function charactersDelete(int $char_id, int $skill_id): void
    {
        $this->Delete->action([$char_id, $skill_id]);
    }

    // GET /skills/{id}/characters
    public function skillsIndex(int $skill_id): void
    {
        $parent = $this->fetchTable('Skills')->get($skill_id);

        $query = $this->CharactersSkills->findWithContain();
        $query->andWhere(['CharactersSkills.skill_id' => $skill_id]);

        $this->IndexRelation->action($parent, $query);
    }
}

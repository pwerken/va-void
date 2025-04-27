<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Exception\NotFoundException;

class PlayersSocialsController
    extends AppController
{
    protected ?string $defaultTable = 'SocialProfiles';

    // GET /players/{plin}/socials
    public function playersIndex(int $plin): void
    {
        $parent = $this->fetchTable('Players')->get($plin);

        $query = $this->SocialProfiles->findWithContain();
        $query->andWhere(['user_id' => $plin]);

        $this->IndexRelation->action($parent, $query, 'edit');
    }

    // GET /players/{plin}/socials/{id}
    public function playersView(int $plin, int $id): void
    {
        $obj = $this->SocialProfiles->getWithContain($id);
        if($obj->user_id != $plin) {
            throw new NotFoundException();
        }
        $this->Authorization->authorize($obj, 'view');
        $this->set('_serialize', $obj);
    }

    // DELETE /players/{plin}/socials/{id}
    public function playersDelete(int $plin, int $id): void
    {
        $this->Delete->action($id);
    }
}

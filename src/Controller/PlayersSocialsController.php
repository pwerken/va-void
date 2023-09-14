<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Exception\NotFoundException;

class PlayersSocialsController
    extends AppController
{
    protected $defaultTable = 'SocialProfiles';

    // GET /players/{plin}/socials
    public function playersIndex(int $plin): void
    {
        $parent = $this->loadModel('Players')->get($plin);
        $this->Authorization->authorize($parent, 'edit');

        $query = $this->fetchTable()->findWithContain();
        $query->andWhere(['user_id' => $plin]);

        $this->set('parent', $parent);
        $this->set('_serialize', $query->all());
    }

    // GET /players/{plin}/socials/{id}
    public function playersView(int $plin, int $id): void
    {
        $obj = $this->fetchTable()->getWithContain($id);
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

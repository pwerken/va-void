<?php
declare(strict_types=1);

namespace App\Controller;

class AttributesItemsController
    extends AppController
{

    public function attributesIndex($id)
    {
        $this->parent = $this->loadModel('Attributes')->get($id);
#TODO   $this->Authorization->authorize($this->parent, 'view');

        $query = $this->AttributesItems->findWithContain();
        $query->andWhere(['attribute_id' => $id]);
#TODO   $this->Authorization->applyScope($query);

        $this->set('parent', $this->parent);
        $this->set('_serialize', $query->all());
    }

    public function itemsAdd($itin)
    {
        $this->request->withData('itin_id', $itin);
        //TODO $this->Create->action()
    }

    public function itemsIndex($itin)
    {
        $this->parent = $this->loadModel('Items')->get($itin);
#TODO   $this->Authorization->authorize($this->parent, 'view');

        $query = $this->AttributesItems->findWithContain();
        $query->andWhere(['item_id' => $itin]);
#TODO   $this->Authorization->applyScope($query);

        $this->set('parent', $this->parent);
        $this->set('_serialize', $query->all());
    }

    public function itemsView($itin, $id)
    {
        $query = $this->AttributesItems->findWithContain();
        $query->andWhere(['item_id' => $itin, 'attribute_id' => $id]);
        $obj = $query->firstOrFail();
        $this->Authorization->authorize($obj);

        $this->set('_serialize', $obj);
    }
}

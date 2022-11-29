<?php
declare(strict_types=1);

namespace App\Controller;

class AttributesItemsController
    extends AppController
{

    public function attributesIndex($id)
    {
        $this->parent = $this->loadModel('Attributes')->get($id);
#        $this->Authorization->authorize($this->parent, 'view');

        $query = $this->AttributesItems->findWithContain();
        $query->andWhere(['AttributesItems.attribute_id' => $id]);
#        $this->Authorization->applyScope($query);

        $this->set('parent', $this->parent);
        $this->set('_serialize', $query->all());
    }

    public function itemsIndex($itin)
    {
        $this->parent = $this->loadModel('Items')->get($itin);
#        $this->Authorization->authorize($this->parent, 'view');

        $query = $this->AttributesItems->findWithContain();
        $query->andWhere(['AttributesItems.item_id' => $itin]);
#        $this->Authorization->applyScope($query);

        $this->set('parent', $this->parent);
        $this->set('_serialize', $query->all());
    }

    public function itemsView($itin, $id)
    {
        $query = $this->AttributesItems->findWithContain();
        $query->andWhere(['AttributesItems.item_id' => $itin]);
        $query->andWhere(['AttributesItems.attribute_id' => $id]);
        $obj = $query->firstOrFail();

#        $this->Authorization->authorize($obj);
        $this->set('_serialize', $obj);
    }
}

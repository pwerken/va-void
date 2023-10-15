<?php
declare(strict_types=1);

namespace App\Controller;

class AttributesItemsController
    extends AppController
{
    // GET /attributes/{id}/items
    public function attributesIndex(int $id): void
    {
        $this->parent = $this->fetchModel('Attributes')->get($id);

        $query = $this->AttributesItems->findWithContain();
        $query->andWhere(['attribute_id' => $id]);

        $this->set('parent', $this->parent);
        $this->set('_serialize', $query->all());
    }

    // GET /items/{itin}/attributes
    public function itemsIndex(int $itin): void
    {
        $this->parent = $this->fetchModel('Items')->get($itin);

        $query = $this->AttributesItems->findWithContain();
        $query->andWhere(['item_id' => $itin]);

        $this->set('parent', $this->parent);
        $this->set('_serialize', $query->all());
    }

    // PUT /items/{itin}/attributes
    public function itemsAdd(int $itin): void
    {
        $request = $this->getRequest();
        $request = $request->withData('item_id', $itin);
        $this->setRequest($request);

        $this->Add->action();
    }

    // GET /items/{itin}/attributes/{id}
    public function itemsView(int $itin, int $id): void
    {
        $parent = $this->fetchModel('Items')->get($itin);
        $this->Authorization->authorize($parent, 'view');

        $this->View->action([$id, $itin], false);
    }

    // DELETE /items/{itin}/attributes/{id}
    public function itemsDelete(int $itin, int $id): void
    {
        $this->Delete->action([$id, $itin]);
    }
}

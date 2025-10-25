<?php
declare(strict_types=1);

namespace App\Controller\Admin\Traits;

use Cake\Http\Response;

trait CRUDTrait
{
    public function index(): void
    {
        $this->getRequest()->allowMethod(['get']);

        $objs = $this->fetchTable()->find()->all();
        $this->set(compact('objs'));
    }

    public function add(): ?Response
    {
        $this->getRequest()->allowMethod(['get', 'post']);

        $model = $this->fetchTable();
        $obj = $model->newEmptyEntity();
        $this->Authorization->authorize($obj, 'add');

        if ($this->getRequest()->is('post')) {
            $obj = $model->patchEntity($obj, $this->request->getData());
            if ($model->save($obj)) {
                $this->Flash->success('Added id#' . $obj->get('id'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error('Failed to add');
        }

        foreach ($model->associations()->getByType('BelongsTo') as $assoc) {
            $this->set($assoc->getTable(), $assoc->find('list')->all());
        }

        $this->set(compact('obj'));

        $this->viewBuilder()->setTemplate('edit');

        return null;
    }

    public function edit(int $id): ?Response
    {
        $this->getRequest()->allowMethod(['get', 'post']);

        $model = $this->fetchTable();
        $obj = $model->get($id);
        $this->Authorization->authorize($obj, 'edit');

        if ($this->getRequest()->is('post')) {
            $obj = $model->patchEntity($obj, $this->request->getData());
            if ($model->save($obj)) {
                $this->Flash->error('Modified id#' . $obj->get('id'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error('Failed to edit id#' . $obj->get('id'));
        }

        foreach ($model->associations()->getByType('BelongsTo') as $assoc) {
            $this->set($assoc->getTable(), $assoc->find('list')->all());
        }

        $this->set(compact('obj'));

        return null;
    }

    public function delete(int $id): Response
    {
        $this->getRequest()->allowMethod(['post']);

        $model = $this->fetchTable();
        $obj = $model->get($id);
        $this->Authorization->authorize($obj, 'delete');

        if ($model->delete($obj)) {
            $this->Flash->success('Deleted id#' . $obj->get('id'));
        } else {
            $this->Flash->error('Failed to delete id#' . $obj->get('id'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

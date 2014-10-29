<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * AttributesItems Controller
 *
 * @property App\Model\Table\AttributesItemsTable $AttributesItems
 */
class AttributesItemsController extends AppController {

/**
 * Index method
 *
 * @return void
 */
	public function index() {
		$this->paginate = [
			'contain' => ['Attributes', 'Items']
		];
		$this->set('attributesItems', $this->paginate($this->AttributesItems));
	}

/**
 * View method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function view($id = null) {
		$attributesItem = $this->AttributesItems->get($id, [
			'contain' => ['Attributes', 'Items']
		]);
		$this->set('attributesItem', $attributesItem);
	}

/**
 * Add method
 *
 * @return void
 */
	public function add() {
		$attributesItem = $this->AttributesItems->newEntity($this->request->data);
		if ($this->request->is('post')) {
			if ($this->AttributesItems->save($attributesItem)) {
				$this->Flash->success('The attributes item has been saved.');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('The attributes item could not be saved. Please, try again.');
			}
		}
		$attributes = $this->AttributesItems->Attributes->find('list');
		$items = $this->AttributesItems->Items->find('list');
		$this->set(compact('attributesItem', 'attributes', 'items'));
	}

/**
 * Edit method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function edit($id = null) {
		$attributesItem = $this->AttributesItems->get($id, [
			'contain' => []
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$attributesItem = $this->AttributesItems->patchEntity($attributesItem, $this->request->data);
			if ($this->AttributesItems->save($attributesItem)) {
				$this->Flash->success('The attributes item has been saved.');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('The attributes item could not be saved. Please, try again.');
			}
		}
		$attributes = $this->AttributesItems->Attributes->find('list');
		$items = $this->AttributesItems->Items->find('list');
		$this->set(compact('attributesItem', 'attributes', 'items'));
	}

/**
 * Delete method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function delete($id = null) {
		$attributesItem = $this->AttributesItems->get($id);
		$this->request->allowMethod(['post', 'delete']);
		if ($this->AttributesItems->delete($attributesItem)) {
			$this->Flash->success('The attributes item has been deleted.');
		} else {
			$this->Flash->error('The attributes item could not be deleted. Please, try again.');
		}
		return $this->redirect(['action' => 'index']);
	}
}

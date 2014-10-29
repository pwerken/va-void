<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Items Controller
 *
 * @property App\Model\Table\ItemsTable $Items
 */
class ItemsController extends AppController {

/**
 * Index method
 *
 * @return void
 */
	public function index() {
		$this->paginate = [
			'contain' => ['Characters']
		];
		$this->set('items', $this->paginate($this->Items));
	}

/**
 * View method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function view($id = null) {
		$item = $this->Items->get($id, [
			'contain' => ['Characters', 'Attributes']
		]);
		$this->set('item', $item);
	}

/**
 * Add method
 *
 * @return void
 */
	public function add() {
		$item = $this->Items->newEntity($this->request->data);
		if ($this->request->is('post')) {
			if ($this->Items->save($item)) {
				$this->Flash->success('The item has been saved.');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('The item could not be saved. Please, try again.');
			}
		}
		$characters = $this->Items->Characters->find('list');
		$attributes = $this->Items->Attributes->find('list');
		$this->set(compact('item', 'characters', 'attributes'));
	}

/**
 * Edit method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function edit($id = null) {
		$item = $this->Items->get($id, [
			'contain' => ['Attributes']
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$item = $this->Items->patchEntity($item, $this->request->data);
			if ($this->Items->save($item)) {
				$this->Flash->success('The item has been saved.');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('The item could not be saved. Please, try again.');
			}
		}
		$characters = $this->Items->Characters->find('list');
		$attributes = $this->Items->Attributes->find('list');
		$this->set(compact('item', 'characters', 'attributes'));
	}

/**
 * Delete method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function delete($id = null) {
		$item = $this->Items->get($id);
		$this->request->allowMethod(['post', 'delete']);
		if ($this->Items->delete($item)) {
			$this->Flash->success('The item has been deleted.');
		} else {
			$this->Flash->error('The item could not be deleted. Please, try again.');
		}
		return $this->redirect(['action' => 'index']);
	}
}

<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Attributes Controller
 *
 * @property App\Model\Table\AttributesTable $Attributes
 */
class AttributesController extends AppController {

/**
 * Index method
 *
 * @return void
 */
	public function index() {
		$this->set('attributes', $this->paginate($this->Attributes));
	}

/**
 * View method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function view($id = null) {
		$attribute = $this->Attributes->get($id, [
			'contain' => ['Items']
		]);
		$this->set('attribute', $attribute);
	}

/**
 * Add method
 *
 * @return void
 */
	public function add() {
		$attribute = $this->Attributes->newEntity($this->request->data);
		if ($this->request->is('post')) {
			if ($this->Attributes->save($attribute)) {
				$this->Flash->success('The attribute has been saved.');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('The attribute could not be saved. Please, try again.');
			}
		}
		$items = $this->Attributes->Items->find('list');
		$this->set(compact('attribute', 'items'));
	}

/**
 * Edit method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function edit($id = null) {
		$attribute = $this->Attributes->get($id, [
			'contain' => ['Items']
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$attribute = $this->Attributes->patchEntity($attribute, $this->request->data);
			if ($this->Attributes->save($attribute)) {
				$this->Flash->success('The attribute has been saved.');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('The attribute could not be saved. Please, try again.');
			}
		}
		$items = $this->Attributes->Items->find('list');
		$this->set(compact('attribute', 'items'));
	}

/**
 * Delete method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function delete($id = null) {
		$attribute = $this->Attributes->get($id);
		$this->request->allowMethod(['post', 'delete']);
		if ($this->Attributes->delete($attribute)) {
			$this->Flash->success('The attribute has been deleted.');
		} else {
			$this->Flash->error('The attribute could not be deleted. Please, try again.');
		}
		return $this->redirect(['action' => 'index']);
	}
}

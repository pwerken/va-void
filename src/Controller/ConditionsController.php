<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Conditions Controller
 *
 * @property App\Model\Table\ConditionsTable $Conditions
 */
class ConditionsController extends AppController {

/**
 * Index method
 *
 * @return void
 */
	public function index() {
		$this->set('conditions', $this->paginate($this->Conditions));
	}

/**
 * View method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function view($id = null) {
		$condition = $this->Conditions->get($id, [
			'contain' => ['Characters']
		]);
		$this->set('condition', $condition);
	}

/**
 * Add method
 *
 * @return void
 */
	public function add() {
		$condition = $this->Conditions->newEntity($this->request->data);
		if ($this->request->is('post')) {
			if ($this->Conditions->save($condition)) {
				$this->Flash->success('The condition has been saved.');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('The condition could not be saved. Please, try again.');
			}
		}
		$characters = $this->Conditions->Characters->find('list');
		$this->set(compact('condition', 'characters'));
	}

/**
 * Edit method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function edit($id = null) {
		$condition = $this->Conditions->get($id, [
			'contain' => ['Characters']
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$condition = $this->Conditions->patchEntity($condition, $this->request->data);
			if ($this->Conditions->save($condition)) {
				$this->Flash->success('The condition has been saved.');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('The condition could not be saved. Please, try again.');
			}
		}
		$characters = $this->Conditions->Characters->find('list');
		$this->set(compact('condition', 'characters'));
	}

/**
 * Delete method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function delete($id = null) {
		$condition = $this->Conditions->get($id);
		$this->request->allowMethod(['post', 'delete']);
		if ($this->Conditions->delete($condition)) {
			$this->Flash->success('The condition has been deleted.');
		} else {
			$this->Flash->error('The condition could not be deleted. Please, try again.');
		}
		return $this->redirect(['action' => 'index']);
	}
}

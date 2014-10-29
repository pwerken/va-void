<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * CharactersConditions Controller
 *
 * @property App\Model\Table\CharactersConditionsTable $CharactersConditions
 */
class CharactersConditionsController extends AppController {

/**
 * Index method
 *
 * @return void
 */
	public function index() {
		$this->paginate = [
			'contain' => ['Characters', 'Conditions']
		];
		$this->set('charactersConditions', $this->paginate($this->CharactersConditions));
	}

/**
 * View method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function view($id = null) {
		$charactersCondition = $this->CharactersConditions->get($id, [
			'contain' => ['Characters', 'Conditions']
		]);
		$this->set('charactersCondition', $charactersCondition);
	}

/**
 * Add method
 *
 * @return void
 */
	public function add() {
		$charactersCondition = $this->CharactersConditions->newEntity($this->request->data);
		if ($this->request->is('post')) {
			if ($this->CharactersConditions->save($charactersCondition)) {
				$this->Flash->success('The characters condition has been saved.');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('The characters condition could not be saved. Please, try again.');
			}
		}
		$characters = $this->CharactersConditions->Characters->find('list');
		$conditions = $this->CharactersConditions->Conditions->find('list');
		$this->set(compact('charactersCondition', 'characters', 'conditions'));
	}

/**
 * Edit method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function edit($id = null) {
		$charactersCondition = $this->CharactersConditions->get($id, [
			'contain' => []
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$charactersCondition = $this->CharactersConditions->patchEntity($charactersCondition, $this->request->data);
			if ($this->CharactersConditions->save($charactersCondition)) {
				$this->Flash->success('The characters condition has been saved.');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('The characters condition could not be saved. Please, try again.');
			}
		}
		$characters = $this->CharactersConditions->Characters->find('list');
		$conditions = $this->CharactersConditions->Conditions->find('list');
		$this->set(compact('charactersCondition', 'characters', 'conditions'));
	}

/**
 * Delete method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function delete($id = null) {
		$charactersCondition = $this->CharactersConditions->get($id);
		$this->request->allowMethod(['post', 'delete']);
		if ($this->CharactersConditions->delete($charactersCondition)) {
			$this->Flash->success('The characters condition has been deleted.');
		} else {
			$this->Flash->error('The characters condition could not be deleted. Please, try again.');
		}
		return $this->redirect(['action' => 'index']);
	}
}

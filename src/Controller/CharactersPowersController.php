<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * CharactersPowers Controller
 *
 * @property App\Model\Table\CharactersPowersTable $CharactersPowers
 */
class CharactersPowersController extends AppController {

/**
 * Index method
 *
 * @return void
 */
	public function index() {
		$this->paginate = [
			'contain' => ['Characters', 'Powers']
		];
		$this->set('charactersPowers', $this->paginate($this->CharactersPowers));
	}

/**
 * View method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function view($id = null) {
		$charactersPower = $this->CharactersPowers->get($id, [
			'contain' => ['Characters', 'Powers']
		]);
		$this->set('charactersPower', $charactersPower);
	}

/**
 * Add method
 *
 * @return void
 */
	public function add() {
		$charactersPower = $this->CharactersPowers->newEntity($this->request->data);
		if ($this->request->is('post')) {
			if ($this->CharactersPowers->save($charactersPower)) {
				$this->Flash->success('The characters power has been saved.');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('The characters power could not be saved. Please, try again.');
			}
		}
		$characters = $this->CharactersPowers->Characters->find('list');
		$powers = $this->CharactersPowers->Powers->find('list');
		$this->set(compact('charactersPower', 'characters', 'powers'));
	}

/**
 * Edit method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function edit($id = null) {
		$charactersPower = $this->CharactersPowers->get($id, [
			'contain' => []
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$charactersPower = $this->CharactersPowers->patchEntity($charactersPower, $this->request->data);
			if ($this->CharactersPowers->save($charactersPower)) {
				$this->Flash->success('The characters power has been saved.');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('The characters power could not be saved. Please, try again.');
			}
		}
		$characters = $this->CharactersPowers->Characters->find('list');
		$powers = $this->CharactersPowers->Powers->find('list');
		$this->set(compact('charactersPower', 'characters', 'powers'));
	}

/**
 * Delete method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function delete($id = null) {
		$charactersPower = $this->CharactersPowers->get($id);
		$this->request->allowMethod(['post', 'delete']);
		if ($this->CharactersPowers->delete($charactersPower)) {
			$this->Flash->success('The characters power has been deleted.');
		} else {
			$this->Flash->error('The characters power could not be deleted. Please, try again.');
		}
		return $this->redirect(['action' => 'index']);
	}
}

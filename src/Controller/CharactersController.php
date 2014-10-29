<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Characters Controller
 *
 * @property App\Model\Table\CharactersTable $Characters
 */
class CharactersController extends AppController {

/**
 * Index method
 *
 * @return void
 */
	public function index() {
		$this->paginate = [
			'contain' => ['Players', 'Factions', 'Believes', 'Groups', 'Worlds']
		];
		$this->set('characters', $this->paginate($this->Characters));
	}

/**
 * View method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function view($id = null) {
		$character = $this->Characters->get($id, [
			'contain' => ['Players', 'Factions', 'Believes', 'Groups', 'Worlds', 'Conditions', 'Powers', 'Skills', 'Spells', 'Items']
		]);
		$this->set('character', $character);
	}

/**
 * Add method
 *
 * @return void
 */
	public function add() {
		$character = $this->Characters->newEntity($this->request->data);
		if ($this->request->is('post')) {
			if ($this->Characters->save($character)) {
				$this->Flash->success('The character has been saved.');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('The character could not be saved. Please, try again.');
			}
		}
		$players = $this->Characters->Players->find('list');
		$factions = $this->Characters->Factions->find('list');
		$believes = $this->Characters->Believes->find('list');
		$groups = $this->Characters->Groups->find('list');
		$worlds = $this->Characters->Worlds->find('list');
		$conditions = $this->Characters->Conditions->find('list');
		$powers = $this->Characters->Powers->find('list');
		$skills = $this->Characters->Skills->find('list');
		$spells = $this->Characters->Spells->find('list');
		$this->set(compact('character', 'players', 'factions', 'believes', 'groups', 'worlds', 'conditions', 'powers', 'skills', 'spells'));
	}

/**
 * Edit method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function edit($id = null) {
		$character = $this->Characters->get($id, [
			'contain' => ['Conditions', 'Powers', 'Skills', 'Spells']
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$character = $this->Characters->patchEntity($character, $this->request->data);
			if ($this->Characters->save($character)) {
				$this->Flash->success('The character has been saved.');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('The character could not be saved. Please, try again.');
			}
		}
		$players = $this->Characters->Players->find('list');
		$factions = $this->Characters->Factions->find('list');
		$believes = $this->Characters->Believes->find('list');
		$groups = $this->Characters->Groups->find('list');
		$worlds = $this->Characters->Worlds->find('list');
		$conditions = $this->Characters->Conditions->find('list');
		$powers = $this->Characters->Powers->find('list');
		$skills = $this->Characters->Skills->find('list');
		$spells = $this->Characters->Spells->find('list');
		$this->set(compact('character', 'players', 'factions', 'believes', 'groups', 'worlds', 'conditions', 'powers', 'skills', 'spells'));
	}

/**
 * Delete method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function delete($id = null) {
		$character = $this->Characters->get($id);
		$this->request->allowMethod(['post', 'delete']);
		if ($this->Characters->delete($character)) {
			$this->Flash->success('The character has been deleted.');
		} else {
			$this->Flash->error('The character could not be deleted. Please, try again.');
		}
		return $this->redirect(['action' => 'index']);
	}
}

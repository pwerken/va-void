<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * CharactersSpells Controller
 *
 * @property App\Model\Table\CharactersSpellsTable $CharactersSpells
 */
class CharactersSpellsController extends AppController {

/**
 * Index method
 *
 * @return void
 */
	public function index() {
		$this->paginate = [
			'contain' => ['Characters', 'Spells']
		];
		$this->set('charactersSpells', $this->paginate($this->CharactersSpells));
	}

/**
 * View method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function view($id = null) {
		$charactersSpell = $this->CharactersSpells->get($id, [
			'contain' => ['Characters', 'Spells']
		]);
		$this->set('charactersSpell', $charactersSpell);
	}

/**
 * Add method
 *
 * @return void
 */
	public function add() {
		$charactersSpell = $this->CharactersSpells->newEntity($this->request->data);
		if ($this->request->is('post')) {
			if ($this->CharactersSpells->save($charactersSpell)) {
				$this->Flash->success('The characters spell has been saved.');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('The characters spell could not be saved. Please, try again.');
			}
		}
		$characters = $this->CharactersSpells->Characters->find('list');
		$spells = $this->CharactersSpells->Spells->find('list');
		$this->set(compact('charactersSpell', 'characters', 'spells'));
	}

/**
 * Edit method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function edit($id = null) {
		$charactersSpell = $this->CharactersSpells->get($id, [
			'contain' => []
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$charactersSpell = $this->CharactersSpells->patchEntity($charactersSpell, $this->request->data);
			if ($this->CharactersSpells->save($charactersSpell)) {
				$this->Flash->success('The characters spell has been saved.');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('The characters spell could not be saved. Please, try again.');
			}
		}
		$characters = $this->CharactersSpells->Characters->find('list');
		$spells = $this->CharactersSpells->Spells->find('list');
		$this->set(compact('charactersSpell', 'characters', 'spells'));
	}

/**
 * Delete method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function delete($id = null) {
		$charactersSpell = $this->CharactersSpells->get($id);
		$this->request->allowMethod(['post', 'delete']);
		if ($this->CharactersSpells->delete($charactersSpell)) {
			$this->Flash->success('The characters spell has been deleted.');
		} else {
			$this->Flash->error('The characters spell could not be deleted. Please, try again.');
		}
		return $this->redirect(['action' => 'index']);
	}
}

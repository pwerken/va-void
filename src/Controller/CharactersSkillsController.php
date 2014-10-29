<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * CharactersSkills Controller
 *
 * @property App\Model\Table\CharactersSkillsTable $CharactersSkills
 */
class CharactersSkillsController extends AppController {

/**
 * Index method
 *
 * @return void
 */
	public function index() {
		$this->paginate = [
			'contain' => ['Characters', 'Skills']
		];
		$this->set('charactersSkills', $this->paginate($this->CharactersSkills));
	}

/**
 * View method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function view($id = null) {
		$charactersSkill = $this->CharactersSkills->get($id, [
			'contain' => ['Characters', 'Skills']
		]);
		$this->set('charactersSkill', $charactersSkill);
	}

/**
 * Add method
 *
 * @return void
 */
	public function add() {
		$charactersSkill = $this->CharactersSkills->newEntity($this->request->data);
		if ($this->request->is('post')) {
			if ($this->CharactersSkills->save($charactersSkill)) {
				$this->Flash->success('The characters skill has been saved.');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('The characters skill could not be saved. Please, try again.');
			}
		}
		$characters = $this->CharactersSkills->Characters->find('list');
		$skills = $this->CharactersSkills->Skills->find('list');
		$this->set(compact('charactersSkill', 'characters', 'skills'));
	}

/**
 * Edit method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function edit($id = null) {
		$charactersSkill = $this->CharactersSkills->get($id, [
			'contain' => []
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$charactersSkill = $this->CharactersSkills->patchEntity($charactersSkill, $this->request->data);
			if ($this->CharactersSkills->save($charactersSkill)) {
				$this->Flash->success('The characters skill has been saved.');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('The characters skill could not be saved. Please, try again.');
			}
		}
		$characters = $this->CharactersSkills->Characters->find('list');
		$skills = $this->CharactersSkills->Skills->find('list');
		$this->set(compact('charactersSkill', 'characters', 'skills'));
	}

/**
 * Delete method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function delete($id = null) {
		$charactersSkill = $this->CharactersSkills->get($id);
		$this->request->allowMethod(['post', 'delete']);
		if ($this->CharactersSkills->delete($charactersSkill)) {
			$this->Flash->success('The characters skill has been deleted.');
		} else {
			$this->Flash->error('The characters skill could not be deleted. Please, try again.');
		}
		return $this->redirect(['action' => 'index']);
	}
}

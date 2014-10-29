<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Skills Controller
 *
 * @property App\Model\Table\SkillsTable $Skills
 */
class SkillsController extends AppController {

/**
 * Index method
 *
 * @return void
 */
	public function index() {
		$this->paginate = [
			'contain' => ['Manatypes']
		];
		$this->set('skills', $this->paginate($this->Skills));
	}

/**
 * View method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function view($id = null) {
		$skill = $this->Skills->get($id, [
			'contain' => ['Manatypes', 'Characters']
		]);
		$this->set('skill', $skill);
	}

/**
 * Add method
 *
 * @return void
 */
	public function add() {
		$skill = $this->Skills->newEntity($this->request->data);
		if ($this->request->is('post')) {
			if ($this->Skills->save($skill)) {
				$this->Flash->success('The skill has been saved.');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('The skill could not be saved. Please, try again.');
			}
		}
		$manatypes = $this->Skills->Manatypes->find('list');
		$characters = $this->Skills->Characters->find('list');
		$this->set(compact('skill', 'manatypes', 'characters'));
	}

/**
 * Edit method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function edit($id = null) {
		$skill = $this->Skills->get($id, [
			'contain' => ['Characters']
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$skill = $this->Skills->patchEntity($skill, $this->request->data);
			if ($this->Skills->save($skill)) {
				$this->Flash->success('The skill has been saved.');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('The skill could not be saved. Please, try again.');
			}
		}
		$manatypes = $this->Skills->Manatypes->find('list');
		$characters = $this->Skills->Characters->find('list');
		$this->set(compact('skill', 'manatypes', 'characters'));
	}

/**
 * Delete method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function delete($id = null) {
		$skill = $this->Skills->get($id);
		$this->request->allowMethod(['post', 'delete']);
		if ($this->Skills->delete($skill)) {
			$this->Flash->success('The skill has been deleted.');
		} else {
			$this->Flash->error('The skill could not be deleted. Please, try again.');
		}
		return $this->redirect(['action' => 'index']);
	}
}

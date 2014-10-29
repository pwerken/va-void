<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Spells Controller
 *
 * @property App\Model\Table\SpellsTable $Spells
 */
class SpellsController extends AppController {

/**
 * Index method
 *
 * @return void
 */
	public function index() {
		$this->set('spells', $this->paginate($this->Spells));
	}

/**
 * View method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function view($id = null) {
		$spell = $this->Spells->get($id, [
			'contain' => ['Characters']
		]);
		$this->set('spell', $spell);
	}

/**
 * Add method
 *
 * @return void
 */
	public function add() {
		$spell = $this->Spells->newEntity($this->request->data);
		if ($this->request->is('post')) {
			if ($this->Spells->save($spell)) {
				$this->Flash->success('The spell has been saved.');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('The spell could not be saved. Please, try again.');
			}
		}
		$characters = $this->Spells->Characters->find('list');
		$this->set(compact('spell', 'characters'));
	}

/**
 * Edit method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function edit($id = null) {
		$spell = $this->Spells->get($id, [
			'contain' => ['Characters']
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$spell = $this->Spells->patchEntity($spell, $this->request->data);
			if ($this->Spells->save($spell)) {
				$this->Flash->success('The spell has been saved.');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('The spell could not be saved. Please, try again.');
			}
		}
		$characters = $this->Spells->Characters->find('list');
		$this->set(compact('spell', 'characters'));
	}

/**
 * Delete method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function delete($id = null) {
		$spell = $this->Spells->get($id);
		$this->request->allowMethod(['post', 'delete']);
		if ($this->Spells->delete($spell)) {
			$this->Flash->success('The spell has been deleted.');
		} else {
			$this->Flash->error('The spell could not be deleted. Please, try again.');
		}
		return $this->redirect(['action' => 'index']);
	}
}

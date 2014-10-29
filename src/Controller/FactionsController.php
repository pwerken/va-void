<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Factions Controller
 *
 * @property App\Model\Table\FactionsTable $Factions
 */
class FactionsController extends AppController {

/**
 * Index method
 *
 * @return void
 */
	public function index() {
		$this->set('factions', $this->paginate($this->Factions));
	}

/**
 * View method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function view($id = null) {
		$faction = $this->Factions->get($id, [
			'contain' => ['Characters']
		]);
		$this->set('faction', $faction);
	}

/**
 * Add method
 *
 * @return void
 */
	public function add() {
		$faction = $this->Factions->newEntity($this->request->data);
		if ($this->request->is('post')) {
			if ($this->Factions->save($faction)) {
				$this->Flash->success('The faction has been saved.');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('The faction could not be saved. Please, try again.');
			}
		}
		$this->set(compact('faction'));
	}

/**
 * Edit method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function edit($id = null) {
		$faction = $this->Factions->get($id, [
			'contain' => []
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$faction = $this->Factions->patchEntity($faction, $this->request->data);
			if ($this->Factions->save($faction)) {
				$this->Flash->success('The faction has been saved.');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('The faction could not be saved. Please, try again.');
			}
		}
		$this->set(compact('faction'));
	}

/**
 * Delete method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function delete($id = null) {
		$faction = $this->Factions->get($id);
		$this->request->allowMethod(['post', 'delete']);
		if ($this->Factions->delete($faction)) {
			$this->Flash->success('The faction has been deleted.');
		} else {
			$this->Flash->error('The faction could not be deleted. Please, try again.');
		}
		return $this->redirect(['action' => 'index']);
	}
}

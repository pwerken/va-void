<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Believes Controller
 *
 * @property App\Model\Table\BelievesTable $Believes
 */
class BelievesController extends AppController {

/**
 * Index method
 *
 * @return void
 */
	public function index() {
		$this->set('believes', $this->paginate($this->Believes));
	}

/**
 * View method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function view($id = null) {
		$belief = $this->Believes->get($id, [
			'contain' => ['Characters']
		]);
		$this->set('belief', $belief);
	}

/**
 * Add method
 *
 * @return void
 */
	public function add() {
		$belief = $this->Believes->newEntity($this->request->data);
		if ($this->request->is('post')) {
			if ($this->Believes->save($belief)) {
				$this->Flash->success('The belief has been saved.');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('The belief could not be saved. Please, try again.');
			}
		}
		$this->set(compact('belief'));
	}

/**
 * Edit method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function edit($id = null) {
		$belief = $this->Believes->get($id, [
			'contain' => []
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$belief = $this->Believes->patchEntity($belief, $this->request->data);
			if ($this->Believes->save($belief)) {
				$this->Flash->success('The belief has been saved.');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('The belief could not be saved. Please, try again.');
			}
		}
		$this->set(compact('belief'));
	}

/**
 * Delete method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function delete($id = null) {
		$belief = $this->Believes->get($id);
		$this->request->allowMethod(['post', 'delete']);
		if ($this->Believes->delete($belief)) {
			$this->Flash->success('The belief has been deleted.');
		} else {
			$this->Flash->error('The belief could not be deleted. Please, try again.');
		}
		return $this->redirect(['action' => 'index']);
	}
}

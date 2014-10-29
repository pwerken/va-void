<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Powers Controller
 *
 * @property App\Model\Table\PowersTable $Powers
 */
class PowersController extends AppController {

/**
 * Index method
 *
 * @return void
 */
	public function index() {
		$this->set('powers', $this->paginate($this->Powers));
	}

/**
 * View method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function view($id = null) {
		$power = $this->Powers->get($id, [
			'contain' => ['Characters']
		]);
		$this->set('power', $power);
	}

/**
 * Add method
 *
 * @return void
 */
	public function add() {
		$power = $this->Powers->newEntity($this->request->data);
		if ($this->request->is('post')) {
			if ($this->Powers->save($power)) {
				$this->Flash->success('The power has been saved.');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('The power could not be saved. Please, try again.');
			}
		}
		$characters = $this->Powers->Characters->find('list');
		$this->set(compact('power', 'characters'));
	}

/**
 * Edit method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function edit($id = null) {
		$power = $this->Powers->get($id, [
			'contain' => ['Characters']
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$power = $this->Powers->patchEntity($power, $this->request->data);
			if ($this->Powers->save($power)) {
				$this->Flash->success('The power has been saved.');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('The power could not be saved. Please, try again.');
			}
		}
		$characters = $this->Powers->Characters->find('list');
		$this->set(compact('power', 'characters'));
	}

/**
 * Delete method
 *
 * @param string $id
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException
 */
	public function delete($id = null) {
		$power = $this->Powers->get($id);
		$this->request->allowMethod(['post', 'delete']);
		if ($this->Powers->delete($power)) {
			$this->Flash->success('The power has been deleted.');
		} else {
			$this->Flash->error('The power could not be deleted. Please, try again.');
		}
		return $this->redirect(['action' => 'index']);
	}
}

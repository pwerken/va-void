<?php
namespace App\Controller;

class TeachingsController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$this->mapMethod('charactersIndex',  [ 'referee', 'user' ], true);
		$this->mapMethod('charactersAdd',    [ 'infobalie'       ]);
		$this->mapMethod('charactersDelete', [ 'infobalie'       ]);
		$this->mapMethod('charactersEdit',   [ 'infobalie'       ]);
		$this->mapMethod('charactersView',   [ 'referee', 'user' ], true);

		$this->Crud->mapAction('charactersQueue',
			[ 'className' => 'Crud.View'
			, 'auth' => [ 'referee' ]
			, 'findMethod' => 'withContain'
			]);
	}

	public function charactersEdit($student_id)
	{
		$action = 'charactersEdit';
		if(!$this->Teachings->exists(['student_id = ' => $student_id])) {
			$action = 'charactersAdd';
			$this->request->data('student_id', $student_id);
		}

		if(array_key_exists('plin', $this->request->data)
		|| array_key_exists('chin', $this->request->data))
		{
			$plin = $this->request->data('plin');
			$chin = $this->request->data('chin');

			if($plin || $chin) {
				$chars = $this->loadModel('Characters');
				$char = $chars->findByPlayerIdAndChin($plin, $chin)->first();
				$this->request->data('teacher_id', $char ? $char->id : -1);
			} else {
				$this->request->data('teacher_id', null);
			}
		}
		unset($this->request->data['plin']);
		unset($this->request->data['chin']);

		$this->dataNameToId('events', 'updated');
		$this->dataNameToId('events', 'started');

		return $this->Crud->execute($action);
	}

	public function charactersQueue($student_id)
	{
		$this->queueLammy();
	}

	public function paginate($query = null, array $settings = [])
	{
		if(isset($this->viewVars['parent'])) {
			$query->where(['teacher_id' => $this->viewVars['parent']->id]);
		}
		return $query->all();
	}

}

<?php
namespace App\Controller;

use App\Model\Entity\JsonEntity;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Network\Exception\BadRequestException;
use Cake\ORM\ResultSet;
use Crud\Error\Exception\ValidationException;

class AppController
	extends Controller
{

	use \Crud\Controller\ControllerTrait;

	public $helpers = [ 'Date' ];

	public function initialize()
	{
		parent::initialize();

		$this->loadComponent('RequestHandler');
		$this->loadComponent('Flash');
		$this->loadComponent('Crud.Crud',
			[ 'listeners' => ['Crud.RelatedModels'] ]
		);
		$this->loadComponent('Auth',
			[ 'storage' => 'Session'
			, 'authenticate' =>
				[ 'Form' =>
					[ 'userModel' => 'Players'
					, 'fields' => [ 'username' => 'id' ]
					]
				, 'ADmad/JwtAuth.Jwt' =>
					[ 'userModel' => 'Players'
					, 'fields' => [ 'username' => 'id' ]
					, 'parameter' => 'token'
					, 'queryDatasource' => true
				]	]
			, 'authorize' => ['Controller']
			, 'unauthorizedRedirect' => false
			, 'checkAuthIn' => 'Controller.initialize'
			, 'loginAction' => '/api/login'
			, 'logoutRedirect' => '/'
			]
		);

		if($this->request->is('json')) {
			$this->viewBuilder()->className('Api');
			if(!$this->request->is('post'))
				$this->request->data = $this->request->input('json_decode', 1);

			$error = json_last_error();
			if($error != JSON_ERROR_NONE) {
				$msg = sprintf("Failed to parse json, error: %s '%s'"
								, $error, json_last_error_msg());
				throw new BadRequestException($msg);
			}

			$this->Crud->on('afterSave', function(Event $event) {
				if(!$event->subject->success)
					throw new ValidationException($event->subject->entity);

				if($event->subject->created) {
					$this->response->statusCode(302);
					//FIXME redirect to location of new entity
					return $this->response;
				}

				$this->response->statusCode(303);
				$this->response->location($this->request->here);
				return $this->response;
			});
			$this->Crud->on('beforeDelete', function(Event $event) {
				if(!$this->canDelete($event->subject->entity))
					throw new BadRequestException("Entity is referenced", 412);
			});
			$this->Crud->on('afterDelete', function(Event $event) {
				if(!$event->subject->success)
					throw new BadRequestException('Failed to delete');

				$this->response->statusCode(204);
				return $this->response;
			});
			$this->Crud->on('beforeRedirect', function(Event $event) {
				if(method_exists($this->Crud->action(), 'publishViewVar'))
					$this->Crud->action()->publishViewVar($event);
				return $this->render();
			});
        }
	}

	public function paginate($query = null)
	{
		if($this->request->is('json'))
			return $query->all();

		return parent::paginate($query);
	}

	public function isAuthorized($user)
	{
		$auths = $this->Crud->action()->config('auth') ?: ['super'];
		foreach($auths as $level) {
			if($this->hasAuth($level))
				return true;
		}
		return false;
	}

	protected function argsOrder($from, $to, $array)
	{
		$lookup = array_flip(str_split($from));
		$output = [];
		foreach(str_split($to) as $key) {
			$output[] = $array[$lookup[$key]];
		}
		return $output;
	}
	protected function argsCharId($args)
	{
		$this->loadModel('Characters');
		if(count($args) >= 2) {
			$plin = array_shift($args);
			$chin = array_shift($args);
			$char = $this->Characters->plinChin($plin, $chin)->id;
			array_unshift($args, $char);
		}
		return $args;
	}

	protected function canDelete($entity)
	{
		return true;
	}

	protected function hasAuth($level)
	{
		if(strcasecmp($level, $this->Auth->user('role')) == 0)
			return true;

		switch(strtolower($level)) {
		case 'user':		return $this->hasAuthUser();
		case 'player':		return $this->hasAuth('referee');
		case 'referee':		return $this->hasAuth('infobalie');
		case 'infobalie':	return $this->hasAuth('super');
		}

		return false;
	}
	protected function hasAuthUser($id = null)
	{
		if($id === null)
			$id = (int)$this->request->param('plin');
		return $this->Auth->user('id') == $id;
	}

	protected function mapMethod($action, $auth, $contain = [])
	{
		$className = ucfirst($action);
		$className = preg_replace('/.*([A-Z][a-z]+)/', 'Crud.\\1', $className);

		if(is_string($auth))
			$auth = [$auth];
		if(empty($auth))
			$auth = ['super'];

		$this->Crud->mapAction($action, compact('className','auth','contain'));
	}

}

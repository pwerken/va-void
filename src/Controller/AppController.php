<?php
namespace App\Controller;

use App\AuthState;
use App\Model\Entity\JsonEntity;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Network\Exception\BadRequestException;
use Cake\ORM\ResultSet;
use Cake\Utility\Inflector;
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
			[ 'listeners' => ['Crud.RelatedModels']
			]);
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
			, 'loginAction' => '/auth/login'
			, 'logoutRedirect' => '/'
			]);

		$this->viewBuilder()->className('Api');
		if(!$this->request->is('post'))
			$this->request->data = $this->request->input('json_decode',1) ?: [];

		$error = json_last_error();
		if($error != JSON_ERROR_NONE) {
			$msg = sprintf("Failed to parse json, error: %s '%s'"
							, $error, json_last_error_msg());
			throw new BadRequestException($msg);
		}

	}

	public function implementedEvents()
	{
		$events = parent::implementedEvents();
		$events['Crud.beforeHandle']   = 'CrudBeforeHandle';
		$events['Crud.afterSave']      = 'CrudAfterSave';
		$events['Crud.beforeDelete']   = 'CrudBeforeDelete';
		$events['Crud.afterDelete']    = 'CrudAfterDelete';
		$events['Crud.beforeRedirect'] = 'CrudBeforeRedirect';
		return $events;
	}

	public function isAuthorized($user)
	{
		AuthState::setAuth($this->Auth, $this->hasAuthUser());

		$auths = $this->Crud->action()->config('auth') ?: ['super'];
		foreach($auths as $role) {
			if(AuthState::hasRole($role))
				return true;
		}
		return false;
	}

	public function paginate($query = null)
	{
		if($this->request->is('json'))
			return $query->all();

		return parent::paginate($query);
	}

	public function CrudBeforeHandle(Event $event)
	{
		switch($event->subject->action) {
		case 'charactersDelete':
		case 'charactersEdit':
		case 'charactersView':
			$event->subject->args = $this->argsCharId($event->subject->args);
			break;
		default:
			break;
		}
	}
	public function CrudAfterSave(Event $event)
	{
		if(!$event->subject->success)
			throw new ValidationException($event->subject->entity);

		if($event->subject->created) {
			$this->response->statusCode(201);
			//FIXME redirect to location of new entity
			return $this->response;
		}

		$action = $this->request->params['action'];
		$action = lcfirst(Inflector::camelize(substr($action, 0, -4)."_view"));
		$this->request->params['_method'] = 'GET';
		$this->request->params['action'] = $action;
		$this->Crud->beforeFilter($event);
		return $this->invokeAction();
	}
	public function CrudBeforeDelete(Event $event)
	{
		if(!$this->canDelete($event->subject->entity))
			throw new BadRequestException("Entity is referenced", 422);
	}
	public function CrudAfterDelete(Event $event)
	{
		if(!$event->subject->success)
			throw new BadRequestException('Failed to delete');

		$this->response->statusCode(204);
		return $this->response;
	}
	public function CrudBeforeRedirect(Event $event)
	{
		if(method_exists($this->Crud->action(), 'publishViewVar'))
			$this->Crud->action()->publishViewVar($event);
		return $this->render();
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

	protected function hasAuthUser($id = null)
	{
		$id = ($id ?: $this->request->param('plin'));
		return ($this->Auth->user('id') == $id);
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

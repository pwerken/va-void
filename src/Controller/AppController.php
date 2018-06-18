<?php
namespace App\Controller;

use App\Utility\AuthState;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Error\ErrorHandler;
use Cake\Event\Event;
use Cake\Network\Exception\BadRequestException;
use Cake\Utility\Inflector;
use Crud\Error\Exception\ValidationException;
use Crud\Controller\ControllerTrait;
use PDOException;

class AppController
	extends Controller
{
	use ControllerTrait;

	public $helpers = [ 'Date' ];

	protected $searchFields = [ ];

	// Can be overriden to disable json output.
	public static $jsonResponse = true;

	public function initialize()
	{
		parent::initialize();

		$this->loadComponent('Crud.Crud',
			[ 'listeners' => ['Crud.RelatedModels']
			]);
		$this->loadComponent('Auth',
			[ 'storage' => 'Memory'
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
					, 'unauthenticatedException' => '\Cake\Network\Exception\ForbiddenException'
				]	]
			, 'authorize' => ['Controller']
			, 'unauthorizedRedirect' => false
			, 'checkAuthIn' => 'Controller.initialize'
			, 'loginAction' => '/auth/login'
			, 'logoutRedirect' => '/'
			]);

		if(static::$jsonResponse) {
			$this->viewBuilder()->className('Api');

			$arr = ['exceptionRenderer' => 'App\Error\ApiExceptionRenderer']
				+ Configure::read('Error');
			(new ErrorHandler($arr))->register();

			$this->response->compress();
		}

		if(!$this->request->is('POST')) {
			$this->request->data = $this->request->input(function($input) {
				if(empty($input))
					return [];
				$json = json_decode($input, true);
				$error = json_last_error();
				if($error != JSON_ERROR_NONE) {
					$msg = sprintf("Failed to parse json, error: %s '%s'"
									, $error, json_last_error_msg());
					throw new BadRequestException($msg);
				}
				return $json;
			});
		}
	}

	public function implementedEvents()
	{
		$events = parent::implementedEvents();
		$events['Crud.beforeHandle']   = 'CrudBeforeHandle';
		$events['Crud.afterSave']      = 'CrudAfterSave';
		$events['Crud.afterDelete']    = 'CrudAfterDelete';
		$events['Crud.beforeRedirect'] = 'CrudBeforeRedirect';
		return $events;
	}

	public function isAuthorized($user)
	{
		AuthState::setAuth($this->Auth, $this->wantAuthUser());

		$auths = $this->Crud->action()->config('auth') ?: ['super'];
		foreach($auths as $role) {
			if($this->hasAuth($role))
				return true;
		}
		return false;
	}

	protected function hasAuth($role)
	{
		return AuthState::hasRole($role);
	}

	protected function wantAuthUser()
	{
		return $this->request->param('plin');
	}

	public function paginate($query = null, array $settings = [])
	{
		$action = $this->request->action;
		$nested = strcmp(substr($action, -5, 5), 'Index') === 0;
		if($nested && isset($this->viewVars['parent'])) {
			$key = Inflector::singularize(substr($action, 0, -5)).'_id';
			$query->where([$key => $this->viewVars['parent']->id]);
		}
		return $query->all();
	}

	public function CrudBeforeHandle(Event $event)
	{
		$action = $this->request->action;

		if(strcmp(substr($action, -3, 3), 'add') == 0
		|| strcmp(substr($action, -4, 4), 'edit') == 0) {
			# remove stuff that's not in the db
			foreach($this->request->data as $key => $value) {
				if(!$this->loadModel()->hasField($key))
					unset($this->request->data[$key]);
			}
			# these can never be set through the rest-api
			unset($this->request->data['created']);
			unset($this->request->data['creator_id']);
			unset($this->request->data['modified']);
			unset($this->request->data['modifier_id']);
		}
		if(strcmp(substr($action, -5, 5), 'Index') == 0) {
			$model = ucfirst(substr($action, 0, -5));
			$parent = $this->loadModel($model)->get($event->subject->args[0]);
			$this->set('parent', $parent);
		}
	}
	public function CrudAfterSave(Event $event)
	{
		if(!$event->subject->success)
			throw new ValidationException($event->subject->entity);

		if(!$event->subject->created) {
			$action = 'view';
			$oldAction = $this->request->params['action'];
			if(strcmp(substr($oldAction, -4, 4), 'Edit') == 0) {
				$action = substr($oldAction, 0, -4) . 'View';
			}
			return $this->Crud->execute($action);
		}

		$this->response->statusCode(201);
		$this->response->location($event->subject->entity->refresh()->getUrl());
		return $this->response;
	}
	public function CrudAfterDelete(Event $event)
	{
		if(!$event->subject->success) {
			throw new ValidationException($event->subject->entity);
		}

		$this->response->statusCode(204);
		return $this->response;
	}
	public function CrudBeforeRedirect(Event $event)
	{
		if(method_exists($this->Crud->action(), 'publishViewVar'))
			$this->Crud->action()->publishViewVar($event);
		return $this->render();
	}

	protected function mapMethod($action, $auth = [], $contain = false)
	{
		$className = ucfirst($action);
		$className = preg_replace('/^.*([A-Z][a-z]+)$/', 'Crud.\\1', $className);

		if(is_string($auth))
			$auth = [$auth];
		if(empty($auth))
			$auth = ['super'];

		$config = compact('className','auth');
		if($contain)
			$config['findMethod'] = 'withContain';

		$this->Crud->mapAction($action, $config);
	}

	protected function setResponseModified()
	{
		$model = $this->modelClass;
		$query = $this->$model->find();
		$query->select(['last' => $query->func()->max("$model.modified")]);
		$modified = $this->doRawQuery($query)[0][0];
		$modified = ($modified ?: new \DateTime('2000-01-01'));
		$this->response = $this->response->withModified($modified);

		return $this->response->checkNotModified($this->request);
	}

	protected function queueLammy()
	{
		$this->Crud->on('beforeRender', function ($event) {
			$table = $this->loadModel('lammies');
			$entity = $event->subject()->entity;
			$table->save($table->newEntity()->set('target', $entity));
			$event->subject()->entity = 1;
		});

		$this->Crud->execute();
	}

	protected function doRawQuery($query)
	{
		$orWhere = [];
		$params = [];
		foreach(explode(' ', $this->request->query('q')) as $q) {
			foreach($this->searchFields as $field) {
				$orWhere[] = "$field LIKE ?";
				$params[] = "%$q%";
			}
			$query->andWhere(function($exp) use ($orWhere) {
				return $exp->or_($orWhere);
			});
		}

		$conn = ConnectionManager::get('default');
		return $conn->execute($query->sql(), $params)->fetchAll();
	}
	protected function doRawIndex($query, $class, $url, $id = 'id')
	{
		$content = [];
		foreach($this->doRawQuery($query) as $row) {
			$content[] =
				[ 'class' => $class
				, 'url'   => $url . $row[0]
				, $id     => (int)$row[0]
				, 'name'  => $row[1]
				];
		}

		$this->set('_serialize',
			[ 'class' => 'List'
			, 'url' => '/' . rtrim($this->request->url, '/')
			, 'list' => $content
			]);
	}

	protected function dataNameToId($table, $field)
	{
		if(!array_key_exists($field, $this->request->data)) {
			return null;
		}

		$name = $this->request->data($field);
		unset($this->request->data[$field]);
		if(empty($name)) {
			$name = "-";
		}

		$model = $this->loadModel($table);
		$ids = $model->findByName($name)->select('id', true)
					->hydrate(false)->all();
		if($ids->count() == 0) {
			$this->request->data($field.'_id', -1);
		} else {
			$this->request->data($field.'_id', $ids->first()['id']);
		}
		return $name;
	}

	protected function dataNameToIdAndAddIfMissing($table, $field)
	{
		$name = $this->dataNameToId($table, $field);
		$id = $this->request->data($field.'_id');
		if($id < 0) {
			$model = $this->loadModel($table);
			$obj = $model->newEntity();
			$obj->name = $name;
			$model->save($obj);
			$this->request->data($field.'_id', $obj->id);
		}
		return $name;
	}
}

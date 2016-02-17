<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use App\Model\Entity\JsonEntity;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Network\Exception\BadRequestException;
use Cake\ORM\ResultSet;
use Crud\Error\Exception\ValidationException;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

	use \Crud\Controller\ControllerTrait;

	public $helpers = [ 'Date' ];

	/**
	 * Initialization hook method.
	 *
	 * Use this method to add common initialization code like loading components.
	 *
	 * e.g. `$this->loadcomponent('security');`
	 *
	 * @return void
	 */
	public function initialize()
	{
		parent::initialize();

		$this->loadComponent('RequestHandler');
		$this->loadComponent('Flash');
		$this->loadComponent('Crud.Crud',
			[ 'listeners' => ['Crud.RelatedModels'] ]
		);
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
				]	]
			, 'unauthorizedRedirect' => false
			, 'checkAuthIn' => 'Controller.initialize'
			, 'loginAction' => '/api/login'
			]
		);

		if($this->request->is('json')) {
			$this->viewBuilder()->className('Api');
			if(!$this->request->is('post'))
				$this->request->data = $this->request->input('json_decode', true);

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
					$this->response->statusCode(201);
					return $this->response;
				}
			});
			$this->Crud->on('afterDelete', function(Event $event) {
				if(!$event->subject->success)
					throw new BadRequestException('Faied to delete');

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

	/**
	 * Disable pagination.
	 *
	 * @param \Cake\ORM\Query|null $qyery Query to execute
	 * @return \Cake\ORM\ResultSet Query results
	 * paginate
	 */
	public function paginate($query = null)
	{
		if($this->request->is('json'))
			return $query->all();

		return parent::paginate($query);
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
}

<?php
namespace App\Test\TestSuite;

use Cake\TestSuite\IntegrationTestCase;

class AuthIntegrationTestCase
	extends IntegrationTestCase
{

	public $fixtures = [ 'app.players' ];

	protected $token = NULL;

	public function assertArrayKeyValue($key, $value, $array)
	{
		$this->assertArrayHasKey($key, $array);
		$this->assertEquals($value, $array[$key]);
	}

	public function assertGet($url, $code = 200)
	{
		$this->setConfigRequest();
		$this->get($url);
		$this->assertResponseCode($code);
	}

	public function assertPost($url, $data = [], $code = 200)
	{
		$this->setConfigRequest();
		$this->post($url, $data);
		$this->assertResponseCode($code);
	}

	public function assertPatch($url, $data = [], $code = 200)
	{
		$this->setConfigRequest();
		$this->patch($url, $data);
		$this->assertResponseCode($code);
	}

	public function assertPut($url, $data = [], $code = 200)
	{
		$this->setConfigRequest();
		$this->put($url, $data);
		$this->assertResponseCode($code);
	}

	public function assertDelete($url, $code = 200)
	{
		$this->setConfigRequest();
		$this->delete($url);
		$this->assertResponseCode($code);
	}

	public function assertHead($url, $code = 200)
	{
		$this->setConfigRequest();
		$this->head($url);
		$this->assertResponseCode($code);
	}

	public function assertOptions($url, $code = 200)
	{
		$this->setConfigRequest();
		$this->options($url);
		$this->assertResponseCode($code);
	}

	protected function jsonBody($field = NULL)
	{
		$data = json_decode($this->_response->getBody(), true);
		if(is_null($field))
			return $data;

		$this->assertArrayHasKey($field, $data);
		return $data[$field];
	}

	protected function withoutAuth()
	{
		$this->token = NULL;
	}

	protected function withAuthPlayer()
	{
		return $this->loginAs(1);
	}

	protected function withAuthReadOnly()
	{
		return $this->loginAs(2);
	}

	protected function withAuthReferee()
	{
		return $this->loginAs(3);
	}

	protected function withAuthInfobalie()
	{
		return $this->loginAs(4);
	}

	protected function withAuthSuper()
	{
		return $this->loginAs(5);
	}

	private function loginAs($id)
	{
		$this->withoutAuth();

		$this->put('/auth/login', ['id' => $id, 'password' => 'password']);
		$this->assertResponseCode(200); # oke

		$this->token = $this->jsonBody('token');

		return $id;
	}

	private function setConfigRequest()
	{
		$this->configRequest(
			[ 'headers' =>
				[ 'Accept' => 'application/json'
			]	]);

		if(!is_null($this->token)) {
			$this->configRequest(
				[ 'headers' =>
					[ 'Authorization' => 'Bearer ' . $this->token
				]	]);
		}
	}
}

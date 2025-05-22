<?php
declare(strict_types=1);

namespace App\Test\TestSuite;

use Cake\TestSuite\IntegrationTestTrait;

class AuthIntegrationTestCase extends TestCase
{
    use IntegrationTestTrait;

    public array $fixtures = [
        'app.Attributes',
        'app.AttributesItems',
        'app.Characters',
        'app.CharactersConditions',
        'app.CharactersPowers',
        'app.CharactersSkills',
        'app.Conditions',
        'app.Events',
        'app.Factions',
        'app.Items',
        'app.Lammies',
        'app.Manatypes',
        'app.Players',
        'app.Powers',
        'app.Skills',
        'app.Teachings',
    ];

    protected $token = null;

    public function assertArrayKeyValue(string $key, mixed $value, array $array, string $message = '')
    {
        $this->assertArrayHasKey($key, $array, $message);
        $this->assertEquals($value, $array[$key], $message);
    }

    public function assertGet(string $url, int $code = 200, string $message = '')
    {
        $this->setConfigRequest();
        $this->get($url);
        $this->assertResponseCode($code, $message);

        return $this->jsonBody();
    }

    public function assertPost(string $url, array|string $data = '', int $code = 200, string $message = '')
    {
        $this->setConfigRequest('application/x-www-form-urlencoded');
        $this->post($url, $data);
        $this->assertResponseCode($code, $message);

        return $this->jsonBody();
    }

    public function assertPatch(string $url, array $data = [], int $code = 200, string $message = '')
    {
        $this->setConfigRequest();
        $this->patch($url, json_encode($data));
        $this->assertResponseCode($code, $message);
    }

    public function assertPut(string $url, array $data = [], int $code = 200, string $message = '')
    {
        $this->setConfigRequest();
        $this->put($url, json_encode($data));
        $this->assertResponseCode($code, $message);

        return $this->jsonBody();
    }

    public function assertDelete(string $url, int $code = 200, string $message = '')
    {
        $this->setConfigRequest();
        $this->delete($url);
        $this->assertResponseCode($code, $message);
    }

    public function assertHead(string $url, int $code = 200, string $message = '')
    {
        $this->setConfigRequest();
        $this->head($url);
        $this->assertResponseCode($code, $message);
    }

    public function assertOptions(string $url, int $code = 200, string $message = '')
    {
        $this->setConfigRequest();
        $this->options($url);
        $this->assertResponseCode($code, $message);
    }

    protected function jsonBody(?string $field = null)
    {
        $data = json_decode((string)$this->_response->getBody(), true);
        if (is_null($field)) {
            return $data;
        }

        $this->assertArrayHasKey($field, $data);

        return $data[$field];
    }

    protected function withoutAuth()
    {
        $this->token = null;
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

    private function loginAs(int $id)
    {
        $this->withoutAuth();

        $this->assertPut(
            '/auth/login',
            ['id' => $id, 'password' => 'password'],
            200,
            'authentication succesful?',
        );

        $this->token = $this->jsonBody('token');
        $this->assertNotNull($this->token, 'JWT Token set?');

        return $id;
    }

    private function setConfigRequest(string $content = 'application/json')
    {
        if ($this->token !== null) {
            $this->configRequest([
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => $content,
                    'Authorization' => 'Bearer ' . $this->token,
                ],
            ]);

            return;
        }

        $this->configRequest([
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => $content,
            ],
        ]);
    }
}

<?php
declare(strict_types=1);

namespace App\Test\TestSuite;

use Cake\I18n\DateTime;
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

    protected ?string $token = null;

    protected static int $PLIN_PLAYER = 1;
    protected static int $PLIN_READONLY = 2;
    protected static int $PLIN_REFEREE = 3;
    protected static int $PLIN_INFOBALIE = 4;
    protected static int $PLIN_SUPER = 5;

    public function assertGet(string $url, int $code = 200, string $message = '')
    {
        $this->setConfigRequest();
        $this->now = null;
        $this->get($url);
        $this->assertResponseCode($code, $message);

        return $this->jsonBody();
    }

    public function assertPost(string $url, array|string $data = '', int $code = 200, string $message = '')
    {
        $this->setConfigRequest('application/x-www-form-urlencoded');
        $this->now = new DateTime('UTC');
        $this->post($url, $data);
        $this->assertResponseCode($code, $message);

        return $this->jsonBody();
    }

    public function assertPatch(string $url, array $data = [], int $code = 200, string $message = '')
    {
        $this->setConfigRequest();
        $this->now = new DateTime('UTC');
        $this->patch($url, json_encode($data));
        $this->assertResponseCode($code, $message);
    }

    public function assertPut(string $url, array $data = [], int $code = 200, string $message = '')
    {
        $this->setConfigRequest();
        $this->now = new DateTime('UTC');
        $this->put($url, json_encode($data));
        $this->assertResponseCode($code, $message);

        return $this->jsonBody();
    }

    public function assertDelete(string $url, int $code = 200, string $message = '')
    {
        $this->setConfigRequest();
        $this->now = new DateTime('UTC');
        $this->delete($url);
        $this->assertResponseCode($code, $message);
    }

    public function assertHead(string $url, int $code = 200, string $message = '')
    {
        $this->setConfigRequest();
        $this->now = null;
        $this->head($url);
        $this->assertResponseCode($code, $message);
    }

    public function assertOptions(string $url, int $code = 200, string $message = '')
    {
        $this->setConfigRequest();
        $this->now = null;
        $this->options($url);
        $this->assertResponseCode($code, $message);
    }

    public function assertErrorsResponse(string $url, array $response): array
    {
        $this->assertArrayKeyValue('class', 'Error', $response);
        $this->assertArrayKeyValue('code', 422, $response);
        $this->assertArrayKeyValue('url', $url, $response);
        $this->assertArrayHasKey('errors', $response);

        return $response['errors'];
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
        return $this->loginAs(self::$PLIN_PLAYER);
    }

    protected function withAuthReadOnly()
    {
        return $this->loginAs(self::$PLIN_READONLY);
    }

    protected function withAuthReferee()
    {
        return $this->loginAs(self::$PLIN_REFEREE);
    }

    protected function withAuthInfobalie()
    {
        return $this->loginAs(self::$PLIN_INFOBALIE);
    }

    protected function withAuthSuper()
    {
        return $this->loginAs(self::$PLIN_SUPER);
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

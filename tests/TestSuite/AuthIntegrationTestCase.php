<?php
declare(strict_types=1);

namespace App\Test\TestSuite;

use App\Test\Fixture\TestAccount;
use Cake\I18n\DateTime;
use Cake\TestSuite\IntegrationTestTrait;

class AuthIntegrationTestCase extends TestCase
{
    use IntegrationTestTrait;

    protected ?string $token = null;
    protected ?TestAccount $account = null;

    public function getFixtures(): array
    {
        return [
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
    }

    public function assertGet(string $url, int $code = 200): mixed
    {
        return $this->_assertRequest($url, 'GET', $code);
    }

    public function assertPost(string $url, array|string $data = [], int $code = 200): mixed
    {
        return $this->_assertRequest($url, 'POST', $code, $data);
    }

    public function assertPatch(string $url, array|string $data = [], int $code = 200): mixed
    {
        return $this->_assertRequest($url, 'PATCH', $code, json_encode($data));
    }

    public function assertPut(string $url, array|string $data = [], int $code = 200): mixed
    {
        return $this->_assertRequest($url, 'PUT', $code, json_encode($data));
    }

    public function assertDelete(string $url, int $code = 204): mixed
    {
        return $this->_assertRequest($url, 'DELETE', $code);
    }

    public function assertHead(string $url, int $code = 200): mixed
    {
        return $this->_assertRequest($url, 'HEAD', $code);
    }

    public function assertOptions(string $url, int $code = 200): mixed
    {
        return $this->_assertRequest($url, 'OPTIONS', $code);
    }

    protected function _assertRequest(string $url, string $method, int $code, array|string $data = []): mixed
    {
        switch ($method) {
            case 'POST':
            case 'DELETE':
            case 'PATCH':
            case 'PUT':
                $this->now = new DateTime('UTC');
                break;
            default:
                $this->now = null;
        }

        $name = $this->account->name ?? 'Unauthenticated';
        $message = "Failed `$method` request on url `$url` with authorization `$name`";

        $this->setConfigRequest($method == 'POST');
        $this->_sendRequest($url, $method, $data);
        $this->assertResponseCode($code, $message);

        return json_decode((string)$this->_response->getBody(), true);
    }

    public function assertErrorsResponse(string $url, array $response): array
    {
        $this->assertArrayKeyValue('class', 'Error', $response);
        $this->assertArrayKeyValue('code', 422, $response);
        $this->assertArrayKeyValue('url', $url, $response);
        $this->assertArrayHasKey('errors', $response);

        return $response['errors'];
    }

    public function assertRedirect(string $url): void
    {
        $this->assertRedirectEquals('http://localhost' . $url);
    }

    protected function withoutAuth(): void
    {
        $this->token = null;
        $this->account = null;
    }

    protected function withAuthPlayer(): void
    {
        $this->loginAs(TestAccount::Player);
    }

    protected function withAuthReadOnly(): void
    {
        $this->loginAs(TestAccount::ReadOnly);
    }

    protected function withAuthReferee(): void
    {
        $this->loginAs(TestAccount::Referee);
    }

    protected function withAuthInfobalie(): void
    {
        $this->loginAs(TestAccount::Infobalie);
    }

    protected function withAuthSuper(): void
    {
        $this->loginAs(TestAccount::Super);
    }

    private function loginAs(TestAccount $account): void
    {
        $this->withoutAuth();
        $this->account = $account;

        $response = $this->assertPut(
            '/auth/login',
            ['id' => $account->value, 'password' => 'password'],
        );

        $this->assertArrayHasKey('token', $response);
        $this->token = $response['token'];
        $this->assertNotNull($this->token, 'Failed asserting that JWT is set after login.');
    }

    protected function setConfigRequest(bool $isPost): void
    {
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => ($isPost ? 'application/x-www-form-urlencoded' : 'application/json'),
        ];

        if ($this->token !== null) {
            $headers['Authorization'] = 'Bearer ' . $this->token;
        }

        $this->configRequest(['headers' => $headers]);
    }
}

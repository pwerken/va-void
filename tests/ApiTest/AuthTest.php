<?php
declare(strict_types=1);

namespace App\Test\ApiTest;

use App\Test\TestSuite\AuthIntegrationTestCase;
use Cake\Core\Configure;
use Cake\Http\TestSuite\HttpClientTrait;
use Cake\TestSuite\EmailTrait;

class AuthTest extends AuthIntegrationTestCase
{
    use EmailTrait;
    use HttpClientTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $provider = [
            'apple' => [
                'applicationId' => 'f00',
                'applicationSecret' => 'bar',
            ],
            'discord' => [
                'applicationId' => 'f00',
                'applicationSecret' => 'bar',
            ],
            'google' => [
                'applicationId' => 'f00',
                'applicationSecret' => 'bar',
            ],
            'gitlab' => [
                'applicationId' => 'f00',
                'applicationSecret' => 'bar',
            ],
        ];

        Configure::write('SocialAuth', $provider);
    }

    public function testValidLogins(): void
    {
        $this->withAuthPlayer();
        $this->withAuthReadOnly();
        $this->withAuthReferee();
        $this->withAuthInfobalie();
        $this->withAuthSuper();
    }

    public function testWithoutAuth(): void
    {
        $this->withoutAuth();
        $this->assertNull($this->token, 'JWT should not be set.');
    }

    public function testInvalidUsernamePassword(): void
    {
        $url = '/auth/login';
        $code = 401;
        $message = 'Invalid username or password';

        $input = ['id' => 1, 'password' => 'wrong'];

        $response = $this->assertPut($url, $input, $code);
        $this->assertArrayKeyValue('class', 'Error', $response);
        $this->assertArrayKeyValue('code', $code, $response);
        $this->assertArrayKeyValue('url', $url, $response);
        $this->assertArrayKeyValue('message', $message, $response);

        $input = ['id' => 99, 'password' => 'wrong'];

        $response = $this->assertPut($url, $input, $code);
        $this->assertArrayKeyValue('class', 'Error', $response);
        $this->assertArrayKeyValue('code', $code, $response);
        $this->assertArrayKeyValue('url', $url, $response);
        $this->assertArrayKeyValue('message', $message, $response);
    }

    public function testSocialListing(): void
    {
        $url = '/auth/social';

        $this->withoutAuth();
        $response = $this->assertGet($url);

        $this->assertArrayKeyValue('class', 'List', $response);
        $this->assertArrayKeyValue('url', '/auth/OAuth2', $response);
        $this->assertArrayHasKey('list', $response);
    }

    public function testOAuth2Listing(): void
    {
        $configured = [];
        foreach (Configure::read('SocialAuth') as $provider => $details) {
            if (!empty($details['applicationId'])) {
                $configured[] = $provider;
            }
        }

        $url = '/auth/OAuth2';

        $this->withoutAuth();
        $response = $this->assertGet($url);

        $this->assertArrayKeyValue('class', 'List', $response);
        $this->assertArrayKeyValue('url', $url, $response);
        $this->assertArrayHasKey('list', $response);

        $list = $response['list'];
        $this->assertCount(count($configured), $list);

        foreach ($list as $socialLogin) {
            $this->assertArrayKeyValue('class', 'OAuth2', $socialLogin);
            $this->assertArrayHasKey('name', $socialLogin);
            $this->assertArrayHasKey('loginRedirect', $socialLogin);
            $this->assertArrayHasKey('urlLoginCode', $socialLogin);
            $this->assertArrayHasKey('urlAccessToken', $socialLogin);
        }
    }

    public function testOAuth2BadRequests(): void
    {
        $this->assertGet('/auth/OAuth2/f00bar', 404);
        $this->assertGet('/auth/OAuth2/google', 400);
        $this->assertGet('/auth/OAuth2/google?code=f4k3', 400);
    }

    public function testOAuth2TokenProviderFailure(): void
    {
        $this->mockClientGet(
            'https://www.googleapis.com/oauth2/v1/userinfo?access_token=f4k3',
            $this->newClientResponse(500),
        );

        $actual = $this->assertGet('/auth/OAuth2/google?token=f4k3', 401);

        $expected = [
            'class' => 'Error',
            'code' => 401,
            'url' => '/auth/OAuth2/google?token=f4k3',
            'message' => 'Login via `google` failed',
        ];
        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
    }

    public function testOAuth2TokenProviderNoId(): void
    {
        $this->mockClientGet(
            'https://www.googleapis.com/oauth2/v1/userinfo?access_token=f4k3',
            $this->newClientResponse(
                200,
                [],
                json_encode(['id' => 0]),
            ),
        );

        $actual = $this->assertGet('/auth/OAuth2/google?token=f4k3', 401);

        $expected = [
            'class' => 'Error',
            'code' => 401,
            'url' => '/auth/OAuth2/google?token=f4k3',
            'message' => 'Login via `google` failed',
        ];
        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
    }

    public function testOAuth2TokenProviderNoEmail(): void
    {
        $this->mockClientGet(
            'https://www.googleapis.com/oauth2/v1/userinfo?access_token=f4k3',
            $this->newClientResponse(
                200,
                [],
                json_encode(['id' => 1, 'email' => null]),
            ),
        );

        $actual = $this->assertGet('/auth/OAuth2/google?token=f4k3', 401);

        $expected = [
            'class' => 'Error',
            'code' => 401,
            'url' => '/auth/OAuth2/google?token=f4k3',
            'message' => 'Login via `google` failed',
        ];
        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
    }

    public function testOAuth2TokenLoginNew(): void
    {
        $this->mockClientGet(
            'https://www.googleapis.com/oauth2/v1/userinfo?access_token=f4k3',
            $this->newClientResponse(
                200,
                [],
                json_encode(['id' => 1, 'email' => 'new@example.com']),
            ),
        );

        $actual = $this->assertGet('/auth/OAuth2/google?token=f4k3', 403);

        $this->assertMailCount(1);
        $this->assertMailSubjectContains('Social login has no associated plin');

        $expected = [
            'class' => 'Error',
            'code' => 403,
            'url' => '/auth/OAuth2/google?token=f4k3',
            'message' => 'Email has no associated plin. Site admin notified. Expect an email.',
        ];
        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
    }

    public function testOAuth2TokenLoginExistingPlayer(): void
    {
        $this->mockClientGet(
            'https://gitlab.com/api/v4/user?access_token=f4k3',
            $this->newClientResponse(
                200,
                [],
                json_encode(['id' => 11, 'email' => 'test@example.com']),
            ),
        );
        $actual = $this->assertGet('/auth/OAuth2/gitlab?token=f4k3');

        $expected = [
            'class' => 'Auth',
            'player' => '/players/1',
            'plin' => 1,
        ];
        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }

        $this->assertArrayHasKey('token', $actual);
    }

    public function testOAuth2TokenLoginExistingSocial(): void
    {
        $this->mockClientGet(
            'https://gitlab.com/api/v4/user?access_token=f4k3',
            $this->newClientResponse(
                200,
                [],
                json_encode(['id' => 11, 'email' => 'fake@example.com']),
            ),
        );
        $actual = $this->assertGet('/auth/OAuth2/gitlab?token=f4k3');

        $expected = [
            'class' => 'Auth',
            'player' => '/players/1',
            'plin' => 1,
        ];
        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }

        $this->assertArrayHasKey('token', $actual);
    }

    public function testOAuth2CallbackFailure(): void
    {
        $this->mockClientPost(
            'https://accounts.google.com/o/oauth2/token',
            $this->newClientResponse(200, [], json_encode([
                'access_token' => 'token',
            ])),
        );

        $actual = $this->assertGet('/auth/OAuth2/google?code=f4k3&redirect_uri=somewhere', 401);

        $expected = [
            'class' => 'Error',
            'code' => 401,
            'url' => '/auth/OAuth2/google?code=f4k3&redirect_uri=somewhere',
            'message' => 'Login via `google` failed',
        ];
        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
    }

    public function testOpenIDConnectListing(): void
    {
        $url = '/auth/OpenIDConnect';

        $this->withoutAuth();
        $response = $this->assertGet($url);

        $this->assertArrayKeyValue('class', 'List', $response);
        $this->assertArrayKeyValue('url', $url, $response);
        $this->assertArrayHasKey('list', $response);

        $list = $response['list'];
        $this->assertCount(2, $list);

        foreach ($list as $socialLogin) {
            $this->assertArrayKeyValue('class', 'OpenIDConnect', $socialLogin);
            $this->assertArrayHasKey('name', $socialLogin);
            $this->assertArrayHasKey('url', $socialLogin);
        }
    }

    public function testOpenIDConnectBadRequests(): void
    {
        $this->assertGet('/auth/OpenIDConnect/f00bar', 404);
        $this->assertGet('/auth/OpenIDConnect/google', 400);
    }

    public function testAppleLoginKeyFetchFailure(): void
    {
        $this->mockClientGet(
            'https://appleid.apple.com/auth/keys',
            $this->newClientResponse(500),
        );

        $actual = $this->assertGet('/auth/OpenIDConnect/apple?token=f4k3', 401);
        $expected = [
            'class' => 'Error',
            'code' => 401,
            'url' => '/auth/OpenIDConnect/apple?token=f4k3',
            'message' => 'API response with error code',
        ];
        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
    }

    public function testGoogleLoginKeyFetchFailure(): void
    {
        $this->mockClientGet(
            'https://accounts.google.com/.well-known/openid-configuration',
            $this->newClientResponse(500),
        );

        $actual = $this->assertGet('/auth/OpenIDConnect/google?token=f4k3', 401);
        $expected = [
            'class' => 'Error',
            'code' => 401,
            'url' => '/auth/OpenIDConnect/google?token=f4k3',
            'message' => 'API response with error code',
        ];
        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
    }
}

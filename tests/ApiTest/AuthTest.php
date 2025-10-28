<?php
declare(strict_types=1);

namespace App\Test\ApiTest;

use App\Test\TestSuite\AuthIntegrationTestCase;
use Cake\Http\TestSuite\HttpClientTrait;
use Cake\TestSuite\EmailTrait;

class AuthTest extends AuthIntegrationTestCase
{
    use EmailTrait;
    use HttpClientTrait;

    public function testWithoutAuth(): void
    {
        $this->withoutAuth();
        $this->assertNull($this->token, 'JWT should not be set.');
    }

    public function testValidLogins(): void
    {
        $this->withAuthPlayer();
        $this->withAuthReadOnly();
        $this->withAuthReferee();
        $this->withAuthInfobalie();
        $this->withAuthSuper();
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
        $this->assertArrayKeyValue('url', $url, $response);
        $this->assertArrayHasKey('list', $response);

        $list = $response['list'];
        $this->assertCount(3, $list);

        foreach ($list as $socialLogin) {
            $this->assertArrayKeyValue('class', 'SocialLogin', $socialLogin);
            $this->assertArrayHasKey('name', $socialLogin);
            $this->assertArrayHasKey('url', $socialLogin);
            $this->assertArrayHasKey('authUri', $socialLogin);
        }
    }

    public function testSocialBadRequests(): void
    {
        $this->assertGet('/auth/social/f00bar', 404);
        $this->assertGet('/auth/social/google', 400);
        $this->assertGet('/auth/social/google?code=f4k3', 400);
    }

    public function testSocialTokenProviderFailure(): void
    {
        $this->mockClientGet(
            'https://www.googleapis.com/oauth2/v1/userinfo?access_token=f4k3',
            $this->newClientResponse(500),
        );

        $actual = $this->assertGet('/auth/social/google?token=f4k3', 401);

        $expected = [
            'class' => 'Error',
            'code' => 401,
            'url' => '/auth/social/google?token=f4k3',
            'message' => 'Login via `google` failed',
        ];
        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
    }

    public function testSocialTokenProviderNoId(): void
    {
        $this->mockClientGet(
            'https://www.googleapis.com/oauth2/v1/userinfo?access_token=f4k3',
            $this->newClientResponse(
                200,
                [],
                json_encode(['id' => 0]),
            ),
        );

        $actual = $this->assertGet('/auth/social/google?token=f4k3', 401);

        $expected = [
            'class' => 'Error',
            'code' => 401,
            'url' => '/auth/social/google?token=f4k3',
            'message' => 'Login via `google` failed',
        ];
        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
    }

    public function testSocialTokenProviderNoEmail(): void
    {
        $this->mockClientGet(
            'https://www.googleapis.com/oauth2/v1/userinfo?access_token=f4k3',
            $this->newClientResponse(
                200,
                [],
                json_encode(['id' => 1, 'email' => null]),
            ),
        );

        $actual = $this->assertGet('/auth/social/google?token=f4k3', 401);

        $expected = [
            'class' => 'Error',
            'code' => 401,
            'url' => '/auth/social/google?token=f4k3',
            'message' => 'Login via `google` failed',
        ];
        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
    }

    public function testSocialTokenLoginNew(): void
    {
        $this->mockClientGet(
            'https://www.googleapis.com/oauth2/v1/userinfo?access_token=f4k3',
            $this->newClientResponse(
                200,
                [],
                json_encode(['id' => 1, 'email' => 'new@example.com']),
            ),
        );

        $actual = $this->assertGet('/auth/social/google?token=f4k3', 401);

        $this->assertMailCount(1);
        $this->assertMailSubjectContains('Social login has no associated plin');

        $expected = [
            'class' => 'Error',
            'code' => 401,
            'url' => '/auth/social/google?token=f4k3',
            'message' => 'Email has no associated plin. Site admin notified. Expect an email.',
        ];
        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
    }

    public function testSocialTokenLoginExisting(): void
    {
        $this->mockClientGet(
            'https://gitlab.com/api/v4/user?access_token=f4k3',
            $this->newClientResponse(
                200,
                [],
                json_encode(['id' => 1, 'email' => 'test@example.com']),
            ),
        );
        $actual = $this->assertGet('/auth/social/gitlab?token=f4k3');

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

    public function testSocialCallbackFailure(): void
    {
        $this->mockClientPost(
            'https://accounts.google.com/o/oauth2/token',
            $this->newClientResponse(200, [], json_encode([
                'access_token' => 'token',
            ])),
        );

        $actual = $this->assertGet('/auth/social/google?code=f4k3&redirect_uri=somewhere', 401);

        $expected = [
            'class' => 'Error',
            'code' => 401,
            'url' => '/auth/social/google?code=f4k3&redirect_uri=somewhere',
            'message' => 'Login via `google` failed',
        ];
        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
    }
}

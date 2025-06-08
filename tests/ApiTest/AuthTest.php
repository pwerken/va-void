<?php
declare(strict_types=1);

namespace App\Test\ApiTest;

use App\Test\TestSuite\AuthIntegrationTestCase;

class AuthTest extends AuthIntegrationTestCase
{
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

    public function testSocialProvider(): void
    {
        $this->assertGet('/auth/social/f00bar', 404); # Not Found
        $this->assertGet('/auth/social/google', 400); # Bad Request
        $this->assertGet('/auth/social/google?token=f4k3', 401); # Login Failed
        $this->assertGet('/auth/social/google?code=f4k3', 400); # Bad Request
        $this->assertGet('/auth/social/google?code=f4k3&redirect_uri=f00b4r', 401); # Login Failed
    }
}

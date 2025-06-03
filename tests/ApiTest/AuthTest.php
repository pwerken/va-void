<?php
declare(strict_types=1);

namespace App\Test\ApiTest;

use App\Test\Fixture\TestAccount;
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
}

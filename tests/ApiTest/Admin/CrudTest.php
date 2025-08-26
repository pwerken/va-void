<?php
declare(strict_types=1);

namespace App\Test\ApiTest\Admin;

use App\Test\TestSuite\AuthIntegrationTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class CrudTest extends AuthIntegrationTestCase
{
    public static function tables(): array
    {
        return [
            ['events'],
            ['factions'],
            ['manatypes'],
            ['skills'],
        ];
    }

    #[DataProvider('tables')]
    public function testAuthorization(string $table): void
    {
        $url = '/admin/' . $table;

        $this->withoutAuth();
        $this->assertGet($url, 302);
        $this->assertRedirect('/admin?redirect=' . urlencode($url));

        $this->withAuthPlayer();
        $this->assertGet($url, 403);
        $this->assertGet($url . '/add', 403);
        $this->assertGet($url . '/edit/1', 403);
        $this->assertPost($url . '/add', [], 403);
        $this->assertPost($url . '/edit/1', [], 403);
        $this->assertPost($url . '/delete/1', [], 403);

        $this->withAuthReadOnly();
        $this->assertGet($url);
        $this->assertGet($url . '/add', 403);
        $this->assertGet($url . '/edit/1', 403);
        $this->assertPost($url . '/add', [], 403);
        $this->assertPost($url . '/edit/1', [], 403);
        $this->assertPost($url . '/delete/1', [], 403);

        $this->withAuthReferee();
        $this->assertGet($url);
        $this->assertGet($url . '/add', 403);
        $this->assertGet($url . '/edit/1', 403);
        $this->assertPost($url . '/add', [], 403);
        $this->assertPost($url . '/edit/1', [], 403);
        $this->assertPost($url . '/delete/1', [], 403);

        $this->withAuthInfobalie();
        $this->assertGet($url);
        $this->assertGet($url . '/add', 403);
        $this->assertGet($url . '/edit/1', 403);
        $this->assertPost($url . '/add', [], 403);
        $this->assertPost($url . '/edit/1', [], 403);
        $this->assertPost($url . '/delete', [], 403);

        $this->withAuthSuper();
        $this->assertGet($url);
        $this->assertGet($url . '/add');
        $this->assertGet($url . '/edit/1');
        $this->assertPost($url . '/add', [], 200);
        $this->assertPost($url . '/edit/1', [], 302);
        $this->assertPost($url . '/delete/1', [], 302);
    }
}

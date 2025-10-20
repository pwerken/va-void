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
            ['events', 1],
            ['factions', 2],
            ['manatypes', 1],
            ['skills', 2],
        ];
    }

    #[DataProvider('tables')]
    public function testAuthorization(string $table, int $count): void
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
    }

    #[DataProvider('tables')]
    public function testSuper(string $table, int $count): void
    {
        $url = '/admin/' . $table;

        $new = [
            'name' => 'test',
            'cost' => 1, # required for skills, does not interfere with the rest
        ];

        $this->withAuthSuper();
        $this->assertGet($url);

        $this->assertGet($url . '/add');
        $this->assertPost($url . '/add', [], 200);
        $this->assertFlashMessage('Failed to add');

        $i = $count + 1;
        $this->assertPost($url . '/add', $new, 302);
        $this->assertRedirect($url);
        $this->assertFlashMessage('Added id#' . $i);

        $this->assertGet($url . '/edit/' . $i);
        $this->assertPost($url . '/edit/' . $i, ['name' => 'edit'], 302);
        $this->assertRedirect($url);
        $this->assertFlashMessage('Modified id#' . $i);

        $this->assertPost($url . '/delete/' . $i, [], 302);
        $this->assertRedirect($url);
        $this->assertFlashMessage('Deleted id#' . $i);
    }
}

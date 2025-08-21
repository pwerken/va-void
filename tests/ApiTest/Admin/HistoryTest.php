<?php
declare(strict_types=1);

namespace App\Test\ApiTest\Admin;

use App\Test\TestSuite\AuthIntegrationTestCase;

class HistoryTest extends AuthIntegrationTestCase
{
    protected string $url = '/admin/history';

    public function testAuthorization(): void
    {
        $this->withoutAuth();
        $this->assertGet($this->url, 302);
        $this->assertRedirect('/admin?redirect=' . urlencode($this->url));

        $this->assertGet($this->url . '/player/1', 302);
        $this->assertGet($this->url . '/player/99', 302);
        $this->assertGet($this->url . '/character/1/1', 302);
        $this->assertGet($this->url . '/character/99/1', 302);
        $this->assertGet($this->url . '/imbue/1', 302);
        $this->assertGet($this->url . '/imbue/99', 302);
        $this->assertGet($this->url . '/power/1', 302);
        $this->assertGet($this->url . '/power/99', 302);
        $this->assertGet($this->url . '/condition/1', 302);
        $this->assertGet($this->url . '/condition/99', 302);
        $this->assertGet($this->url . '/item/1', 302);
        $this->assertGet($this->url . '/item/99', 302);
    }

    public function testAsPlayer(): void
    {
        $this->withAuthPlayer();
        $this->assertGet($this->url, 403);
        $this->assertGet($this->url . '/player/1', 403);
        $this->assertGet($this->url . '/player/99', 403);
        $this->assertGet($this->url . '/character/1/1', 403);
        $this->assertGet($this->url . '/character/99/1', 403);
        $this->assertGet($this->url . '/imbue/1', 403);
        $this->assertGet($this->url . '/imbue/99', 403);
        $this->assertGet($this->url . '/power/1', 403);
        $this->assertGet($this->url . '/power/99', 403);
        $this->assertGet($this->url . '/condition/1', 403);
        $this->assertGet($this->url . '/condition/99', 403);
        $this->assertGet($this->url . '/item/1', 403);
        $this->assertGet($this->url . '/item/99', 403);
    }

    public function testAsReadOnly(): void
    {
        $this->withAuthReadOnly();

        $this->assertGet($this->url);
        $this->assertGet($this->url . '/player/1');
        $this->assertGet($this->url . '/player/99', 404);
        $this->assertGet($this->url . '/character/1/1');
        $this->assertGet($this->url . '/character/99/1', 404);
        $this->assertGet($this->url . '/imbue/1');
        $this->assertGet($this->url . '/imbue/99', 404);
        $this->assertGet($this->url . '/power/1');
        $this->assertGet($this->url . '/power/99', 404);
        $this->assertGet($this->url . '/condition/1');
        $this->assertGet($this->url . '/condition/99', 404);
        $this->assertGet($this->url . '/item/1');
        $this->assertGet($this->url . '/item/99', 404);
    }
}

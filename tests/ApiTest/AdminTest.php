<?php
declare(strict_types=1);

namespace App\Test\ApiTest;

use App\Test\TestSuite\AuthIntegrationTestCase;
use Cake\I18n\DateTime;

class AdminTest extends AuthIntegrationTestCase
{
    public function testAuthorization(): void
    {
        $this->withoutAuth();
        $this->assertHttp('/admin', 'GET');
        $this->assertHttp('/admin', 'POST');
        $this->assertHttp('/admin/authorization', 'GET', 302);
        $this->assertHttp('/admin/authorization/edit', 'POST', 302);
        $this->assertHttp('/admin/backups', 'GET', 302);
        $this->assertHttp('/admin/checks', 'GET');
        $this->assertHttp('/admin/history', 'GET', 302);
        $this->assertHttp('/admin/history/player/1', 'GET', 302);
        $this->assertHttp('/admin/history/character/1/1', 'GET', 302);
        $this->assertHttp('/admin/history/item/1', 'GET', 302);
        $this->assertHttp('/admin/history/condition/1', 'GET', 302);
        $this->assertHttp('/admin/history/power/1', 'GET', 302);
        $this->assertHttp('/admin/logout', 'GET', 302);
        $this->assertHttp('/admin/migrations', 'GET', 302);
        $this->assertHttp('/admin/password', 'GET', 302);
        $this->assertHttp('/admin/password', 'POST', 302);
        $this->assertHttp('/admin/printing', 'GET', 302);
        $this->assertHttp('/admin/printing', 'POST', 302);
        $this->assertHttp('/admin/printing/single', 'GET', 302);
        $this->assertHttp('/admin/printing/double', 'GET', 302);
        $this->assertHttp('/admin/routes', 'GET', 302);
        $this->assertHttp('/admin/skills', 'GET', 302);
        $this->assertHttp('/admin/social', 'GET', 302);
        $this->assertHttp('/admin/social', 'POST', 302);
        $this->assertHttp('/admin/social/all', 'GET', 302);
        $this->assertHttp('/admin/social/all', 'POST', 302);
        $this->assertHttp('/admin/social/callback', 'GET', 302);
        $this->assertHttp('/admin/stats', 'GET', 302);

        $this->withAuthPlayer();
        $this->assertHttp('/admin', 'GET');
        $this->assertHttp('/admin', 'POST');
        $this->assertHttp('/admin/authorization', 'GET', 403);
        $this->assertHttp('/admin/authorization/edit', 'POST', 403);
        $this->assertHttp('/admin/backups', 'GET', 403);
        $this->assertHttp('/admin/checks', 'GET');
        $this->assertHttp('/admin/history', 'GET', 403);
        $this->assertHttp('/admin/history/player/99', 'GET', 403);
        $this->assertHttp('/admin/history/character/99/1', 'GET', 403);
        $this->assertHttp('/admin/history/item/99', 'GET', 403);
        $this->assertHttp('/admin/history/condition/99', 'GET', 403);
        $this->assertHttp('/admin/history/power/99', 'GET', 403);
        $this->assertHttp('/admin/logout', 'GET', 302);
        $this->assertHttp('/admin/migrations', 'GET', 403);
        $this->assertHttp('/admin/password', 'GET');
        $this->assertHttp('/admin/password', 'POST');
        $this->assertHttp('/admin/printing', 'GET', 403);
        $this->assertHttp('/admin/printing', 'POST', 403);
        $this->assertHttp('/admin/printing/single', 'GET', 403);
        $this->assertHttp('/admin/printing/double', 'GET', 403);
        $this->assertHttp('/admin/routes', 'GET');
        $this->assertHttp('/admin/skills', 'GET', 403);
        $this->assertHttp('/admin/social', 'GET', 403);
        $this->assertHttp('/admin/social', 'POST', 403);
        $this->assertHttp('/admin/social/all', 'GET', 403);
        $this->assertHttp('/admin/social/all', 'POST', 403);
        $this->assertHttp('/admin/stats', 'GET', 403);

        $this->withAuthReadOnly();
        $this->assertHttp('/admin/backups', 'GET', 403);
        $this->assertHttp('/admin/authorization', 'GET');
        $this->assertHttp('/admin/authorization/edit', 'POST', 302);
        $this->assertHttp('/admin/history', 'GET');
        $this->assertHttp('/admin/history/player/99', 'GET', 404);
        $this->assertHttp('/admin/history/character/99/1', 'GET', 404);
        $this->assertHttp('/admin/history/item/99', 'GET', 404);
        $this->assertHttp('/admin/history/condition/99', 'GET', 404);
        $this->assertHttp('/admin/history/power/99', 'GET', 404);
        $this->assertHttp('/admin/migrations', 'GET', 403);
        $this->assertHttp('/admin/printing', 'GET');
        $this->assertHttp('/admin/printing', 'POST');
        $this->assertHttp('/admin/printing/single', 'GET', 403);
        $this->assertHttp('/admin/printing/double', 'GET', 403);
        $this->assertHttp('/admin/skills', 'GET');
        $this->assertHttp('/admin/skills?skills=&skills[]=1&skills[]=2&and=1', 'GET');
        $this->assertHttp('/admin/social', 'GET', 403);
        $this->assertHttp('/admin/social', 'POST', 403);
        $this->assertHttp('/admin/social/all', 'GET', 403);
        $this->assertHttp('/admin/social/all', 'POST', 403);
        $this->assertHttp('/admin/stats', 'GET');
        $this->assertHttp('/admin/stats?selected=xp-curve', 'GET');
        $this->assertHttp('/admin/stats?selected=items-per-char', 'GET');
        $this->assertHttp('/admin/stats?selected=powers-conditions-per-char', 'GET');
        $this->assertHttp('/admin/stats?selected=attunement-powers-per-char', 'GET');
        $this->assertHttp('/admin/stats?selected=attunement-per-char', 'GET');

        $this->withAuthReferee();
        $this->assertHttp('/admin/backups', 'GET', 403);
        $this->assertHttp('/admin/migrations', 'GET', 403);
        $this->assertHttp('/admin/printing/single', 'GET', 403);
        $this->assertHttp('/admin/printing/double', 'GET', 403);
        $this->assertHttp('/admin/social', 'GET', 403);
        $this->assertHttp('/admin/social', 'POST', 403);
        $this->assertHttp('/admin/social/all', 'GET', 403);
        $this->assertHttp('/admin/social/all', 'POST', 403);

        $this->withAuthInfobalie();
        $this->assertHttp('/admin/backups', 'GET');
        $this->assertHttp('/admin/migrations', 'GET');
        $this->assertHttp('/admin/printing/double', 'GET');
        $this->assertHttp('/admin/printing/single', 'GET');
        $this->assertHttp('/admin/social', 'GET');
        $this->assertHttp('/admin/social', 'POST', 302);
        $this->assertHttp('/admin/social/all', 'GET');
        $this->assertHttp('/admin/social/all', 'POST', 302);
    }

    protected function assertHttp(string $url, string $method, int $code = 200, array|string $data = []): mixed
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

        return (string)$this->_response->getBody();
    }
}

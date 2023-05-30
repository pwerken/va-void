<?php
declare(strict_types=1);

namespace App\Test\TestCase\Integration;

use App\Test\TestSuite\AuthIntegrationTestCase;

class PlayersTest
    extends AuthIntegrationTestCase
{

    public function getFixtures(): array
    {
        return [
            'app.Players',
            'app.Characters',
        ];
    }

    public function checkList($url, $size)
    {
        $data = $this->assertGet($url);

        $this->assertArrayKeyValue('class', 'List', $data);
        $this->assertArrayKeyValue('url', $url, $data);
        $this->assertArrayNotHasKey('parent', $data);
        $this->assertArrayHasKey('list', $data);
        $this->assertCount($size, $data['list']);

        return $data['list'];
    }

    public function checkPlayerCompact($id, $data)
    {
        $ref = $this->fetchTable('players')->get($id);

        $this->assertCount(4, $data);
        $this->assertArrayKeyValue('class', $ref->getClass(), $data);
        $this->assertArrayKeyValue('url', $ref->getUrl(), $data);
        $this->assertArrayKeyValue('plin', $ref->getIdentifier(), $data);
        $this->assertArrayKeyValue('full_name', $ref->full_name, $data);
    }

    public function checkPlayer($url, $id)
    {
        $ref = $this->fetchTable('players')->get($id);

        $data = $this->assertGet($url);

        $this->assertCount(13, $data);
        $this->assertArrayKeyValue('class', $ref->getClass(), $data);
        $this->assertArrayKeyValue('url', $ref->getUrl(), $data);
        $this->assertArrayKeyValue('plin', $ref->getIdentifier(), $data);

        $this->assertArrayKeyValue('role', $ref->role, $data);
        $this->assertArrayKeyValue('password', isset($ref->password), $data);
        $this->assertArrayKeyValue('first_name', $ref->first_name, $data);
        $this->assertArrayKeyValue('insertion', $ref->insertion, $data);
        $this->assertArrayKeyValue('last_name', $ref->last_name, $data);
        $this->assertArrayKeyValue('gender', $ref->gender, $data);
        $this->assertArrayKeyValue('date_of_birth', $ref->date_of_birth, $data);
        $this->assertArrayKeyValue('modified', $ref->modified, $data);
        $this->assertArrayKeyValue('full_name', $ref->full_name, $data);

        $this->assertArrayHasKey('characters', $data);
    }

    public function testUnauthenticatedAccess()
    {
        $this->withoutAuth();
        $this->assertGet('/players', 403);
        $this->assertGet('/players/1', 403);
        $this->assertGet('/players/1/characters', 403);
        $this->assertGet('/players/2', 403);
        $this->assertGet('/players/2/characters', 403);
        $this->assertGet('/players/99', 403);
        $this->assertGet('/players/99/characters', 403);
    }

    public function testGetAsPlayer()
    {
        $this->withAuthPlayer();

        $list = $this->checkList('/players', 1);
        $this->checkPlayerCompact(1, $list[0]);

        $this->checkPlayer('/players/1', 1);

        $this->assertGet('/players/1/characters');
        $this->assertGet('/players/2', 403);
        $this->assertGet('/players/2/characters', 403);
        $this->assertGet('/players/99', 404);
        $this->assertGet('/players/99/characters', 404);
    }

    public function testGetAsReadonly($asReadOnly = True)
    {
        if ($asReadOnly) {
            $this->withAuthReadOnly();
        }

        $list = $this->checkList('/players', 6);
        $this->checkPlayerCompact(1, $list[0]);
        $this->checkPlayerCompact(2, $list[1]);
        $this->checkPlayerCompact(3, $list[2]);
        $this->checkPlayerCompact(4, $list[3]);
        $this->checkPlayerCompact(5, $list[4]);
        $this->checkPlayerCompact(6, $list[5]);

        $this->assertGet('/players/1');
        $this->assertGet('/players/1/characters');
        $this->assertGet('/players/2');
        $this->assertGet('/players/2/characters');
        $this->assertGet('/players/99', 404);
        $this->assertGet('/players/99/characters', 404);
    }

    public function testGetAsReferee()
    {
        $this->withAuthReadOnly();
        $this->testGetAsReadonly(False);
    }

    public function testGetAsInfobalie()
    {
        $this->withAuthInfobalie();
        $this->testGetAsReadonly(False);
    }

    public function testGetAsSuper()
    {
        $this->withAuthSuper();
        $this->testGetAsReadonly(False);
    }
}

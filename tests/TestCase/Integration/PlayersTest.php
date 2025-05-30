<?php
declare(strict_types=1);

namespace App\Test\TestCase\Integration;

use App\Test\TestSuite\AuthIntegrationTestCase;

class PlayersTest extends AuthIntegrationTestCase
{
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
        $this->assertArrayKeyValue('email', $ref->email, $data);
        $this->assertArrayKeyValue('modified', $ref->modified, $data);
        $this->assertArrayKeyValue('full_name', $ref->full_name, $data);

        $this->assertArrayHasKey('characters', $data);
        $this->assertArrayHasKey('socials', $data);
    }

    public function testAuthorization()
    {
        $this->withoutAuth();
        $this->assertGet('/players', 401);
        $this->assertGet('/players/1', 401);
        $this->assertGet('/players/1/characters', 401);
        $this->assertGet('/players/2', 401);
        $this->assertGet('/players/2/characters', 401);
        $this->assertGet('/players/99', 401);
        $this->assertGet('/players/99/characters', 401);
        $this->assertPut('/players', [], 401);
        $this->assertPut('/players/1', [], 401);
        $this->assertPut('/players/1/characters', [], 401);
        $this->assertPut('/players/2', [], 401);
        $this->assertPut('/players/2/characters', [], 401);
        $this->assertPut('/players/99', [], 401);
        $this->assertPut('/players/99/characters', [], 401);

        $this->withAuthPlayer();
        $this->assertGet('/players');
        $this->assertGet('/players/1/characters');
        $this->assertGet('/players/1/socials');
        $this->assertGet('/players/2', 403);
        $this->assertGet('/players/2/characters', 403);
        $this->assertGet('/players/2/socials', 403);
        $this->assertGet('/players/99', 404);
        $this->assertGet('/players/99/characters', 403);
        $this->assertGet('/players/99/socials', 403);
        $this->assertPut('/players', [], 403);
        $this->assertPut('/players/1', []);
        $this->assertPut('/players/1/characters', [], 403);
        $this->assertPut('/players/2', [], 403);
        $this->assertPut('/players/2/characters', [], 403);
        $this->assertPut('/players/99', [], 404);
        $this->assertPut('/players/99/characters', [], 403);

        $this->withAuthReadOnly();
        $this->assertGet('/players');
        $this->assertGet('/players/1');
        $this->assertGet('/players/1/characters');
        $this->assertGet('/players/2');
        $this->assertGet('/players/2/characters');
        $this->assertGet('/players/99', 404);
        $this->assertGet('/players/99/characters', 404);
        $this->assertPut('/players', [], 403);
        $this->assertPut('/players/1', [], 403);
        $this->assertPut('/players/1/characters', [], 403);
        $this->assertPut('/players/2', []);
        $this->assertPut('/players/2/characters', [], 403);
        $this->assertPut('/players/99', [], 404);
        $this->assertPut('/players/99/characters', [], 403);

        $this->withAuthReferee();
        $this->assertGet('/players');
        $this->assertGet('/players/1');
        $this->assertGet('/players/1/characters');
        $this->assertGet('/players/2');
        $this->assertGet('/players/2/characters');
        $this->assertGet('/players/99', 404);
        $this->assertGet('/players/99/characters', 404);
        $this->assertPut('/players', [], 403);
        $this->assertPut('/players/1', [], 403);
        $this->assertPut('/players/1/characters', [], 422);
        $this->assertPut('/players/2', [], 403);
        $this->assertPut('/players/2/characters', [], 422);
        $this->assertPut('/players/99', [], 404);
        $this->assertPut('/players/99/characters', [], 422); // FIXME -> 404
    }

    public function testRequiredFieldsValidation()
    {
        $this->withAuthInfobalie();
        $response = $this->assertPut('/players', [], 422);

        $errors = $this->assertErrorsResponse('/players', $response);

        # expected fields with validation errors:
        $this->assertCount(3, $errors);
        $this->assertArrayHasKey('id', $errors); // FIXME? -> plin
        $this->assertArrayHasKey('first_name', $errors);
        $this->assertArrayHasKey('last_name', $errors);
    }

    public function testAddPlayerMinimal()
    {
        $input = [
# only required fields:
            'plin' => 10,
            'first_name' => 'first',
            'last_name' => 'last',
        ];

        $expected = [
            'class' => 'Player',
            'url' => '/players/10',
            'plin' => 10,
            'first_name' => $input['first_name'],
            'insertion' => null,
            'last_name' => $input['last_name'],
            'full_name' => $input['first_name'] . ' ' . $input['last_name'],
            'email' => null,
            'modifier_id' => self::$PLIN_INFOBALIE,
        ];

        $this->withAuthInfobalie();
        $actual = $this->assertPut('/players', $input, 201);

        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
        $this->assertDateTimeNow($actual['modified']);
    }

    public function testAddPlayerComplete()
    {
        $input = [
# required fields:
            'plin' => 10,
            'first_name' => 'first',
            'last_name' => 'last',
# optional fields:
            'insertion' => 'insertion',
            'email' => 'email@example.com',
            'password' => 'secret',
            'role' => 'Read-only',
# ignored fields:
            'modifier_id' => '99',
            'ignored' => 'ignored',
        ];

        $expected = [
            'class' => 'Player',
            'url' => '/players/10',
            'plin' => 10,
            'role' => $input['role'],
            'password' => (bool)$input['password'],
            'first_name' => $input['first_name'],
            'insertion' => $input['insertion'],
            'last_name' => $input['last_name'],
            'full_name' => $input['first_name'] . ' ' . $input['insertion'] . ' ' . $input['last_name'],
            'email' => $input['email'],
            'modifier_id' => self::$PLIN_INFOBALIE,
        ];

        $this->withAuthInfobalie();
        $actual = $this->assertPut('/players', $input, 201);

        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
        $this->assertDateTimeNow($actual['modified']);

        $this->assertArrayNotHasKey('ignored', $actual);
    }

    public function testEditOwnPlayer()
    {
        $input = [
# disallowed fields:
            'plin' => 50,
# required fields:
            'first_name' => 'first',
            'last_name' => 'last',
# optional fields:
            'insertion' => 'insertion',
            'email' => 'email@example.com',
            'password' => 'secret',
# ignored fields:
            'role' => 'Read-only',
            'modifier_id' => '99',
            'ignored' => 'ignored',
        ];

        $this->withAuthPlayer();
        $actual = $this->assertPut('/players/1', $input, 422);

        $errors = $this->assertErrorsResponse('/players/1', $actual);
        # expected fields with validation errors:
        $this->assertCount(1, $errors);
        $this->assertArrayHasKey('id', $errors); // FIXME? -> plin

        unset($input['plin']);
        $expected = [
            'class' => 'Player',
            'url' => '/players/1',
            'plin' => 1,
            'role' => 'Player',
            'password' => (bool)$input['password'],
            'first_name' => $input['first_name'],
            'insertion' => $input['insertion'],
            'last_name' => $input['last_name'],
            'full_name' => $input['first_name'] . ' ' . $input['insertion'] . ' ' . $input['last_name'],
            'email' => $input['email'],
        ];

        $actual = $this->assertPut('/players/1', $input);

        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
        $this->assertDateTimeNow($actual['modified']);
        $this->assertArrayNotHasKey('modifier_id', $actual);
        $this->assertArrayNotHasKey('ignored', $actual);
    }

    public function testEditPlayer()
    {
        $input = [
# disallowed fields:
            'plin' => 50,
# required fields:
            'first_name' => 'first',
            'last_name' => 'last',
# optional fields:
            'insertion' => 'insertion',
            'email' => 'email@example.com',
            'password' => 'secret',
            'role' => 'Read-only',
# ignored fields:
            'modifier_id' => '99',
            'ignored' => 'ignored',
        ];

        $this->withAuthInfobalie();
        $actual = $this->assertPut('/players/1', $input, 422);

        $errors = $this->assertErrorsResponse('/players/1', $actual);
        # expected fields with validation errors:
        $this->assertCount(1, $errors);
        $this->assertArrayHasKey('id', $errors); // FIXME? -> plin

        unset($input['plin']);
        $expected = [
            'class' => 'Player',
            'url' => '/players/1',
            'plin' => 1,
            'role' => $input['role'],
            'password' => (bool)$input['password'],
            'first_name' => $input['first_name'],
            'insertion' => $input['insertion'],
            'last_name' => $input['last_name'],
            'full_name' => $input['first_name'] . ' ' . $input['insertion'] . ' ' . $input['last_name'],
            'email' => $input['email'],
            'modifier_id' => self::$PLIN_INFOBALIE,
        ];

        $actual = $this->assertPut('/players/1', $input);

        foreach ($expected as $key => $value) {
            $this->assertArrayKeyValue($key, $value, $actual);
        }
        $this->assertDateTimeNow($actual['modified']);
        $this->assertArrayNotHasKey('ignored', $actual);
    }

    public function testGetIndexAsPlayer()
    {
        $this->withAuthPlayer();

        $list = $this->checkList('/players', 1);
        $this->checkPlayerCompact(1, $list[0]);

        $this->checkPlayer('/players/1', 1);
    }

    public function testGetIndexAsReadonly($asReadOnly = true)
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
    }
}

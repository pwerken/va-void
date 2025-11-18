<?php
declare(strict_types=1);

namespace App\Test\ApiTest;

use App\Test\TestSuite\AuthIntegrationTestCase;

class TeachingsTest extends AuthIntegrationTestCase
{
    public function testAuthorizationGet(): void
    {
        $this->withoutAuth();
        $this->assertGet('/characters/1/1/students', 401);
        $this->assertGet('/characters/1/1/teacher', 401);
        $this->assertGet('/characters/1/2/students', 401);
        $this->assertGet('/characters/1/2/teacher', 401);
        $this->assertGet('/characters/1/99/students', 401);
        $this->assertGet('/characters/1/99/teacher', 401);
        $this->assertGet('/characters/2/1/students', 401);
        $this->assertGet('/characters/2/1/teacher', 401);
        $this->assertGet('/characters/2/2/students', 401);
        $this->assertGet('/characters/2/2/teacher', 401);
        $this->assertGet('/characters/99/1/students', 401);
        $this->assertGet('/characters/99/1/teacher', 401);

        $this->withAuthPlayer();
        $this->assertGet('/characters/1/1/students');
        $this->assertGet('/characters/1/1/teacher');
        $this->assertGet('/characters/1/99/students', 404);
        $this->assertGet('/characters/1/99/teacher', 404);
        $this->assertGet('/characters/2/1/students', 403);
        $this->assertGet('/characters/2/1/teacher', 403);
        $this->assertGet('/characters/99/1/students', 403);
        $this->assertGet('/characters/99/1/teacher', 403);

        $this->withAuthReadOnly();
        $this->assertGet('/characters/1/1/students');
        $this->assertGet('/characters/1/1/teacher');
        $this->assertGet('/characters/1/99/students', 404);
        $this->assertGet('/characters/1/99/teacher', 404);
        $this->assertGet('/characters/2/1/students');
        $this->assertGet('/characters/2/1/teacher');
        $this->assertGet('/characters/99/1/students', 404);
        $this->assertGet('/characters/99/1/teacher', 404);
    }

    public function testAuthorizationPut(): void
    {
        $this->withoutAuth();
        $this->assertPut('/characters/1/1/teacher', [], 401);
        $this->assertPut('/characters/1/99/teacher', [], 401);
        $this->assertPut('/characters/2/1/teacher', [], 401);
        $this->assertPut('/characters/99/1/teacher', [], 401);

        $this->withAuthPlayer();
        $this->assertPut('/characters/1/1/teacher', [], 403);
        $this->assertPut('/characters/1/99/teacher', [], 403);
        $this->assertPut('/characters/2/1/teacher', [], 403);
        $this->assertPut('/characters/99/1/teacher', [], 403);

        $this->withAuthReadOnly();
        $this->assertPut('/characters/1/1/teacher', [], 403);
        $this->assertPut('/characters/1/99/teacher', [], 403);
        $this->assertPut('/characters/2/1/teacher', [], 403);
        $this->assertPut('/characters/99/1/teacher', [], 403);

        $this->withAuthReferee();
        $this->assertPut('/characters/1/1/teacher', [], 422);
        $this->assertPut('/characters/1/99/teacher', [], 404);
        $this->assertPut('/characters/2/1/teacher', [], 422);
        $this->assertPut('/characters/99/1/teacher', [], 404);
    }

    public function testAuthorizationDelete(): void
    {
        $this->withoutAuth();
        $this->assertDelete('/characters/1/1/teacher', 401);
        $this->assertDelete('/characters/1/99/teacher', 401);
        $this->assertDelete('/characters/2/1/teacher', 401);
        $this->assertDelete('/characters/99/1/teacher', 401);

        $this->withAuthPlayer();
        $this->assertDelete('/characters/1/1/teacher', 403);
        $this->assertDelete('/characters/1/99/teacher', 403);
        $this->assertDelete('/characters/2/1/teacher', 403);
        $this->assertDelete('/characters/99/1/teacher', 403);

        $this->withAuthReadOnly();
        $this->assertDelete('/characters/1/1/teacher', 403);
        $this->assertDelete('/characters/1/99/teacher', 403);
        $this->assertDelete('/characters/2/1/teacher', 403);
        $this->assertDelete('/characters/99/1/teacher', 403);
    }

    public function testTeaching(): void
    {
        $teacher = ['plin' => 2, 'chin' => 1, 'skill_id' => 1];

        $this->withAuthReferee();
        $this->assertGet('/characters/1/1/teacher');
        $this->assertDelete('/characters/1/1/teacher');
        $this->assertGet('/characters/1/1/teacher', 404);
        $this->assertPut('/characters/1/1/teacher', $teacher, 201);
        $this->assertGet('/characters/1/1/teacher');
    }
}

<?php
declare(strict_types=1);

namespace App\Test\TestCase\Policy;

use App\Policy\Policy;
use App\Test\TestSuite\TestCase;

class PolicyTest extends TestCase
{
    public function testHasRoleUserWithNull(): void
    {
        $policy = $this->createMock(Policy::class);
        $hasRoleUser = $this->protectedMethod($policy, 'hasRoleUser');

        $this->assertFalse(call_user_func_array($hasRoleUser, [0, null]));
    }
}

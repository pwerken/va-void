<?php
declare(strict_types=1);

namespace App\Test\TestCase\Policy;

use App\Authentication\UnauthenticatedException;
use App\Model\Entity\Player;
use App\Policy\Controller\TeachingsControllerPolicy;
use App\Test\TestSuite\TestCase;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\ServerRequest;

class ControllerPolicyTest extends TestCase
{
    public function testControllerCanAccessExceptions(): void
    {
        $user = $this->createStub(Player::class);
        $request = $this->createStub(ServerRequest::class);
        $request->method('getParam')->willReturn('doesNotExist');

        $policy = new TeachingsControllerPolicy();

        $e = $this->catchException([$policy, 'canAccess'], [$user, $request]);
        $this->assertInstanceOf(NotFoundException::class, $e);

        $e = $this->catchException([$policy, 'canAccess'], [null, $request]);
        $this->assertInstanceOf(UnauthenticatedException::class, $e);
    }

    public function testCannotEditTeaching(): void
    {
        $policy = new TeachingsControllerPolicy();

        $this->assertFalse($policy->charactersEdit());
    }
}

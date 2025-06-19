<?php
declare(strict_types=1);

namespace App\Test\TestCase\Authorization;

use App\Authorization\ControllerPolicyResolver;
use App\Policy\Controller\ControllerPolicy;
use App\Test\TestSuite\TestCase;
use Authorization\Policy\Exception\MissingPolicyException;
use Cake\Http\ServerRequest;
use PHPUnit\Framework\Attributes\DataProvider;

class ControllerPolicyResolverTest extends TestCase
{
    public function testNullResource(): void
    {
        $resolver = new ControllerPolicyResolver();
        $method = function ($resource) use ($resolver) {
            return $resolver->getPolicy($resource);
        };

        $msg = 'Failed asserting an exception is thrown by ControllerPolicyResolver->getPolicy(..)';
        $e = $this->catchException($method, [null], $msg);

        $msg = 'Wrong exception thrown by ControllerPolicyResolver->getPolicy(..).';
        $this->assertInstanceOf(MissingPolicyException::class, $e, $msg);
    }

    public function testMissingPolicy(): void
    {
        $resolver = new ControllerPolicyResolver();
        $method = function ($resource) use ($resolver) {
            return $resolver->getPolicy($resource);
        };

        $request = new ServerRequest([
            'params' => [
                'controller' => 'doesNotExist',
                'prefix' => null,
            ],
        ]);

        $msg = 'Failed asserting an exception is thrown by ControllerPolicyResolver->getPolicy(..)';
        $e = $this->catchException($method, [$request], $msg);

        $msg = 'Wrong exception thrown by ControllerPolicyResolver->getPolicy(..).';
        $this->assertInstanceOf(MissingPolicyException::class, $e, $msg);
    }

    public static function validControllers(): array
    {
        return [
            ['Auth'],
            ['Believes'],
            ['Characters'],
            ['CharactersConditions'],
            ['CharactersPowers'],
            ['CharactersSkills'],
            ['Conditions'],
            ['Events'],
            ['Factions'],
            ['Groups'],
            ['Items'],
            ['Lammies'],
            ['Manatypes'],
            ['Players'],
            ['PlayersSocials'],
            ['Powers'],
            ['Root'],
            ['Skills'],
            ['Teachings'],
            ['Worlds'],
            ['Authentication', 'Admin'],
            ['Authorization', 'Admin'],
            ['Backups', 'Admin'],
            ['Checks', 'Admin'],
            ['History', 'Admin'],
            ['Logout', 'Admin'],
            ['Migrations', 'Admin'],
            ['Password', 'Admin'],
            ['Printing', 'Admin'],
            ['Root', 'Admin'],
            ['Routes', 'Admin'],
            ['Skills', 'Admin'],
            ['Social', 'Admin'],
        ];
    }

    #[DataProvider('validControllers')]
    public function testGetPolicy(string $name, ?string $prefix = null): void
    {
        $request = new ServerRequest([
            'params' => [
                'controller' => $name,
                'prefix' => $prefix,
            ],
        ]);

        $resolver = new ControllerPolicyResolver();

        $result = $resolver->getPolicy($request);

        $this->assertInstanceOf(ControllerPolicy::class, $result);
    }
}

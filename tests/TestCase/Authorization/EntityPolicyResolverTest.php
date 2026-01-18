<?php
declare(strict_types=1);

namespace App\Test\TestCase\Authorization;

use App\Authorization\EntityPolicyResolver;
use App\Policy\Entity\EntityPolicy;
use App\Test\TestSuite\TestCase;
use Authorization\Policy\Exception\MissingPolicyException;
use Cake\Datasource\EntityInterface;
use PHPUnit\Framework\Attributes\DataProvider;

class EntityPolicyResolverTest extends TestCase
{
    public function testInvalidResource(): void
    {
        $resolver = new EntityPolicyResolver();
        $method = function ($resource) use ($resolver) {
            return $resolver->getPolicy($resource);
        };

        $msg = 'Failed asserting an exception is thrown by EntityPolicyResolver->getPolicy(..)';
        $e = $this->catchException($method, [null], $msg);

        $msg = 'Wrong exception thrown by EntityPolicyResolver->getPolicy(..).';
        $this->assertInstanceOf(MissingPolicyException::class, $e, $msg);
    }

    public function testMissingPolicy(): void
    {
        $entity = $this->createStub(EntityInterface::class);
        $this->assertInstanceOf(EntityInterface::class, $entity);

        $resolver = new EntityPolicyResolver();
        $method = function ($resource) use ($resolver) {
            return $resolver->getPolicy($resource);
        };

        $msg = 'Failed asserting an exception is thrown by EntityPolicyResolver->getPolicy(..)';
        $e = $this->catchException($method, [$entity], $msg);

        $msg = 'Wrong exception thrown by EntityPolicyResolver->getPolicy(..).';
        $this->assertInstanceOf(MissingPolicyException::class, $e, $msg);
    }

    public static function validEntities(): array
    {
        return [
            ['Character'],
            ['CharactersCondition'],
            ['CharactersPower'],
            ['CharactersSkill'],
            ['Condition'],
            ['Event'],
            ['Faction'],
#            ['History'],
            ['Item'],
            ['Lammy'],
            ['Manatype'],
            ['Player'],
            ['Power'],
            ['Skill'],
            ['SocialProfile'],
#            ['Teaching'],
        ];
    }

    #[DataProvider('validEntities')]
    public function testGetPolicy(string $name): void
    {
        $entity = new ('\\App\\Model\\Entity\\' . $name)();
        $this->assertInstanceOf(EntityInterface::class, $entity);

        $result = (new EntityPolicyResolver())->getPolicy($entity);

        $this->assertInstanceOf(EntityPolicy::class, $result);
    }
}

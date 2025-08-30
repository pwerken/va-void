<?php
declare(strict_types=1);

namespace App\Test\TestCase\Policy;

use App\Model\Entity\Player;
use App\Test\TestSuite\TestCase;
use Cake\Core\App;
use PHPUnit\Framework\Attributes\DataProvider;
use RuntimeException;

class EntityPolicyTest extends TestCase
{
    public static function cannotViewEntities(): array
    {
        return [
            ['CharactersSkill'],
            ['CharactersPower'],
            ['CharactersCondition'],
        ];
    }

    #[DataProvider('cannotViewEntities')]
    public function testCanViewRuntimeException(string $entityClass): void
    {
        $user = $this->createStub(Player::class);

        $objClass = App::className($entityClass, 'Model/Entity');
        $obj = $this->createStub($objClass);

        $policyClass = App::className($entityClass, 'Policy/Entity', 'Policy');
        $policy = new $policyClass();

        $e = $this->catchException([$policy, 'canView'], [$user, $obj]);
        $this->assertInstanceOf(RuntimeException::class, $e);
    }

    public static function disallowedPolicies(): array
    {
        return [
            ['SocialProfile', 'canAdd'],
            ['SocialProfile', 'canEdit'],
            ['Teaching', 'canEdit'],
        ];
    }

    #[DataProvider('disallowedPolicies')]
    public function testDisallowed(string $entityClass, string $method): void
    {
        $user = $this->createStub(Player::class);

        $objClass = App::className($entityClass, 'Model/Entity');
        $obj = $this->createStub($objClass);

        $policyClass = App::className($entityClass, 'Policy/Entity', 'Policy');
        $policy = new $policyClass();

        $this->assertFalse(call_user_func_array([$policy, $method], [$user, $obj]));
    }

    public static function hasRoleUserEntities(): array
    {
        return [
            ['Power'],
            ['Condition'],
            ['Item'],
        ];
    }

    #[DataProvider('hasRoleUserEntities')]
    public function testHasRoleUserWithNull(string $entityClass): void
    {
        $policyClass = App::className($entityClass, 'Policy/Entity', 'Policy');

        $policy = new $policyClass();
        $hasRoleUser = $this->protectedMethod($policy, 'hasRoleUser');

        $this->assertFalse(call_user_func_array($hasRoleUser, [0, null]));
    }
}

<?php
declare(strict_types=1);

namespace App\Test\TestCase\Authorization;

use App\Authorization\TablePolicyResolver;
use App\Policy\Policy;
use App\Test\TestSuite\TestCase;
use Authorization\Policy\Exception\MissingPolicyException;
use Cake\Datasource\QueryInterface;
use Cake\ORM\Table;
use PHPUnit\Framework\Attributes\DataProvider;
use RuntimeException;

class TablePolicyResolverTest extends TestCase
{
    public function testNullResource(): void
    {
        $resolver = new TablePolicyResolver();
        $method = function ($resource) use ($resolver) {
            return $resolver->getPolicy($resource);
        };

        $msg = 'Failed asserting an exception is thrown by TablePolicyResolver->getPolicy(..)';
        $e = $this->catchException($method, [null], $msg);

        $msg = 'Wrong exception thrown by TablePolicyResolver->getPolicy(..).';
        $this->assertInstanceOf(MissingPolicyException::class, $e, $msg);
    }

    public function testBrokenQuery(): void
    {
        $resolver = new TablePolicyResolver();
        $method = function ($resource) use ($resolver) {
            return $resolver->getPolicy($resource);
        };

        $query = $this->createStub(QueryInterface::class);
        $this->assertInstanceOf(QueryInterface::class, $query);
        $this->assertNull($query->getRepository());

        $msg = 'Failed asserting an exception is thrown by TablePolicyResolver->getPolicy(..)';
        $e = $this->catchException($method, [$query], $msg);

        $msg = 'Wrong exception thrown by TablePolicyResolver->getPolicy(..).';
        $this->assertInstanceOf(RuntimeException::class, $e, $msg);
    }

    public function testMissingPolicy(): void
    {
        $resolver = new TablePolicyResolver();
        $method = function ($resource) use ($resolver) {
            return $resolver->getPolicy($resource);
        };

        $table = $this->createStub(Table::class);
        $query = $this->createStub(QueryInterface::class);
        $query->method('getRepository')->willReturn($table);

        $this->assertInstanceOf(QueryInterface::class, $query);
        $this->assertInstanceOf(Table::class, $query->getRepository());

        $msg = 'Failed asserting an exception is thrown by TablePolicyResolver->getPolicy(..)';
        $e = $this->catchException($method, [$query], $msg);

        $msg = 'Wrong exception thrown by TablePolicyResolver->getPolicy(..).';
        $this->assertInstanceOf(MissingPolicyException::class, $e, $msg);
    }

    public static function validTables(): array
    {
        return [
            ['Characters'],
#            ['CharactersConditions'],
#            ['CharactersPowers'],
#            ['CharactersSkills'],
            ['Conditions'],
#            ['Events'],
#            ['Factions'],
#            ['History'],
            ['Items'],
#            ['Lammies'],
#            ['Manatypes'],
            ['Players'],
            ['Powers'],
#            ['Skills'],
#            ['SocialProfiles'],
#            ['Teachings'],
        ];
    }

    #[DataProvider('validTables')]
    public function testGetPolicy(string $name): void
    {
        $table = new ('\\App\\Model\\Table\\' . $name . 'Table')();
        $this->assertInstanceOf(Table::class, $table);

        $result = (new TablePolicyResolver())->getPolicy($table);

        $this->assertInstanceOf(Policy::class, $result);
    }
}

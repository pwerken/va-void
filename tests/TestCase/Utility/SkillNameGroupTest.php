<?php
declare(strict_types=1);

namespace App\Test\TestCase\Utility;

use App\Test\TestSuite\TestCase;
use App\Utility\SkillNameGroup;
use PHPUnit\Framework\Attributes\DataProvider;
use RuntimeException;

class SkillNameGroupTest extends TestCase
{
    public function testGroup()
    {
        $input = [];
        $input[] = 'ambidextry (2)';
        $input[] = 'lifeforce A (1)';
        $input[] = 'lifeforce B (2)';
        $input[] = 'priest A (purity) (4)';
        $input[] = 'priest B (wrath) (4)';
        $input[] = 'priest C (purity) (4)';
        $input[] = 'mage A (black) (4)';
        $input[] = 'armour I (0)';
        $input[] = 'armour II (2)';
        $input[] = 'armour III (2)';
        $input[] = 'armour IV (2)';
        $input[] = 'immune to poisons (2)';
        $input[] = 'immune to diseases (2)';
        $input[] = 'mage B (black) (4)';

        $expected = [];
        $expected[] = 'ambidextry (2)';
        $expected[] = 'lifeforce A+B (3)';
        $expected[] = 'priest A+C (purity) (8)';
        $expected[] = 'priest B (wrath) (4)';
        $expected[] = 'mage A+B (black) (8)';
        $expected[] = 'armour I+II+III+IV (6)';
        $expected[] = 'immune to poisons (2)';
        $expected[] = 'immune to diseases (2)';

        $this->assertEquals($expected, SkillNameGroup::group($input));
    }

    public static function validSplitterCases(): array
    {
        return [
            ['aaaaaaa (1)', ['aaaaaaa', '', '', '1']],
            ['bbbbb B (2)', ['bbbbb', 'B', '', '2']],
            ['ccc C C (3)', ['ccc C', 'C', '', '3']],
            ['d D (d) (4)', ['d', 'D', '(d)', '4']],
            ['eeee IV (5)', ['eeee', 'IV', '', '5']],
        ];
    }

    #[DataProvider('validSplitterCases')]
    public function testSplitter(string $input, array $expected): void
    {
        $method = $this->protectedStaticMethod(SkillNameGroup::class, 'splitter');
        $this->assertEquals($expected, call_user_func($method, $input));
    }

    public static function invalidSplitterCases(): array
    {
        return [
            ['ambidexterity'],
            ['lifeforce B'],
        ];
    }

    #[DataProvider('invalidSplitterCases')]
    public function testSplitterException(string $input): void
    {
        $method = $this->protectedStaticMethod(SkillNameGroup::class, 'splitter');

        $msg = 'Failed asserting an exception is thrown by SkillNameGroup::splitter(..).';
        $e = $this->catchException($method, [$input], $msg);

        $msg = 'Wrong exception thrown by SkillNameGroup::splitter(..).';
        $this->assertInstanceOf(RuntimeException::class, $e, $msg);
    }
}

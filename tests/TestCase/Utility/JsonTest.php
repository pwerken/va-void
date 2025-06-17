<?php
declare(strict_types=1);

namespace App\Test\TestCase\Utility;

use App\Test\TestSuite\TestCase;
use App\Utility\Json;
use PHPUnit\Framework\Attributes\DataProvider;

class JsonTest extends TestCase
{
    public static function decodeCases(): array
    {
        return [
            [null, null],
            ['null', null],
            ['', null],
            ['f00bar', null],
            ['"double"', 'double'],
            ['2', 2],
            ['false', false],
            ['[]', []],
            ['{}', []],
        ];
    }

    public static function encodeCases(): array
    {
        return [
            [null, null],
            ['string', '"string"'],
            [2, '2'],
            [false, 'false'],
            [[], '[]'],
        ];
    }

    #[DataProvider('decodeCases')]
    public function testDecode(string|null $input, mixed $expected): void
    {
        $this->assertEquals($expected, Json::decode($input));
    }

    #[DataProvider('encodeCases')]
    public function testEncode(mixed $input, string|null $expected): void
    {
        $this->assertEquals($expected, Json::encode($input));
    }
}

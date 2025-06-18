<?php
declare(strict_types=1);

namespace App\Test\TestCase\Utility;

use App\Test\TestSuite\TestCase;
use App\Utility\CheckConfig;
use PHPUnit\Framework\Attributes\DataProvider;

class CheckConfigTest extends TestCase
{
    public static function installationResults(): array
    {
        $results = [];
        foreach (CheckConfig::installation() as $msg => $state) {
            $results[] = [$msg, $state];
        }

        return $results;
    }

    public function testInstallationHasChecks(): void
    {
        $results = CheckConfig::installation();

        $this->assertIsArray($results);
        $this->assertNotEmpty($results);
    }

    #[DataProvider('installationResults')]
    public function testInstallation(mixed $msg, mixed $state): void
    {
        $this->assertIsString($msg);
        $this->assertNotEmpty($msg);

        $this->assertIsBool($state);
        $this->assertTrue($state);
    }
}

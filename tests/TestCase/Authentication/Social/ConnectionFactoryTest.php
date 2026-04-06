<?php
declare(strict_types=1);

namespace App\Test\TestCase\Authentication\Social;

use App\Authentication\Social\CollectionFactory;
use App\Test\TestSuite\TestCase;

/**
 * ApplicationTest class
 */
class ConnectionFactoryTest extends TestCase
{
    public function testProviders(): void
    {
        $factory = new CollectionFactory();

        $providers = $factory->getProviders();
        $this->assertCount(4, $providers);

        foreach ($providers as $name => $class) {
            $this->assertIsString($name);
            $this->assertIsString($class);
        }
    }
}

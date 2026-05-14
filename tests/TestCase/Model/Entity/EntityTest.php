<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\Entity;
use App\Test\TestSuite\TestCase;

class EntityTest extends TestCase
{
    public function testEmptyToDefault(): void
    {
        $e = new class extends Entity {
            protected array $_defaults = [ 'name' => 'bar' ];

            protected function _setName(mixed $name): mixed
            {
                return $this->emptyToDefault('name', $name);
            }
        };

        $this->assertEquals('bar', $e->get('name'));

        $e->set('name', 'f00');
        $this->assertEquals('f00', $e->get('name'));

        $e->set('name', null);
        $this->assertEquals('bar', $e->get('name'));
    }
}

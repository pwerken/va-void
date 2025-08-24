<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\History;
use App\Test\TestSuite\TestCase;

class HistoryTest extends TestCase
{
    public function testCompare(): void
    {
        $a = new History();
        $b = new History();

        $this->assertEquals(0, History::compare(null, null));
        $this->assertEquals(1, History::compare(null, $b));
        $this->assertEquals(-1, History::compare($a, null));

        $a->set('modified', '2025-08-24 20:22:10');
        $b->set('modified', '2025-08-24 21:22:10');
        $this->assertGreaterThan(0, History::compare($a, $b));
        $this->assertLessThan(0, History::compare($b, $a));

        $b->set('modified', $a->get('modified'));
        $a->set('entity', 'someAap');
        $b->set('entity', 'someNootMies');
        $this->assertGreaterThan(0, History::compare($a, $b));
        $this->assertLessThan(0, History::compare($b, $a));

        $b->set('entity', 'someNoot');
        $this->assertGreaterThan(0, History::compare($a, $b));
        $this->assertLessThan(0, History::compare($b, $a));

        $b->set('entity', $a->get('entity'));
        $a->set('key1', 1);
        $b->set('key1', 2);
        $this->assertLessThan(0, History::compare($a, $b));
        $this->assertGreaterThan(0, History::compare($b, $a));

        $b->set('key1', $a->get('key1'));
        $a->set('key2', 1);
        $b->set('key2', 2);
        $this->assertGreaterThan(0, History::compare($a, $b));
        $this->assertLessThan(0, History::compare($b, $a));

        $b->set('key2', $a->get('key2'));
        $this->assertEquals(0, History::compare($a, $b));
    }

    public function testModifiedString(): void
    {
        $h = new History();
        $date = '2025-08-24 20:22:10';

        $this->assertNull($h->get('modified'));
        $this->assertEquals('(??)', $h->modifiedString());

        $h->set('modified', $date);
        $this->assertEquals($date, $h->modifiedString());
    }

    public function testModifierString(): void
    {
        $h = new History();

        $this->assertNull($h->get('modifier_id'));
        $this->assertEquals('(??)', $h->modifierString());

        $h->set('modifier_id', -1);
        $this->assertEquals('_cli', $h->modifierString());

        $h->set('modifier_id', 3);
        $this->assertEquals('0003', $h->modifierString());
    }
}

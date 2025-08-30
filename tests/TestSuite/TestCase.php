<?php
declare(strict_types=1);

namespace App\Test\TestSuite;

use Cake\I18n\DateTime;
use Cake\TestSuite\TestCase as CakeTestCase;
use Closure;
use ReflectionClass;
use ReflectionObject;
use Throwable;

class TestCase extends CakeTestCase
{
    protected ?DateTime $now = null;

    public function catchException(callable $func, ?array $args = [], ?string $message = null): Throwable
    {
        try {
            call_user_func_array($func, $args);
        } catch (Throwable $t) {
            return $t;
        }
        $this->fail($message ?? 'Failed asserting that an exception is thrown.');
    }

    public function protectedMethod(object $obj, string $method): Closure
    {
        $c = new ReflectionObject($obj);
        $m = $c->getMethod($method);

        if (!$m->isProtected() || $m->isStatic() || $m->isAbstract()) {
            $class = get_class($obj);
            $this->fail("Not a protected method {$class}::{$method}");
        }

        return $m->getClosure($obj);
    }

    public function protectedStaticMethod(string $class, string $method): Closure
    {
        $c = new ReflectionClass($class);
        $m = $c->getMethod($method);

        if (!$m->isProtected() || !$m->isStatic() || $m->isAbstract()) {
            $this->fail("Not a protected static method {$class}::{$method}");
        }

        return $m->getClosure();
    }

    public function assertArrayKeyValue(mixed $key, mixed $value, array $actual): void
    {
        $this->assertArrayHasKey($key, $actual);

        $message = sprintf('Failed assert on value of array[ `%s` ].', print_r($key, true));
        $this->assertEquals($value, $actual[$key], $message);
    }

    public function assertDateTime(string|DateTime $expected, string $actual): void
    {
        if ($expected instanceof DateTime) {
            $e = $expected;
        } else {
            $e = new DateTime($expected, 'UTC');
        }

        $a = new DateTime($actual, 'UTC');
        $this->assertEqualsWithDelta($e, $a, 1.0);
    }

    public function assertDateTimeNow(string $actual): void
    {
        $this->assertNotNull($this->now);
        $this->assertDateTime($this->now, $actual);
    }
}

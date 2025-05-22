<?php
declare(strict_types=1);

namespace App\Test\TestSuite;

use Cake\I18n\DateTime;
use Cake\TestSuite\TestCase as CakeTestCase;
use Closure;
use ReflectionClass;
use Throwable;

class TestCase extends CakeTestCase
{
    public function catchException(Closure $func, ?array $args = [], ?string $message = null): Throwable
    {
        try {
            call_user_func_array($func, $args);
        } catch (Throwable $t) {
            return $t;
        }
        $this->fail($message ?? 'No exception was caught');
    }

    public function protectedStaticMethod(string $class, string $method): Closure
    {
        $c = new ReflectionClass($class);
        $m = $c->getMethod($method);

        if (!$m->isProtected() || !$m->isStatic()) {
            $this->fail("Not a protected static method $class::$method");
        }

        return $m->getClosure();
    }

    public function assertArrayKeyValue(mixed $key, mixed $value, array $actual): void
    {
        $this->assertArrayHasKey($key, $actual);

        $message = sprintf(
            'Failed to assert array key `%s` with value `%s` matches expected `%s`',
            print_r($key, true),
            print_r($actual[$key], true),
            print_r($value, true),
        );
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
        static $now = null;
        if (is_null($now)) {
            $now = DateTime::now('UTC');
        }
        $this->assertDateTime($now, $actual);
    }
}

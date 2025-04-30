<?php
declare(strict_types=1);

namespace App\Test\TestSuite;

use Cake\TestSuite\TestCase as CakeTestCase;
use Closure;
use ReflectionClass;
use Throwable;

class TestCase extends CakeTestCase
{
    public function catchException(Closure $func, ?array $args = [], ?string $msg = null): Throwable
    {
        try {
            call_user_func_array($func, $args);
        } catch (Throwable $t) {
            return $t;
        }
        $this->fail($msg ?? 'No exception was caught');
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
}

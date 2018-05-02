<?php declare(strict_types=1);
/**
 * Created by IntelliJ IDEA.
 * User: richardmiles
 * Date: 5/2/18
 * Time: 12:05 PM
 */


use PHPUnit\Framework\TestCase;

final class ExampleTest extends TestCase
{
    public function testPushAndPop()
    {
        $stack = [];
        $this->assertSame(0, count($stack));

        array_push($stack, 'foo');
        $this->assertSame('foo', $stack[count($stack)-1]);
        $this->assertSame(1, count($stack));

        $this->assertSame('foo', array_pop($stack));
        $this->assertSame(0, count($stack));
    }
}


<?php
/**
 * Created by IntelliJ IDEA.
 * User: richardmiles
 * Date: 5/2/18
 * Time: 12:50 PM
 */

/** @noinspection PhpUndefinedClassInspection */

use Carbon\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{

    public function testAlunmAcceptsAlphaAndNumbers()
    {
        $this->assertSame('String', (new Request())->set('String')->alnum());
    }
}

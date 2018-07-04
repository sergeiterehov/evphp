<?php

use evphp\helpers\Vector;

class VectorTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    // tests
    public function testArrayAccess()
    {
        $a = [];
        $v = new Vector;

        $v[] = 1;
        $a[] = 1;
        $v[] = 2.34;
        $a[] = 2.34;
        $v[] = 'a';
        $a[] = 'a';
        $v[] = true;
        $a[] = true;
        $v[] = null;
        $a[] = null;

        $this->assertEquals(iterator_to_array($v), $a, 'Behavior must be like real array on push');

        $stringValue = 'string-value';

        $v['abc'] = $stringValue;
        $a['abc'] = $stringValue;

        $this->assertArrayHasKey('abc', $v, 'Must support string keys');
        $this->assertEquals($v['abc'], $stringValue, 'Must correctly return value by string key');

        unset($v[1]);
        unset($a[1]);
        unset($v['abc']);
        unset($a['abc']);

        $this->assertEquals(iterator_to_array($v), $a, 'Must support unset');

        $this->assertEquals(json_encode($v), '{"0":1,"2":"a","3":true,"4":null}', 'Must be serializable');
    }

    public function testExceptionOnArray()
    {
        $this->expectException(\Exception::class);

        $v = new Vector;

        $v[] = [];
    }

    public function testExceptionOnObject()
    {
        $this->expectException(\Exception::class);

        $v = new Vector;

        $v[] = (object) ['a' => 1];
    }

    public function testAllowVector()
    {
        $v = new Vector;

        $v['x'] = new Vector(['a', 'y' => 'b', 'z' => new Vector([1, 2, 3])]);

        $this->assertEquals(json_encode($v), '{"x":{"0":"a","y":"b","z":[1,2,3]}}', 'Must have correctly structure on json_encode');
    }
}
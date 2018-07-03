<?php

use evphp\IO;

class SingleModuleTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    // tests
    public function testHello()
    {
        $name = new IO;

        $hello = new modules\HelloModule;

        $hello['inName'] = $name;
        $message = $hello['outMessage'];

        $name->set('Ivan');
        $this->assertEquals($message->get(), 'Hello, Ivan!');

        $name->set('Alex');
        $this->assertEquals($message->get(), 'Hello, Alex!');

        $name->set('Sergei');
        $this->assertEquals($message->get(), 'Hello, Sergei!');

        $name->set(null);
        $this->assertEquals($message->get(), 'Hello, !');

        $name->set(123);
        $this->assertEquals($message->get(), 'Hello, 123!');
    }

    public function testStorage()
    {
        $index = new IO;
        $sync = new IO;

        $storage = new modules\StorageModule;

        $storage['inIndex'] = $index;
        $storage['inSync'] = $sync;
        $value = $storage['outValue'];
        $length = $storage['outLength'];
        $error = $storage['outError'];

        $this->assertEquals($length->get(), null);

        $sync->set(true);
        $this->assertEquals($length->get(), 2);

        $this->assertEquals($value->get(), null);

        $index->set(0);
        $this->assertEquals($value->get(), 'Hello');

        $index->set(1);
        $this->assertEquals($value->get(), 'world');
        $this->assertEquals($error->get(), false);

        $index->set(3);
        $this->assertEquals($error->get(), true);
        $this->assertEquals($value->get(), null);
    }
}
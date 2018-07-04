<?php

use evphp\IO;

class SingleModuleTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    // tests
    public function testHelloModule()
    {
        $name = new IO;

        $hello = new \modules\HelloModule;

        $hello['inName'] = $name;
        $message = $hello['outMessage'];

        $name->set('Ivan');
        $this->assertEquals($message->get(), 'Hello, Ivan!', 'Must correctly allow string');

        $name->set('Alex');
        $this->assertEquals($message->get(), 'Hello, Alex!', 'Must correctly reflecting on changes');

        $name->set('Sergei');
        $this->assertEquals($message->get(), 'Hello, Sergei!');

        $name->set(null);
        $this->assertEquals($message->get(), 'Hello, !', 'Must correctly allow null');

        $name->set(123);
        $this->assertEquals($message->get(), 'Hello, 123!', 'Must correctly allow number');
    }

    public function testStorageModule()
    {
        $index = new IO;
        $sync = new IO;

        $storage = new \modules\StorageModule;

        $storage['inIndex'] = $index;
        $storage['inSync'] = $sync;
        $value = $storage['outValue'];
        $length = $storage['outLength'];
        $error = $storage['outError'];

        $this->assertEquals($length->get(), null, 'Must not have length-signal before reset-signal');

        $sync->set(true);
        $this->assertEquals($length->get(), 2, 'Return correctly number amount of elements');

        $this->assertEquals($value->get(), null);

        $index->set(0);
        $this->assertEquals($value->get(), 'Hello');

        $index->set(1);
        $this->assertEquals($value->get(), 'world');
        $this->assertEquals($error->get(), false, 'Must not have error signal when element exists');

        $index->set(3);
        $this->assertEquals($error->get(), true);
        $this->assertEquals($value->get(), null, 'Must have error signal when element not exists');
    }

    public function testUsingVector()
    {
        $names = new IO;

        $hellos = new \modules\HelloVectorModule;

        $hellos['names'] = $names;
        $messages = $hellos['messages'];

        $names->set(new \evphp\helpers\Vector(['ivan', 'Alex']));

        \Codeception\Util\Debug::debug(print_r($messages->get(), true));
    }
}
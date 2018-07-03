<?php

namespace modules;

use evphp\Module;

/**
 * @property string $inName
 * @property string $outMessage
 */
class HelloModule extends Module
{
    protected function schema()
    {
        $this->in('inName');
        $this->out('outMessage');

        $this->always('inName')->do(function () {
            $this->outMessage = "Hello, {$this->inName}!";
        });
    }
}
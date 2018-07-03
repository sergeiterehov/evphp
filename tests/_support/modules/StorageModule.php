<?php

namespace modules;

use evphp\Module;

class StorageModule extends Module
{
    private $source = ['Hello', 'world'];

    protected function schema()
    {
        $this->in('inIndex', 'inSync');
        $this->out('outValue', 'outLength', 'outError');

        $this->always('inIndex')->do(function () {
            $error = ! isset($this->source[$this->inIndex]);

            if ($error) {
                $this->outValue = null;
                $this->outError = true;
            } else {
                $this->outValue = $this->source[$this->inIndex];
                $this->outError = false;
            }
        });

        $this->toPositive('inSync')->do(function () {
            $this->outLength = count($this->source);
        });
    }
}
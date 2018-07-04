<?php
/**
 * Created by PhpStorm.
 * User: sterehov
 * Date: 04.07.18
 * Time: 19:27
 */

namespace modules;


use evphp\helpers\Vector;
use evphp\Module;

/**
 * Class HelloVectorModule
 *
 * @package modules
 *
 * @property Vector $names
 * @property Vector $messages
 */
class HelloVectorModule extends Module
{
    protected function schema()
    {
        $this->in('names');
        $this->out('messages');

        $this->messages = new Vector;

        // TODO: reflection ???

        $this->always('names')->do(function() {
            $this->messages->clean();

            foreach ($this->names as $name) {
                $this->messages[] = "Hello, {$name}!";
            }
        });
    }
}
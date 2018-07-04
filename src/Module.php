<?php

namespace evphp;


/**
 * Module - main logic block
 *
 * @package evphp
 */
abstract class Module implements \ArrayAccess
{
    /**
     * @var IO[]
     */
    private $inputs = [];
    /**
     * @var IO[]
     */
    private $outputs = [];

    abstract protected function schema();

    public function __construct()
    {
        $this->schema();
    }

    /**
     * Register inputs by names
     *
     * @param string ...$names
     * @return $this
     */
    protected function in(string ... $names)
    {
        foreach ($names as $name) {
            $this->inputs[$name] = new IO($this);
        }

        return $this;
    }

    /**
     * Register outputs by names
     *
     * @param string ...$names
     * @return $this
     */
    protected function out(string ... $names)
    {
        foreach ($names as $name) {
            $this->outputs[$name] = new IO($this);
        }

        return $this;
    }

    /**
     * Inputs to IO objects transform
     *
     * @param string[] $names
     * @return IO[]
     */
    private function inputsToIO(array $names)
    {
        return array_map(function (string $name) {
            return $this->inputs[$name];
        }, $names);
    }

    /**
     * On any changes
     *
     * @param string ...$names
     * @return Listener
     */
    public function always(string ... $names)
    {
        return new Listener(null, ... $this->inputsToIO($names));
    }

    /**
     * On like true changes
     *
     * @param string ...$names
     * @return Listener
     */
    public function toPositive(string ... $names)
    {
        return new Listener(IO::EDGE_POSITIVE, ... $this->inputsToIO($names));
    }

    /**
     * On like false changes
     *
     * @param string ...$names
     * @return Listener
     */
    public function toNegative(string ... $names)
    {
        return new Listener(IO::EDGE_POSITIVE, ... $this->inputsToIO($names));
    }

    /**
     * Link input IO by name
     *
     * @param mixed $offset
     * @param mixed $value
     * @throws \Exception
     */
    public function offsetSet($offset, $value)
    {
        if (! isset($this->inputs[$offset])) {
            throw new \Exception("Input '{$offset}' not found");
        }

        $this->inputs[$offset]->link($value);
    }

    /**
     * Return output IO by name
     *
     * @param mixed $offset
     * @return mixed
     * @throws \Exception
     */
    public function offsetGet($offset)
    {
        if (! isset($this->outputs[$offset])) {
            throw new \Exception("Output '{$offset}' not found");
        }

        return $this->outputs[$offset];
    }

    /**
     * Set output state by name
     *
     * @param $name
     * @param $value
     * @throws
     */
    public function __set($name, $value)
    {
        $this->outputs[$name]->set($value);
    }

    /**
     * Return input state by name
     *
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return ($this->inputs[$name] ?? $this->outputs[$name])->get();
    }

    /**
     * @param mixed $offset
     * @throws \Exception
     */
    public function offsetUnset($offset)
    {
        throw new \Exception("Not available");
    }

    public function offsetExists($offset)
    {
        return isset($this->inputs[$offset]) || isset($this->outputs[$offset]);
    }
}
<?php

namespace evphp;


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
     * Инициализирует вход
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
     * Инициализирует выход
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
     * Выполняет преобразование имен входов в объекты IO
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
     * При любом изменении сигнала
     *
     * @param string ...$names
     * @return Listener
     */
    public function always(string ... $names)
    {
        return new Listener(null, ... $this->inputsToIO($names));
    }

    /**
     * При переходе в условно-истинное состояние
     *
     * @param string ...$names
     * @return Listener
     */
    public function toPositive(string ... $names)
    {
        return new Listener(IO::EDGE_POSITIVE, ... $this->inputsToIO($names));
    }

    /**
     * При переходе в условно-ложное состояние
     *
     * @param string ...$names
     * @return Listener
     */
    public function toNegative(string ... $names)
    {
        return new Listener(IO::EDGE_POSITIVE, ... $this->inputsToIO($names));
    }

    /**
     * Устанавливает значение выхода
     *
     * @param mixed $offset
     * @param mixed $value
     * @throws \Exception
     */
    public function offsetSet($offset, $value)
    {
        if (! isset($this->inputs[$offset])) {
            throw new \Exception("Вход '{$offset}' не найден");
        }

        $this->inputs[$offset]->link($value);
    }

    /**
     * Возвращает значение входа
     *
     * @param mixed $offset
     * @return mixed
     * @throws \Exception
     */
    public function offsetGet($offset)
    {
        if (! isset($this->outputs[$offset])) {
            throw new \Exception("Выход '{$offset}' не найден");
        }

        return $this->outputs[$offset];
    }

    /**
     * Связывание входа
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
     * Возвращает выход
     *
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->inputs[$name]->get();
    }

    public function offsetUnset($offset)
    {
        throw new \Exception("Не доступно");
    }

    public function offsetExists($offset)
    {
        return isset($this->inputs[$offset]) || isset($this->outputs[$offset]);
    }
}
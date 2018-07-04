<?php

namespace evphp\helpers;


use evphp\IO;

final class Vector implements Scalar, \ArrayAccess, \IteratorAggregate, \JsonSerializable
{
    private $list = [];

    /**
     * Vector constructor.
     *
     * @param array|null $array
     */
    public function __construct(array $array = null)
    {
        if ($array) {
            foreach ($array as $key => $item) {
                $this[$key] = $item;
            }
        }
    }

    /**
     * Clean current array
     *
     * @return $this
     */
    public function clean()
    {
        $this->list = [];

        return $this;
    }

    /**
     * @return null|bool|string|int|float|Scalar
     */
    public function ioScalarPresentationValue()
    {
        return json_encode($this);
    }

    /**
     * @param mixed $offset
     * @return null|bool|string|int|float|Scalar
     */
    public function offsetGet($offset)
    {
        return $this->list[$offset];
    }

    /**
     * @param mixed $offset
     * @param null|bool|string|int|float|Scalar $value
     * @throws
     */
    public function offsetSet($offset, $value)
    {
        IO::testAvailableValue($value);

        if (is_null($offset)) {
            $this->list[] = $value;
        } else {
            $this->list[$offset] = $value;
        }
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->list[$offset]);
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->list[$offset]);
    }

    /**
     * @return \ArrayIterator|\Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->list);
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return $this->list;
    }
}
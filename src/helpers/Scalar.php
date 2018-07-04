<?php

namespace evphp\helpers;


/**
 * Not scalar to scalar converter
 *
 * @package evphp\interfaces
 */
interface Scalar
{
    /**
     * Return current scalar presentation of object
     *
     * @return null|bool|string|int|float|self
     */
    public function ioScalarPresentationValue();
}
<?php

namespace evphp;


/**
 * IO State listener
 *
 * @package evphp
 */
class Listener
{
    /**
     * @var callable
     */
    private $function;

    /**
     * @var int
     */
    private $edgeSense;

    public function __construct(int $edge = null, IO ... $ios)
    {
        $this->edgeSense = $edge;

        foreach ($ios as $io) {
            $io->listen($this);
        }
    }

    /**
     * Function on any defined change
     *
     * @param callable $function
     * @return $this
     */
    public function do(callable $function)
    {
        $this->function = $function;

        return $this;
    }

    /**
     * Register change
     *
     * @param IO $io
     * @param int $edge
     */
    public function trigger(IO $io, int $edge)
    {
        if (null !== $this->edgeSense && $edge !== $this->edgeSense) {
            return;
        }

        ($this->function)();
    }
}
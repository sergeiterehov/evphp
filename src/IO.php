<?php

namespace evphp;


class IO
{
    const EDGE_POSITIVE = 1;
    const EDGE_NEGATIVE = -1;
    const EDGE_CONTINUED = 0;

    /**
     * @var Module
     */
    private $parent;

    private $value;

    /**
     * @var IO
     */
    private $dependency;
    /**
     * @var IO[]
     */
    protected $refs = [];

    /**
     * @var Listener[]
     */
    private $listeners = [];
    
    public function __construct(Module $parent = null)
    {
        $this->parent = $parent;
    }

    public function listen(Listener $listener)
    {
        $this->listeners[] = $listener;

        return $this;
    }

    public function get()
    {
        return $this->value;
    }

    /**
     * @param $value
     * @return $this
     * @throws \Exception
     */
    public function set($value)
    {
        if (! is_scalar($value) && ! is_null($value)) {
            throw new \Exception("Значение сигнала должно быть скалярным");
        }

        if ($this->value !== $value) {
            $edge = !! $this->value && ! $value ? self::EDGE_NEGATIVE :
                ! $this->value && !! $value ? self::EDGE_POSITIVE :
                    self::EDGE_CONTINUED;

            $this->value = $value;

            foreach ($this->listeners as $listener) {
                $listener->trigger($this, $edge);
            }

            $this->propagate();
        }

        return $this;
    }

    public function link(self $source)
    {
        if ($this->dependency) {
            unset ($this->dependency->refs[array_search($this, $this->dependency->refs, true)]);
        }

        $this->dependency = $source;
        $source->refs[] = $this;

        $source->propagate();

        return $this;
    }

    /**
     * @throws \Exception
     */
    protected function propagate()
    {
        foreach ($this->refs as $ref) {
            $ref->set($this->value);
        }
    }
}
<?php

namespace evphp;

use evphp\helpers\Scalar;


/**
 * Input-output signal
 *
 * @package evphp
 */
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

    /**
     * Return current state
     *
     * @return mixed
     */
    public function get()
    {
        return $this->value;
    }

    /**
     * Set new state
     *
     * @param $value
     * @return $this
     * @throws \Exception
     */
    public function set($value)
    {
        self::testAvailableValue($value);

        $valueCurrent = self::convertValueToScalar($this->value);
        $valueNew = self::convertValueToScalar($value);

        if ($valueCurrent !== $valueNew) {
            $edge = $this->edgeDetect($value);

            $this->value = $value;

            foreach ($this->listeners as $listener) {
                $listener->trigger($this, $edge);
            }

            $this->propagate();
        }

        return $this;
    }

    /**
     * Link current (as target) with source IO object
     *
     * @param self $source
     * @return $this
     * @throws
     */
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

    /**
     * Convert value to scalar for comparing
     *
     * @param $value
     * @return bool|Scalar|float|int|null|string
     * @throws \Exception
     */
    private static function convertValueToScalar($value)
    {
        if (is_scalar($value) || is_null($value)) {
            return $value;
        }

        if ($value instanceof Scalar) {
            return $value->ioScalarPresentationValue();
        }

        throw new \Exception("Value is not available");
    }

    /**
     * Test value for using as scalar.
     * If not available, throw exception.
     *
     * @param $value
     * @throws \Exception
     */
    public static function testAvailableValue($value)
    {
        if (! is_scalar($value) && ! is_null($value) && ! $value instanceof Scalar) {
            throw new \Exception("Value must be scalar or instance of Scalar");
        }
    }

    /**
     * Detect edge type for new value
     *
     * @param $value
     * @return int
     */
    private function edgeDetect($value) : int
    {
        return !! $this->value && ! $value ? self::EDGE_NEGATIVE :
            ! $this->value && !! $value ? self::EDGE_POSITIVE :
                self::EDGE_CONTINUED;
    }
}
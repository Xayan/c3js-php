<?php

namespace C3\Chart\Column;

use InvalidArgumentException;

class MovingAverage extends Column
{
    private $data = [];

    private $steps = 1;

    private $precision = 0;

    /**
     * MovingAverage constructor.
     *
     * @param array $data
     * @param int $steps
     * @param int $precision
     */
    public function __construct(array $data, int $steps, int $precision = 0)
    {
        if($steps < 1 || $steps % 2 === 0) {
            throw new InvalidArgumentException("Invalid argument");
        }

        $this->data = $data;
        $this->steps = $steps;
        $this->precision = $precision;

        parent::__construct(
            $this->getUniqueName(),
            $this->getUniqueLabel(),
            []
        );
    }

    /**
     * @return array
     */
    public function getColumnData(): array
    {
        if(count($this->data) < $this->steps) {
            return array_merge(
                [$this->getName()],
                array_fill(0, count($this->data), null)
            );
        }

        $values = [];
        $periods = intval(($this->steps - 1) / 2);

        for(
            $i = $periods;
            $i < (count($this->data) - $periods);
            $i++
        ) {
            $stepValues = [];

            $its = [];
            for($j = -$periods; $j <= $periods; $j++) {
                $its[]  = array_values($this->data)[$i + $j];
                $stepValues[] = array_values($this->data)[$i + $j];
            }

            $values[] = round(array_sum($stepValues) / count($stepValues), $this->precision);
        }

        return array_merge(
            [$this->getName()],
            array_fill(0, $periods, null),
            $values,
            array_fill(0, $periods, null)
        );
    }

    /**
     * @return string
     */
    private function getUniqueName(): string
    {
        return sprintf('mavg_%d', spl_object_id($this));
    }

    /**
     * @return string
     */
    private function getUniqueLabel(): string
    {
        return sprintf('Moving avg %d', $this->steps);
    }
}
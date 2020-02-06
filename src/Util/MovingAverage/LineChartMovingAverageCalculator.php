<?php

namespace C3\Util\MovingAverage;

use C3\Chart\Column\Column;
use C3\Enum\MovingAverageGapBehavior;
use C3\Exception\MovingAverageCalculatorException;

class LineChartMovingAverageCalculator implements MovingAverageCalculatorInterface
{
    /**
     * @param Column $column
     * @param int $steps
     * @param int $precision
     * @param MovingAverageGapBehavior|null $gapBehavior Throw exception by default
     * @return array
     * @throws MovingAverageCalculatorException
     */
    public function calculate(
        Column $column,
        int $steps,
        int $precision,
        ?MovingAverageGapBehavior $gapBehavior = null
    ): array
    {
        if($gapBehavior === null) {
            $gapBehavior = MovingAverageGapBehavior::THROW_EXCEPTION();
        }

        $values = $this->processArrayByGapBehavior($column->getValues(), $gapBehavior);

        $leftOffset = $rightOffset = floor($steps / 2);

        if($steps % 2 === 0) {
            $rightOffset--;
        }

        if(count($values) < $steps) {
            return array_fill(0, count($values), null);
        }

        $averages = [];

        for($i = $leftOffset; $i < (count($values) - $rightOffset); $i++) {
            $stepValues = [];

            for($j = -$leftOffset; $j <= $rightOffset; $j++) {
                $stepValues[] = $values[$i + $j];
            }

            $averages[] = $this->calculateMovingAverageStep($stepValues, $precision, $gapBehavior);
        }

        return array_merge(
            array_fill(0, $leftOffset, null),
            $averages,
            array_fill(0, $rightOffset, null)
        );
    }

    /**
     * @param array $values
     * @param MovingAverageGapBehavior $gapBehavior
     * @return array
     * @throws MovingAverageCalculatorException
     */
    public function processArrayByGapBehavior(array $values, MovingAverageGapBehavior $gapBehavior): array
    {
        if($gapBehavior == MovingAverageGapBehavior::THROW_EXCEPTION() && in_array(null, $values)) {
            throw new MovingAverageCalculatorException();
        }

        $lastValidValue = reset($values);
        $firstIndex = $lastValidIndex = key($values);
        end($values);
        $lastIndex = key($values);

        for($i = $firstIndex; $i <= $lastIndex; $i++) {
            if(!isset($values[$i])) {
                $values[$i] = null;
            }
        }

        ksort($values);

        if($gapBehavior == MovingAverageGapBehavior::ASSUME_ZERO()) {
            $values = array_replace($values, [null => 0]);
        } elseif($gapBehavior == MovingAverageGapBehavior::AVERAGE()) {
            foreach($values as $i => $value) {
                if($value !== null) {
                    if($i - $lastValidIndex > 1) {
                        $steps = $i - $lastValidIndex - 1;
                        $diff = $values[$i] - $lastValidValue;
                        $stepDiff = $diff / ($steps + 1);
                        $newValue = $lastValidValue;

                        for($j = 1; $j <= $steps; $j++) {
                            $newValue += $stepDiff;
                            $values[$i - ($steps - $j) - 1] = $newValue;
                        }
                    }

                    $lastValidIndex = $i;
                    $lastValidValue = $value;
                }
            }
        }

        return $values;
    }

    private function calculateMovingAverageStep(array $stepValues, int $precision, MovingAverageGapBehavior $gapBehavior): float
    {
        return round(array_sum($stepValues) / count($stepValues), $precision);
    }
}
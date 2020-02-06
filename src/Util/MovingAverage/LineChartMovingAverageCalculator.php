<?php

namespace C3\Util\MovingAverage;

use C3\Chart\Column\Column;
use C3\Enum\MovingAverageGapBehavior;

class LineChartMovingAverageCalculator implements MovingAverageCalculatorInterface
{
    /**
     * @param Column $sourceColumn
     * @param int $steps
     * @param int $precision
     * @param MovingAverageGapBehavior|null $gapBehavior Throw exception by default
     * @return array
     */
    public function calculate(
        Column $sourceColumn,
        int $steps,
        int $precision,
        ?MovingAverageGapBehavior $gapBehavior = null
    ): array
    {
        if($gapBehavior === null) {
            $gapBehavior = MovingAverageGapBehavior::THROW_EXCEPTION();
        }

        $leftOffset = $rightOffset = floor($steps / 2);

        if($steps % 2 === 0) {
            $rightOffset--;
        }

        if(count($sourceColumn->getValues()) < $steps) {
            return array_fill(0, count($sourceColumn->getValues()), null);
        }

        $values = [];

        for(
            $i = $leftOffset;
            $i < (count($sourceColumn->getValues()) - $rightOffset);
            $i++
        ) {
            $stepValues = [];

            for($j = -$leftOffset; $j <= $rightOffset; $j++) {
                $stepValues[] = $sourceColumn->getValues()[$i + $j];
            }

            $values[] = $this->calculateMovingAverageStep($stepValues, $precision, $gapBehavior);
        }

        return array_merge(
            array_fill(0, $leftOffset, null),
            $values,
            array_fill(0, $rightOffset, null)
        );
    }

    private function calculateMovingAverageStep(array $stepValues, int $precision, MovingAverageGapBehavior $gapBehavior): float
    {
        return round(array_sum($stepValues) / count($stepValues), $precision);
    }
}
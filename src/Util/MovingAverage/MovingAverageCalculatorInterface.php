<?php

namespace C3\Util\MovingAverage;

use C3\Chart\Column\Column;
use C3\Enum\MovingAverageGapBehavior;

interface MovingAverageCalculatorInterface
{
    /**
     * @param Column $column
     * @param int $steps
     * @param int $precision
     * @param MovingAverageGapBehavior $gapBehavior
     * @return array
     */
    public function calculate(
        Column $column,
        int $steps,
        int $precision,
        MovingAverageGapBehavior $gapBehavior
    ): array;
}
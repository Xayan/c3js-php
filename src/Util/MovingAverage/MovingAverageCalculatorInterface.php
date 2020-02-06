<?php

namespace C3\Util\MovingAverage;

use C3\Chart\Column\Column;
use C3\Enum\MovingAverageGapBehavior;

interface MovingAverageCalculatorInterface
{
    /**
     * @param Column $sourceColumn
     * @param int $steps
     * @param int $precision
     * @param MovingAverageGapBehavior $gapBehavior
     * @return array
     */
    public function calculate(
        Column $sourceColumn,
        int $steps,
        int $precision,
        MovingAverageGapBehavior $gapBehavior
    ): array;
}
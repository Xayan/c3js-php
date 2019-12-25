<?php

namespace C3\Enum;

use MyCLabs\Enum\Enum;

/**
 * @see TimeseriesChartFactory
 *
 * @method static STEPS()
 * @method static PRECISION()
 */
class MovingAverageProperty extends Enum
{
    const STEPS = 'steps';
    const PRECISION = 'precision';
}
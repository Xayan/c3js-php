<?php

namespace C3\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static AVERAGE()
 * @method static OMIT_VALUE()
 * @method static SET_NULL_STEP()
 * @method static SET_NULL_RANGE()
 * @method static ASSUME_ZERO()
 * @method static THROW_EXCEPTION()
 */
class MovingAverageGapBehavior extends Enum
{
    const AVERAGE = 'average'; // calculate average between two known values
    const OMIT_VALUE = 'omit_value'; // value will not be considered in calculating moving average
    const SET_NULL_STEP = 'set_null_step'; // result of moving average will be null in a single step
    const SET_NULL_RANGE = 'set_null_range'; // result of moving average will be null in the entire range
    const ASSUME_ZERO = 'assume_zero'; // nulls or missing values will be treated as 0
    const THROW_EXCEPTION = 'throw_exception'; // if a missing value or a null is found an exception will be thrown
}
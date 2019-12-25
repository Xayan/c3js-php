<?php

namespace C3\Enum;

use C3\Chart\AbstractChart;
use JsonSerializable;
use MyCLabs\Enum\Enum;

/**
 * @see AbstractChart::setZoomType()
 *
 * @method static NONE()
 * @method static DRAG()
 * @method static SCROLL()
 */
class ZoomTypeEnum extends Enum implements JsonSerializable
{
    const NONE = 'none';
    const DRAG = 'drag';
    const SCROLL = 'scroll';

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        if ($this->getValue() !== self::NONE) {
            return [
                'enabled' => true,
                'type' => $this->getValue()
            ];
        } else {
            return [
                'enabled' => false
            ];
        }
    }
}
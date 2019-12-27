<?php

namespace C3\Chart;

use C3\Enum\ZoomTypeEnum;
use JsonSerializable;

interface ChartInterface extends JsonSerializable
{
    /**
     * @return array
     */
    public function getColumns(): array;

    /**
     * @return array
     */
    public function getNames(): array;

    /**
     * @param ZoomTypeEnum $zoomType
     * @return void
     */
    public function setZoomType(ZoomTypeEnum $zoomType): void;
}
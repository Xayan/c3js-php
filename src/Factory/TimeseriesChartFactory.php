<?php

namespace C3\Factory;

use C3\Chart\Column\Column;
use C3\Chart\TimeseriesChart;
use C3\Enum\ZoomTypeEnum;
use C3\Util\DateArrayFiller;

class TimeseriesChartFactory
{
    /**
     * @var DateArrayFiller
     */
    private $dateArrayFiller;

    /**
     * ChartFactory constructor.
     * @param DateArrayFiller $dateArrayFiller
     */
    public function __construct(DateArrayFiller $dateArrayFiller)
    {
        $this->dateArrayFiller = $dateArrayFiller;
    }

    /**
     * @param array $data
     * @param ZoomTypeEnum|null $zoomType
     * @param bool $connectNulls
     * @param string|null $dateFormat
     * @return TimeseriesChart
     */
    public function createTimeseriesChart(
        array $data,
        ?ZoomTypeEnum $zoomType = null,
        bool $connectNulls = false,
        string $dateFormat = null
    ): TimeseriesChart
    {
        $chart = new TimeseriesChart();

        $chart->addColumn(new Column('x', 'Date', array_keys($data)), true);
        $chart->addColumn(new Column('value', 'Value', array_values($data)));

        $this->setBaseInfo($chart, $zoomType, $connectNulls, $dateFormat);

        return $chart;
    }

    /**
     * @param TimeseriesChart $chart
     * @param ZoomTypeEnum|null $zoomType
     * @param bool|null $connectNulls
     * @param string|null $dateFormat
     */
    private function setBaseInfo(
        TimeseriesChart $chart,
        ?ZoomTypeEnum $zoomType = null,
        ?bool $connectNulls = null,
        ?string $dateFormat = null
    )
    {
        if ($zoomType !== null) {
            $chart->setZoomType($zoomType);
        }

        if($connectNulls !== null) {
            $chart->setConnectNull($connectNulls);
        }

        if($dateFormat !== null) {
            $chart->setDateFormat($dateFormat);
        }
    }
}
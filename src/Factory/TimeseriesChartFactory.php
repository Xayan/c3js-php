<?php

namespace C3\Factory;

use C3\Chart\ChartInterface;
use C3\Chart\LineChart;
use C3\Chart\TimeseriesChart;
use C3\Enum\MovingAverageProperty;
use C3\Enum\ZoomTypeEnum;
use C3\Exception\DateArrayFillerException;
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
     * @param array $movingAverages
     * @param bool $connectNull
     * @param string|null $dateFormat
     * @return TimeseriesChart
     * @throws DateArrayFillerException
     */
    public function createTimeseriesChart(
        array $data,
        ?ZoomTypeEnum $zoomType = null,
        array $movingAverages = [],
        bool $connectNull = false,
        string $dateFormat = null
    ): TimeseriesChart
    {
        $dataWithNulls = $this->dateArrayFiller->fillGapsWithNulls($data);
        $dataWithAvgs = $this->dateArrayFiller->fillGapsWithAverages($data);

        $chart = new TimeseriesChart($dataWithNulls, $dataWithAvgs);

        $this->setBaseInfo($chart, $zoomType);
        $this->processMovingAverages($chart, $movingAverages);

        $chart->setConnectNull($connectNull);

        if ($dateFormat !== null) {
            $chart->setDateFormat($dateFormat);
        }

        return $chart;
    }

    /**
     * @param array $data
     * @param ZoomTypeEnum|null $zoomType
     * @param array $movingAverages
     * @param bool $connectNull
     * @return LineChart
     */
    public function createLineChart(
        array $data,
        ?ZoomTypeEnum $zoomType = null,
        array $movingAverages = [],
        bool $connectNull = false
    ): LineChart
    {
        $chart = new LineChart($data);

        $this->setBaseInfo($chart, $zoomType);
        $this->processMovingAverages($chart, $movingAverages);

        $chart->setConnectNull($connectNull);

        return $chart;
    }

    /**
     * @param ChartInterface $chart
     * @param ZoomTypeEnum|null $zoomType
     */
    private function setBaseInfo(ChartInterface $chart, ?ZoomTypeEnum $zoomType)
    {
        if ($zoomType !== null) {
            $chart->setZoomType($zoomType);
        }
    }

    /**
     * @param LineChart $chart
     * @param array $movingAverages
     */
    private function processMovingAverages(LineChart $chart, array $movingAverages)
    {
        foreach ($movingAverages as $movingAverage) {
            $chart->addMovingAverage(
                $movingAverage[MovingAverageProperty::STEPS],
                $movingAverage[MovingAverageProperty::PRECISION]
            );
        }
    }
}
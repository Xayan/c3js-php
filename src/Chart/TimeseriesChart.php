<?php

namespace C3\Chart;

use C3\Extension\Column;
use C3\Extension\MovingAverage;

class TimeseriesChart extends LineChart
{
    /**
     * @var array
     */
    protected $filledAvgs = [];

    /**
     * @var string
     */
    protected $dateFormat = '%Y-%m-%d';

    /**
     * TimeseriesChart constructor.
     * @param array $filledNulls
     * @param array $filledAvgs
     */
    public function __construct(array $filledNulls, array $filledAvgs)
    {
        $this->filledAvgs = $filledAvgs;

        parent::__construct($filledNulls);
    }

    /**
     * @param array $data
     * @return Column[]
     */
    public function getDefaultColumns(array $data): array
    {
        return [
            new Column('x', 'Date', array_keys($data)),
            new Column('value', 'Value', array_values($data)),
            new Column('value_avg', 'Value w/ avg', $this->filledAvgs, true)
        ];
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $chart = parent::jsonSerialize();

        $chart['data']['x'] = 'x';
        $chart['axis']['x']['type'] = 'timeseries';
        $chart['axis']['x']['tick']['format'] = $this->getDateFormat();

        return $chart;
    }

    /**
     * @param int $steps
     * @param int $precision
     */
    public function addMovingAverage(int $steps, int $precision)
    {
        $this->columns[] = new MovingAverage($this->filledAvgs, $steps, $precision);
    }

    /**
     * @return string
     */
    public function getDateFormat(): string
    {
        return $this->dateFormat;
    }

    /**
     * @param string $dateFormat
     */
    public function setDateFormat(string $dateFormat): void
    {
        $this->dateFormat = $dateFormat;
    }
}
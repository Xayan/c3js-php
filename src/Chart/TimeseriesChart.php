<?php

namespace C3\Chart;

class TimeseriesChart extends LineChart
{

    /**
     * @var string
     */
    protected $dateFormat = '%Y-%m-%d';

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $chart = parent::jsonSerialize();

        $chart['axis']['x']['type'] = 'timeseries';
        $chart['axis']['x']['tick']['format'] = $this->getDateFormat();

        return $chart;
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
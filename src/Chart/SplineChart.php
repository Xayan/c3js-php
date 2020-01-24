<?php


namespace C3\Chart;


class SplineChart extends LineChart
{
    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $chart = parent::jsonSerialize();

        $chart['data']['type'] = 'spline';

        return $chart;
    }
}
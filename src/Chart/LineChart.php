<?php

namespace C3\Chart;

use C3\Extension\Column;
use C3\Extension\MovingAverage;

class LineChart extends AbstractChart
{
    /**
     * @var bool
     */
    private $connectNull = false;

    /**
     * @param array $data
     * @return array
     */
    public function getDefaultColumns(array $data): array
    {
        return [
            new Column('value', 'Value', array_values($data))
        ];
    }

    /**
     * @param int $steps
     * @param int $precision
     */
    public function addMovingAverage(int $steps, int $precision)
    {
        $this->columns[] = new MovingAverage($this->data, $steps, $precision);
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $chart = parent::jsonSerialize();

        $chart['line']['connectNull'] = $this->isConnectNull();

        return $chart;
    }

    /**
     * @return bool
     */
    public function isConnectNull(): bool
    {
        return $this->connectNull;
    }

    /**
     * @param bool $connectNull
     */
    public function setConnectNull(bool $connectNull): void
    {
        $this->connectNull = $connectNull;
    }
}
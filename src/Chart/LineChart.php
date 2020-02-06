<?php

namespace C3\Chart;

use C3\Chart\Column\Column;
use C3\Chart\Column\MovingAverage;
use C3\Enum\MovingAverageGapBehavior;
use C3\Util\MovingAverage\LineChartMovingAverageCalculator;
use C3\Util\MovingAverage\MovingAverageCalculatorInterface;

class LineChart extends AbstractChart
{
    /**
     * @var bool
     */
    private $connectNull = false;

    /**
     * @var MovingAverageCalculatorInterface
     */
    private $movingAverageCalculator;

    /**
     * LineChart constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->movingAverageCalculator = new LineChartMovingAverageCalculator();
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

    /**
     * @param string $name
     * @param string $label
     * @param Column $sourceColumn
     * @param int $steps
     * @param int $precision
     * @param MovingAverageGapBehavior $movingAverageGapBehavior
     */
    public function addMovingAverage(
        string $name,
        string $label,
        Column $sourceColumn,
        int $steps,
        int $precision,
        MovingAverageGapBehavior $movingAverageGapBehavior
    ): void
    {
        $this->addColumn(
            new MovingAverage(
                $label,
                $name,
                $this->movingAverageCalculator->calculate($sourceColumn, $steps, $precision, $movingAverageGapBehavior)
            )
        );
    }
}
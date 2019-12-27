<?php

namespace C3\Chart;

use C3\Chart\Column\Column;
use C3\Enum\ZoomTypeEnum;

abstract class AbstractChart implements ChartInterface
{
    /**
     * @var Column[]
     */
    protected $columns = [];

    /**
     * @var string
     */
    protected $divId;

    /**
     * @var ZoomTypeEnum
     */
    protected $zoomType;
    /**
     * @var string
     */
    protected $xAxisName = 'x';

    /**
     * AbstractChart constructor.
     */
    public function __construct()
    {
        $this->divId = 'chart_' . spl_object_id($this);
        $this->zoomType = ZoomTypeEnum::NONE();
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            'x' => $this->getXAxisName(),
            'columns' => $this->getColumns(),
            'names' => $this->getNames(),
            'hide' => $this->getHiddenColumnNames()
        ];
    }

    /**
     * @return string
     */
    public function getDivId(): string
    {
        return $this->divId;
    }

    /**
     * @param string $divId
     * @return AbstractChart
     */
    public function setDivId(string $divId): self
    {
        $this->divId = $divId;

        return $this;
    }

    /**
     * @param Column $column
     * @param bool $xAxis
     * @return AbstractChart
     */
    public function addColumn(Column $column, bool $xAxis = false): self
    {
        $this->columns[] = $column;

        if($xAxis) {
            $this->setXAxisName($column->getName());
        }

        return $this;
    }

    /**
     * @return Column[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @return ZoomTypeEnum
     */
    public function getZoomType(): ZoomTypeEnum
    {
        return new ZoomTypeEnum($this->zoomType);
    }

    /**
     * @param ZoomTypeEnum $zoomType
     */
    public function setZoomType(ZoomTypeEnum $zoomType): void
    {
        $this->zoomType = $zoomType->getValue();
    }

    /**
     * @return array
     */
    public function getNames(): array
    {
        $names = [];

        foreach ($this->columns as $column) {
            $names[$column->getName()] = $column->getLabel();
        }

        return $names;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'bindto' => '#' . $this->getDivId(),
            'data' => $this->getData(),
            'zoom' => $this->getZoomType()
        ];
    }

    /**
     * @return array
     */
    private function getHiddenColumnNames(): array
    {
        return array_values( // reset indexes
            array_map(
                function (Column $column) { // get column name
                    return $column->getName();
                },
                array_filter( // filter out only hidden columns
                    $this->getColumns(),
                    function (Column $column) {
                        return $column->isHidden();
                    }
                )
            )
        );
    }

    /**
     * @return string
     */
    public function getXAxisName(): string
    {
        return $this->xAxisName;
    }

    /**
     * @param string $xAxisName
     */
    public function setXAxisName(string $xAxisName): void
    {
        $this->xAxisName = $xAxisName;
    }
}
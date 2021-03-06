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
    protected $xAxisName;

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
        // TODO: check if column with given name already exists

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
     * @param string $name
     * @return Column|null
     */
    public function getColumnByName(string $name): ?Column
    {
        foreach ($this->columns as $column) {
            if($column->getName() === $name) {
                return $column;
            }
        }

        return null;
    }

    /**
     * @return ZoomTypeEnum
     */
    public function getZoomType(): ZoomTypeEnum
    {
        return $this->zoomType;
    }

    /**
     * @param ZoomTypeEnum $zoomType
     */
    public function setZoomType(ZoomTypeEnum $zoomType): void
    {
        $this->zoomType = $zoomType;
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
            'zoom' => $this->getZoomType()->getValue() ?? null
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
     * @return string|null
     */
    public function getXAxisName(): ?string
    {
        return $this->xAxisName;
    }

    /**
     * @param string|null $xAxisName
     */
    public function setXAxisName(?string $xAxisName): void
    {
        $this->xAxisName = $xAxisName;
    }
}
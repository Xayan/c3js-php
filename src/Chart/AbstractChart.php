<?php

namespace C3\Chart;

use C3\Enum\ZoomTypeEnum;
use C3\Extension\Column;
use C3\Extension\MovingAverage;

abstract class AbstractChart implements ChartInterface
{
    /**
     * @var array
     */
    protected $data = [];

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
     * AbstractChart constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
        $this->divId = 'chart_' . spl_object_id($this);
        $this->zoomType = ZoomTypeEnum::NONE();

        foreach($this->getDefaultColumns($data) as $column) {
            $this->addColumn($column);
        }
    }

    /**
     * @param array $data
     * @return Column[]
     */
    abstract public function getDefaultColumns(array $data): array;

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
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
     * @return AbstractChart
     */
    public function addColumn(Column $column): self
    {
        $this->columns[] = $column;

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
            'data' => [
                'columns' => $this->getColumns(),
                'names' => $this->getNames(),
                'hide' => $this->getHiddenColumnNames()
            ],
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
    public function renderHTML(): string
    {
        $html = sprintf('<style>#%s .c3-line { stroke-width: 2px; }</style>', $this->getDivId());

        foreach ($this->getColumns() as $column) {
            if($column instanceof MovingAverage) {
                $html .= sprintf('<style>#%s .c3-target-%s .c3-circles { display: none; }</style>', $this->getDivId(), $column->getName());
                $html .= sprintf('<style>#%s .c3-target-%s .c3-line { stroke-width: 2px; stroke-opacity: 0.5; }</style>', $this->getDivId(), $column->getName());
            }
        }

        $html .= sprintf('<div id="%s"></div>', $this->getDivId());

        return $html;
    }

    /**
     * @return string
     */
    public function renderJS(): string
    {
        return sprintf('<script>c3.generate(%s)</script>', json_encode($this));
    }
}
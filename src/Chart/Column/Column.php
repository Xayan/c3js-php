<?php

namespace C3\Chart\Column;

use JsonSerializable;

class Column implements JsonSerializable
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $strokeWidth = '2px';

    /**
     * @var bool
     */
    private $circlesHidden = false;

    /**
     * @var float
     */
    private $opacity = 1.0;

    /**
     * @var bool
     */
    private $hidden = false;

    /**
     * @var array
     */
    protected $values = [];

    /**
     * Column constructor.
     * @param string $name
     * @param string $label
     * @param array $values
     * @param bool $hidden
     */
    public function __construct(string $name, string $label, array $values, bool $hidden = false)
    {
        $this->name = $name;
        $this->label = $label;
        $this->values = array_values($values);
        $this->hidden = $hidden;
    }

    /**
     * @return array
     */
    public function getColumnData(): array
    {
        return array_merge(
            [$this->name],
            array_values($this->values)
        );
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @return bool
     */
    public function isHidden(): bool
    {
        return $this->hidden;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->getColumnData();
    }

    /**
     * @return string
     */
    public function getStrokeWidth(): string
    {
        return $this->strokeWidth;
    }

    /**
     * @param string $strokeWidth
     * @return Column
     */
    public function setStrokeWidth(string $strokeWidth): Column
    {
        $this->strokeWidth = $strokeWidth;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCirclesHidden(): bool
    {
        return $this->circlesHidden;
    }

    /**
     * @param bool $circlesHidden
     * @return Column
     */
    public function setCirclesHidden(bool $circlesHidden): Column
    {
        $this->circlesHidden = $circlesHidden;
        return $this;
    }

    /**
     * @return float
     */
    public function getOpacity(): float
    {
        return $this->opacity;
    }

    /**
     * @param float $opacity
     * @return Column
     */
    public function setOpacity(float $opacity): Column
    {
        $this->opacity = $opacity;
        return $this;
    }
}
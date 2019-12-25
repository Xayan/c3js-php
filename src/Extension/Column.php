<?php

namespace C3\Extension;

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
        $this->values = $values;
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
}
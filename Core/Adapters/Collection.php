<?php

namespace Core\Adapters;

use ArrayObject;

class Collection extends ArrayObject
{
//    public function __toString(): string
//    {
//        return json_encode($this);
//    }

    public function sortByValue(): void
    {
        $this->asort();
    }

    public function sortByKey(): void
    {
        $this->ksort();
    }

    /**
     * @return void
     * This method use alphanumeric strings for sort
     */
    public function sortByNaturalOrderValue(): void
    {
        $this->natsort();
    }

    public function sortByInsensitiveNaturalOrderValue()
    {
        $this->natcasesort();
    }

    public function sortByCallableValue(callable $callable)
    {
        $this->uasort($callable);
    }

    public function sortByCallableKey(callable $callable)
    {
        $this->uksort($callable);
    }
}
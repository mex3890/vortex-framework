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

    /**
     * @return false|mixed
     */
    public function first(): mixed
    {
        if ($this->isEmpty()) {
            return false;
        }

        return $this[0];
    }

    /**
     * @return false|mixed
     */
    public function last(): mixed
    {
        if ($this->isEmpty()) {
            return false;
        }

        return $this[$this->count() - 1];
    }

    public function isEmpty(): bool
    {
        if ($this->count() === 0) {
            return true;
        }

        return false;
    }
}

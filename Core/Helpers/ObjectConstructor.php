<?php

namespace Core\Helpers;

use stdClass;

class ObjectConstructor
{
    public static function mountModelObject(object $model, array $args): object
    {
        $object = new $model();

        foreach ($args as $key => $value) {
            $object->$key = $value;
        }

        unset($object->args);
        unset($object->table);

        return $object;
    }
}
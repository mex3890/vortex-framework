<?php

namespace Core\Helpers;

use Core\Abstractions\Model;

class ObjectConstructor
{
    public static function mountModelObject(object $model, array $args): Model
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
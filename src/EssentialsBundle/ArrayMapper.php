<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 27/10/17
 * Time: 11:25
 */

namespace EssentialsBundle;

class ArrayMapper
{
    public function fieldSelector(
        array $array,
        $fields
    ) {
        (!$this->isObjectArray($fields)) ?: $fields = $fields[0];

        foreach ($array as $key => $value) {
            $recursion = (($this->isAssociativeArray($value))
                || ($this->isObjectArray($value)));

            if ($recursion) {
                $array[$key] = $this->fieldSelector(
                    $value,
                    (is_int($key)) ? $fields : $fields[$key]
                );
            }

            if ($this->needRemove($key, $fields)) {
                unset($array[$key]);
            }
        }

        return $array;
    }

    protected function isObjectArray($value): bool
    {
        return ((is_array($value)) && (is_array($value[0])));
    }

    protected function isAssociativeArray($value): bool
    {
        return ((is_array($value)) && (array_values($value) != $value));
    }

    protected function needRemove($key, $fields)
    {
        return (!((isset($fields[$key])) || (in_array($key, $fields))));
    }
}

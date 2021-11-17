<?php
function create($class, $attributes=[], $items = null)
{
    return $class::factory($items)->create($attributes);
}
function make($class, $attributes=[], $items = null)
{
    return $class::factory($items)->make($attributes);
}

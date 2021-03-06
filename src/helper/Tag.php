<?php

namespace helper;

class Tag
{
    const LEGACY = 'yes';
    const MEN = 'men';
    const BOOTS = 'boots';
    const PUMPS = 'pumps';
    const SANDALS = 'sandals';
    const BEST = 'best';
    const CHRISTMAS = 'christmas';
    const WALL = 'wall';

    function all()
    {
        $class = new \ReflectionClass('helper\Tag');
        return $class->getConstants();
    }

    function match($request, $collection)
    {
        if ($request == self::MEN) {
            return in_array(self::MEN, $collection);
        } else if ($request == self::LEGACY){
            return !in_array(self::MEN, $collection) && in_array($request, $collection);
        } else {
            return in_array($request, $collection);
        }
    }
}
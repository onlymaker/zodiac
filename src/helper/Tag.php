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

    function all()
    {
        $class = new \ReflectionClass('helper\Tag');
        return $class->getConstants();
    }
}
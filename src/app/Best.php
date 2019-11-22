<?php

namespace app;

class Best
{
    function beforeRoute()
    {
        header('Access-Control-Allow-Origin:*');
    }

    function get(\Base $f3)
    {
        $feature = new Feature();
        $feature->tag($f3, ['feature' => 'best']);
    }
}

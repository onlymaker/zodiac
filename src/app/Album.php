<?php

namespace app;

class Album
{
    function beforeRoute()
    {
        header('Access-Control-Allow-Origin:*');
    }

    function get(\Base $f3)
    {
        $feature = new Feature();
        $feature->tag($f3, ['feature' => 'yes']);
    }
}

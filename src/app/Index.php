<?php

namespace app;

class Index
{
    function get()
    {
        echo \Template::instance()->render('index.html');
    }
}

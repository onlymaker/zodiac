<?php

namespace app;

class Index
{
    function get()
    {
        echo \Template::instance()->render('index.html');
    }

    function gallery()
    {
        $file = RUNTIME . '/gallery.json';
        if (is_file($file)) {
            echo file_get_contents($file);
        } else {
            echo '[]';
        }
    }
}

<?php

namespace admin;

use db\JigMapper;

class Delete extends Index
{
    function post(\Base $f3)
    {
        $gallery = new JigMapper('gallery');
        $gallery->erase(['@id=?', $f3->get('POST.id')]);
        echo 'success';
    }
}

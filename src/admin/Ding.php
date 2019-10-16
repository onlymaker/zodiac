<?php

namespace admin;

use db\JigMapper;
use helper\Sort;

class Ding extends Index
{
    function post(\Base $f3)
    {
        $id = $f3->get('POST.id');
        $gallery = new JigMapper('gallery');
        $gallery->load(['@id=?', $id]);
        if ($gallery->dry()) {
            echo "id not found: $id";
        } else {
            $gallery['id'] = (new Sort())->nextId();
            $gallery->save();
            echo 'success';
        }
    }
}

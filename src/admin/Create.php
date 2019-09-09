<?php

namespace admin;

use db\JigMapper;

class Create extends Index
{
    function get(\Base $f3, array $params)
    {
        echo \Template::instance()->render('admin/create.html');
    }

    function post(\Base $f3)
    {
        $gallery = new JigMapper('gallery');
        $gallery->copyfrom($f3->get('POST'));
        $gallery['id'] = $this->nextId();
        $gallery['create_time'] = date('Y-m-d H:i:s');
        $gallery->save();
        echo 'success';
    }

    function nextId()
    {
        $id = new JigMapper('id');
        $id->load(['@object=?', 'gallery']);
        if ($id->dry()) {
            $id['object'] = 'gallery';
            $id['id'] = 2;
        } else {
            $id['id']++;
        }
        $id->save();
        return --$id['id'];
    }
}

<?php

namespace admin;

use db\JigMapper;
use helper\Tag;

class Edit extends Index
{
    function get(\Base $f3, array $params)
    {
        $gallery = new JigMapper('gallery');
        $gallery->load(['@id=?', $f3->get('REQUEST.id')]);
        if ($gallery->dry()) {
            $f3->error(404, "Not Found");
        } else {
            $f3->set('gallery', $gallery->cast());
            $f3->set('tags', (new Tag())->all());
            $f3->set('featured', $gallery['featured'] ?? '');
            echo \Template::instance()->render('admin/edit.html');
        }
    }

    function post(\Base $f3)
    {
        $gallery = new JigMapper('gallery');
        $gallery->load(['@id=?', $f3->get('REQUEST.id')]);
        if (!$gallery->dry()) {
            $gallery->copyfrom($f3->get('POST'));
            $gallery->save();
        }
        echo 'success';
    }
}

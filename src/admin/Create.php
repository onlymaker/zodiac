<?php

namespace admin;

use db\JigMapper;
use helper\Sort;
use helper\Tag;

class Create extends Index
{
    function get(\Base $f3, array $params)
    {
        $f3->set('tags', (new Tag())->all());
        echo \Template::instance()->render('admin/create.html');
    }

    function post(\Base $f3)
    {
        $gallery = new JigMapper('gallery');
        $gallery->copyfrom($f3->get('POST'));
        $size = $this->fetchImage($gallery['image']);
        if ($size) {
            $gallery['id'] = (new Sort())->nextId();
            $gallery['create_time'] = date('Y-m-d H:i:s');
            $gallery['width'] = $size[0];
            $gallery['height'] = $size[1];
            $gallery->save();
            echo 'success';
        } else {
            echo 'Can not get image size';
        }
    }

    function fetchImage($url)
    {
        $name = end(explode('/', parse_url($url, PHP_URL_PATH)));
        $path = "/tmp/$name";
        if (!is_file($path)) {
            $response = \Web::instance()->request($url);
            file_put_contents($path, $response['body']);
        }
        return getimagesize($path);
    }
}

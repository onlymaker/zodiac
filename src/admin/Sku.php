<?php

namespace admin;

use db\JigMapper;
use helper\Sort;

class Sku extends Index
{
    function get(\Base $f3, array $params)
    {
        $results = [];
        $sku = $f3->get('GET.sku');
        $gallery = new JigMapper('gallery');
        if ($sku) {
            $gallery->load(['@sku=?', $sku], Sort::DEFAULT);
            while (!$gallery->dry()) {
                $results[] = $gallery->cast();
                $gallery->next();
            }
            $f3->set('gallery', $results);
            $f3->set('pageNo', 1);
            $f3->set('pageCount', 1);
            echo \Template::instance()->render('admin/index.html');
        } else {
            $gallery->load();
            while (!$gallery->dry()) {
                $results[] = $gallery['sku'];
                $gallery->next();
            }
            $results = array_unique($results);
            \sort($results);
            foreach ($results as $result) {
                echo "<pre>{$result}</pre>";
            }
        }
    }
}

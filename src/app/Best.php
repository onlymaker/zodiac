<?php

namespace app;

use db\JigMapper;

class Best
{
    function beforeRoute()
    {
        header('Access-Control-Allow-Origin:*');
    }

    function get(\Base $f3)
    {
        $best = [
            167,
            13,
            59,
            122,
            72,
            119,
        ];
        $height = 300;
        $gallery = new JigMapper('gallery');
        $album = [];
        foreach ($best as $id) {
            $item = $gallery->load(['@id=?', $id]);
            if (!$item->dry()) {
                $fields = $item->cast();
                $fields['bias'] = $fields['width'] / $fields['height'] * $height;
                $fields['grow'] = $fields['bias'];
                $fields['bottom'] = sprintf('%.2f%%', $fields['height'] / $fields['width'] * 100);//转换为百分比
                $fields['url'] .= ((strpos($fields['url'], '?', 1)) ? '&' : '?') . 'utm_source=site&utm_medium=best&utm_campaign=gallery';
                $album[] = $fields;
            }
        }
        $f3->set('album', $album);
        echo \Template::instance()->render('best.html');
    }
}

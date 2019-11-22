<?php

namespace app;

use db\JigMapper;
use helper\Sort;
use helper\Tag;

/*
<div id="gallery"></div>
<script>
    if (typeof fetch === "function") {
        fetch("https://gallery.onlymaker.com/Album")
            .then(function (response) {
                return response.text()
            })
            .then(function (text) {
                document.getElementById("gallery").insertAdjacentHTML("afterbegin", text);
            })
            .catch(console.log)
    }
</script>
 */
class Feature
{
    function beforeRoute()
    {
        header('Access-Control-Allow-Origin:*');
    }

    function tag(\Base $f3, array $params)
    {
        $album = [];
        $i = 0;
        $height = 300;
        $size = $f3->get('ALBUM_SIZE');
        $feature = $params['feature'];
        $tag = new Tag();
        $gallery = new JigMapper('gallery');
        $gallery->load(null, Sort::DEFAULT);
        while ($i < $size && !$gallery->dry()) {
            $tags = explode(',', $gallery['featured'] ?? '');
            if ($tag->match($feature, $tags)) {
                $fields = $gallery->cast();
                $fields['bias'] = $fields['width'] / $fields['height'] * $height;
                $fields['grow'] = $fields['bias'];
                $fields['bottom'] = sprintf('%.2f%%',$fields['height'] / $fields['width'] * 100);//转换为百分比
                $fields['url'] .= ((strpos($fields['url'], '?', 1)) ? '&' : '?') . 'utm_source=feature&utm_medium=' . $feature . '&utm_campaign=gallery';
                $album[] = $fields;
                $i++;
            }
            $gallery->next();
        }
        $f3->set('album', $album);
        $f3->set('more', $tag->more($feature));
        echo \Template::instance()->render('feature.html');
    }
}

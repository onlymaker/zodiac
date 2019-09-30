<?php

namespace app;

use db\JigMapper;

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
class Album
{
    function beforeRoute()
    {
        header('Access-Control-Allow-Origin:*');
    }

    function get(\Base $f3)
    {
        $height = 300;
        $gallery = new JigMapper('gallery');
        $data = $gallery->paginate(0, 15, ["@featured='yes'"], ['order' => 'id SORT_DESC']);
        $album = [];
        foreach ($data['subset'] as $item) {
            $fields = $item->cast();
            $fields['bias'] = $fields['width'] / $fields['height'] * $height;
            $fields['grow'] = $fields['bias'];
            $fields['bottom'] = $fields['height'] / $fields['width'] * 100;//转换为百分比
            $fields['url'] .= ((strpos($fields['url'], '?', 1)) ? '&' : '?') . 'utm_source=gallery&utm_medium=gallery&utm_campaign=gallery';
            $album[] = $fields;
        }
        $f3->set('album', $album);
        echo \Template::instance()->render('album.html');
    }
}

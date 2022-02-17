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
        $feature = $params['feature'];
        if ($feature == 'men') {
            header('Location:https://www.onlymaker.com/pages/ins-men');
        } else {
            $size = ($feature === Tag::WALL) ? 100 : $f3->get('ALBUM_SIZE');
            $pageNo = $f3->get('PARAMS.pageNo') ?? 1;
            $offset = ($pageNo - 1) * $size;
            $tag = new Tag();
            $detect = new \Mobile_Detect();
            $mobile = $detect->isMobile();
            $gallery = new JigMapper('gallery');
            $gallery->load(null, Sort::DEFAULT);
            while ($i < ($size + $offset) && !$gallery->dry()) {
                $tags = explode(',', $gallery['featured'] ?? '');
                if ($tag->match($feature, $tags)) {
                    $i++;
                    if ($i > $offset) {
                        if ($mobile) {
                            $gallery['image'] = str_replace('_360x.', '_600x.', $gallery['image']);
                        }
                        $fields = $gallery->cast();
                        $fields['bias'] = $fields['width'] / $fields['height'] * $height;
                        $fields['grow'] = $fields['bias'];
                        $fields['bottom'] = sprintf('%.2f%%', $fields['height'] / $fields['width'] * 100);//转换为百分比
                        if (!strpos($fields['url'], 's.onlymaker.com')) {
                            $fields['url'] .= ((strpos($fields['url'], '?', 1)) ? '&' : '?') . 'utm_source=feature&utm_medium=' . $feature . '&utm_campaign=gallery';
                        }
                        $album[] = $fields;
                    }
                }
                $gallery->next();
            }
            $f3->set('album', $album);
            $f3->set('tag', $feature);
            $f3->set('pageNo', $pageNo);
            switch ($feature) {
                case Tag::LEGACY:
                    $f3->set('more', 'https://gallery.onlymaker.com');
                    break;
                case Tag::BEST:
                case Tag::CHRISTMAS:
                    break;
                default:
                    if (count($album) == $size) {
                        $f3->set('more', true);
                    }
            }
            echo \Template::instance()->render('feature.html');
        }
    }
}

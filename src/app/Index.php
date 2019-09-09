<?php

namespace app;

use db\JigMapper;

class Index
{
    function get()
    {
        echo \Template::instance()->render('index.html');
    }

    function gallery(\Base $f3)
    {
        $pageNo = $f3->get('PARAMS.pageNo') ?? 1;
        $pageSize = 15;
        $gallery = new JigMapper('gallery');
        $data = $gallery->paginate(--$pageNo, $pageSize, null, ['order' => 'id SORT_DESC']);
        $subset = [];
        foreach ($data['subset'] as $item) {
            $subset[] = $item->cast();
        }
        echo json_encode([
            'subset' => $subset,
            'pageNo' => ++$data['pos'],
            'pageCount' => $data['count'],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}

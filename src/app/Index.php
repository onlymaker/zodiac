<?php

namespace app;

use db\JigMapper;
use helper\Sort;

class Index
{
    function get(\Base $f3)
    {
        $pageNo = $f3->get('GET.pageNo') ?? 1;
        $f3->set('pageNo', $pageNo);
        echo \Template::instance()->render('index.html');
    }

    function gallery(\Base $f3)
    {
        $pageNo = $f3->get('PARAMS.pageNo') ?? 1;
        $pageSize = 20;
        $gallery = new JigMapper('gallery');
        $data = $gallery->paginate(--$pageNo, $pageSize, null, Sort::DEFAULT);
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

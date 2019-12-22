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
        $detect = new \Mobile_Detect();
        $mobile = $detect->isMobile();
        $pageNo = $f3->get('PARAMS.pageNo') ?? 1;
        $pageSize = 20;
        $gallery = new JigMapper('gallery');
        $data = $gallery->paginate(--$pageNo, $pageSize, null, Sort::DEFAULT);
        $subset = [];
        foreach ($data['subset'] as $item) {
            if ($mobile) {
                $item['image'] = str_replace('_360x.', '_600x.', $item['image']);
            }
            $subset[] = $item->cast();
        }
        echo json_encode([
            'subset' => $subset,
            'pageNo' => ++$data['pos'],
            'pageCount' => $data['count'],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}

<?php

namespace admin;

use db\JigMapper;
use helper\Sort;
use helper\Tag;

class Index
{
    private $user;

    function get(\Base $f3, array $params)
    {
        $results = [];
        $pageNo = $params['pageNo'] ?? 1;
        $pageSize = $f3->get('ALBUM_SIZE');
        $gallery = new JigMapper('gallery');
        $count = ceil($gallery->count() / $pageSize);
        $feature = $f3->get('GET.tag') ?? '';
        if ($feature) {
            $i = 0;
            $tag = new Tag();
            $offset = ($pageNo - 1) * $pageSize;
            $gallery->load(null, Sort::DEFAULT);
            while ($i < ($pageSize + $offset) && !$gallery->dry()) {
                $tags = explode(',', $gallery['featured'] ?? '');
                if (in_array($feature, $tags)) {
                    $i++;
                    if ($i > $offset) {
                        $results[] = $gallery->cast();
                    }
                }
                $gallery->next();
            }
        } else {
            $data = $gallery->paginate($pageNo - 1, $pageSize, null, Sort::DEFAULT);
            foreach ($data['subset'] as $item) {
                $results[] = $item->cast();
            }
            $count = $data['count'];
        }
        $f3->set('gallery', $results);
        $f3->set('tag', $feature);
        $f3->set('pageNo', $pageNo);
        $f3->set('pageCount', $count);
        echo \Template::instance()->render('admin/index.html');
    }

    function auth(\Base $f3)
    {
        if (!$f3->get('SESSION.AUTHENTICATION')) {
            if ($f3->get('VERB') == 'GET') {
                setcookie('target', $f3->get('REALM'), 0, '/');
            } else {
                setcookie('target', $this->url(), 0, '/');
            }
            return false;
        } else {
            $this->user = [
                'name' => $f3->get('SESSION.AUTHENTICATION'),
                'role' => $f3->get('SESSION.AUTHORIZATION')
            ];
            $f3->set('user', $this->user);
            return true;
        }
    }

    function beforeRoute(\Base $f3)
    {
        if (!$this->auth($f3)) {
            $f3->reroute($this->url('/Login'));
        }
    }

    function url($target = '')
    {
        $scheme = explode('/', strtolower($_SERVER["SERVER_PROTOCOL"]))[0];
        $host = $_SERVER['SERVER_NAME'];
        $port = $_SERVER['SERVER_PORT'] == 80 ? '' : ':' . $_SERVER['SERVER_PORT'];
        $base = rtrim(strtr(dirname($_SERVER['SCRIPT_NAME']), '\\', '/'), '/');
        $base .= '/admin';
        return $scheme . '://' . $host . $port . $base . $target;
    }
}

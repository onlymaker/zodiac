<?php

namespace admin;

use db\JigMapper;

class Index
{
    private $user;

    function get(\Base $f3, array $params)
    {
        $pageNo = $params['pageNo'] ?? 1;
        $pageSize = 15;
        $gallery = new JigMapper('gallery');
        $data = $gallery->paginate(--$pageNo, $pageSize, null, ['order' => 'id SORT_DESC']);
        if ($pageNo == $data['pos']) {
            $subset = [];
            foreach ($data['subset'] as $item) {
                $subset[] = $item->cast();
            }
        } else {
            $subset = [];
        }
        $f3->set('gallery', $subset);
        $f3->set('pageNo', ++$data['pos']);
        $f3->set('pageCount', $data['count']);
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

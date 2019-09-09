<?php

use admin\Create;
use db\JigMapper;

require_once __DIR__ . '/index.php';

$legacy = RUNTIME . '/gallery.json';

if (is_file($legacy)) {
    $gallery = new JigMapper('gallery');
    $creator = new Create();
    $data = json_decode(file_get_contents($legacy), true);
    foreach ($data as $item) {
        $gallery->reset();
        $gallery->copyfrom($item);
        $gallery['id'] = $creator->nextId();
        $gallery['create_time'] = date('Y-m-d H:i:s');
        $gallery->save();
    }
}

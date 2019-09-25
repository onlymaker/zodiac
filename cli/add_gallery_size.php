<?php

use admin\Create;
use db\JigMapper;

require_once __DIR__ . '/index.php';

$creator = new Create();
$gallery = new JigMapper('gallery');
$gallery->load();
while (!$gallery->dry()) {
    $size = $creator->fetchImage($gallery['image']);
    if ($size) {
        $gallery['width'] = $size[0];
        $gallery['height'] = $size[1];
        $gallery->save();
    } else {
        echo 'Can not get image size: ', $gallery['sku'], ', ', $gallery['image'], PHP_EOL;
    }
    $gallery->next();
}

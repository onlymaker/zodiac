<?php

use admin\Create;
use db\JigMapper;
use helper\Sort;

require_once __DIR__ . '/index.php';

//sync gallery id
$helper = new Sort();
$max = $helper->maxId();

$id = new JigMapper('id');
$id->load(['@object=?', 'gallery']);
$id->copyfrom([
    'object' => 'gallery',
    'id' => ++$max,
]);
$id->save();

$gallery = new JigMapper('gallery');
for ($i = 1; $i <= $max; $i++) {
    $gallery->load(['@id=?', $i]);
    if (!$gallery->dry()) {
        $gallery->next();
        while (!$gallery->dry()) {
            $gallery['id'] = $helper->nextId();
            $gallery->save();
            $gallery->next();
        }
    }
}


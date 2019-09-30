<?php

use admin\Create;
use db\JigMapper;

require_once __DIR__ . '/index.php';

$creator = new Create();
$gallery = new JigMapper('gallery');
$gallery->load();
while (!$gallery->dry()) {
    $gallery['featured'] = 'yes';
    $gallery->save();
    $gallery->next();
}

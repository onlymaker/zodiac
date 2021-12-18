<?php

use db\JigMapper;

require_once __DIR__ . '/index.php';

$products = [];
$gallery = new JigMapper('gallery');
$gallery->load();
while (!$gallery->dry()) {
    $products[] = $gallery['sku'];
    $gallery->next();
}

$products = array_unique($products);
sort($products);
echo "'", implode("','", $products), "'", PHP_EOL;
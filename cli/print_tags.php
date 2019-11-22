<?php

use helper\Tag;

require_once __DIR__ . '/index.php';

print_r((new Tag())->all());
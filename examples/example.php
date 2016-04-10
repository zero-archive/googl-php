<?php

require_once __DIR__ . '/../vendor/autoload.php';

use \dotzero\Googl;
use \dotzero\GooglException;

try {
    # GOOGLE_API=ABCDABCDABCDABCDABCDABCDABCDABCDABCDABC php examples/example.php
    $googl = new Googl(getenv('GOOGLE_API'));

    $link = $googl->shorten('http://github.com');
    printf($link . "\n");

    $link = $googl->expand('http://goo.gl/KkZ8');
    printf($link . "\n");

} catch (GooglException $e) {
    printf('Error (%d): %s', $e->getCode(), $e->getMessage());
}

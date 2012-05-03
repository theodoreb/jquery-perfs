<?php

header('Content-type: application/javascript');

$component = !empty($_GET['c']) ? $_GET['c'] : array();

function load($c) {
  $path = './jquery/' . $c . '.js';
  if (file_exists($path)) {
    return file_get_contents($path);
  }
  return '';
}

foreach ((array) $component as $c) {
  print "\n\n//$c\n" . load($c);
}

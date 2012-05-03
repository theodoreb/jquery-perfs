<?php

$seconds_to_cache = 3600*24*14;
$ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
header('Content-type: application/x-javascript');
header("Expires: $ts");
header("Cache-Control: public");//max-age=$seconds_to_cache");


$component = !empty($_GET['c']) ? $_GET['c'] : array();

function load($c) {
  $path = './jquery/' . $c . '.js';
  if (file_exists($path)) {
    return file_get_contents($path);
  }
  return '';
}

if ($component !== 'raw') {
  foreach ((array) $component as $c) {
    print "\n\n//$c\n" . load($c);
  }
}

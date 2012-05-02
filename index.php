<?php

/**
 * jquery: 85
 *
 * - ajax: 12 (core, ajax): 7
 * - attributes 19 (core, callbacks, support, attributes): 5
 * - callbacks: 9 (core, callbacks): 4
 * - core: 5
 * - css 18 (core, callbacks, support, css): 4
 * - data 12 (core, data): 7
 * - deferred 10 (core, deferred): 5
 * - dimensions 8 (core, dimensions) 3
 * - effects 21 (core, callbacks, support, effects): 7
 * - event 34 (core, callbacks, support, attributes, data, event) 8
 * - manipulation 13 (core, manipulation): 8
 * - offset 9 (core, offset): 4
 * - queue 10 (core, queue): 5
 * - support 14 (core, callbacks, support); 5
 * - traversing 11 (core, traversing): 6
 */





$component = !empty($_GET['c']) ? $_GET['c'] : 'RAW';
$js = !empty($_GET['no_js']) && $_GET['no_js'] == 1 ? 0 : 1;

function load($c) {
  $path = './jquery/' . $c . '.js';
  if (file_exists($path)) {
    return file_get_contents($path);
  }
  return '';
}



?><!doctype html>
<html>
<head>
  <meta charset="utf8">
  <title>jQuery perf rundown</title>
  <script><?php
    foreach ((array) $component as $c) {
      print "\n\n//$c\n" . load($c);
    }
    ?></script>
</head>
<body style="text-align:center;">
<a href="?">raw</a> |
<a href="?c[]=jquery.min">jquery.min</a> |
<a href="?c[]=core.min&c[]=callbacks.min&c[]=support.min&c[]=effects.min">effects.min</a> |
<a href="?c[]=core.min&c[]=manipulation.min">manipulation.min</a>
<div id="log" style="text-align:center;font-size:100px;font:georgia;margin:50px"><h2>…</h2></div>
<script><?php print str_replace(
  array('"$component$"', '"$js$"'),
  array(json_encode((array) $component), $js),
  file_get_contents('./performance.min.js')
); ?></script>
</body>
</html>

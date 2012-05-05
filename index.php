<?php

// build query string for scripts.php
function qs($comp) {
  $s = array();
  foreach ((array) $comp as $c) {
    $s[] = "c[]=$c";
  }
  return $s;
}

function load($c) {
  $path = './jquery/' . $c . '.js';
  if (file_exists($path)) {
    return file_get_contents($path);
  }
  return '';
}


$component = !empty($_GET['c']) ? $_GET['c'] : 'raw';
$query = qs($component);
$samples = !empty($_GET['s']) ? $_GET['s'] : 0;
$js = !empty($_GET['no_js']) && $_GET['no_js'] == 1 ? 0 : 1;


$tests = array(
  'raw' => array('raw'),
  'jquery' => array('jquery.min'),
  'jquery-cache' => array('cache', 'jquery.min'),
  'sizzle' => array('sizzle.min'),
  'sizzle-cache' => array('cache', 'sizzle.min'),
  'core' => array('core.min'),
  'core-cache' => array('cache', 'core.min'),
  'ajax' => array('core.min', 'ajax.min'),
  'ajax-cache' => array('cache', 'core.min', 'ajax.min'),
  'attributes' => array('core.min', 'callbacks.min', 'support.min', 'attributes.min'),
  'attributes-cache' => array('cache', 'core.min', 'callbacks.min', 'support.min', 'attributes.min'),
  'callbacks' => array('core.min', 'callbacks.min'),
  'callbacks-cache' => array('cache', 'core.min', 'callbacks.min'),
  'css' => array('core.min', 'callbacks.min', 'support.min', 'css.min'),
  'css-cache' => array('cache', 'core.min', 'callbacks.min', 'support.min', 'css.min'),
  'data' => array('core.min', 'data.min'),
  'data-cache' => array('cache', 'core.min', 'data.min'),
  'deferred' => array('core.min', 'deferred.min'),
  'deferred-cache' => array('cache', 'core.min', 'deferred.min'),
  'dimensions' => array('core.min', 'dimensions.min'),
  'dimensions-cache' => array('cache', 'core.min', 'dimensions.min'),
  'effects' => array('core.min', 'callbacks.min', 'effects.min'),
  'effects-cache' => array('cache', 'core.min', 'callbacks.min', 'effects.min'),
  'event' => array('core.min', 'callbacks.min', 'support.min', 'data.min', 'event.min'),
  'event-cache' => array('cache', 'core.min', 'callbacks.min', 'support.min', 'data.min', 'event.min'),
  'manipulation' => array('core.min', 'callbacks.min', 'support.min', 'manipulation.min'),
  'manipulation-cache' => array('cache', 'core.min', 'callbacks.min', 'support.min', 'manipulation.min'),
  'offset' => array('core.min', 'offset.min'),
  'offset-cache' => array('cache', 'core.min', 'offset.min'),
  'queue' => array('core.min', 'queue.min'),
  'queue-cache' => array('cache', 'core.min', 'queue.min'),
  'support' => array('core.min', 'callbacks.min', 'support.min'),
  'support-cache' => array('cache', 'core.min', 'callbacks.min', 'support.min'),
  'traversing' => array('core.min', 'traversing.min'),
  'traversing-cache' => array('cache', 'core.min', 'traversing.min'),
);


?><!doctype html>
<html>
<head>
  <meta charset="utf8">
  <title>jQuery perf rundown</title>
  <?php
  if (!empty($query) && $component !== 'raw') {

    $cache = !empty($_GET['cache']);

    print '<script ' . (in_array('cache', (array) $component) ? 'type="text/cache"' : '') . '>';

    if ($component !== 'raw') {
      foreach ((array) $component as $c) {
        if ($c !== 'cache') {
          print "\n//$c\n" . load($c);
        }
      }
    }

    print '</script>';

  }
  ?>
</head>
<body style="text-align:center;margin-top:20px;line-height:2;font-size:18px;">

<?php
$links = array();
foreach ($tests as $id => $t) {
  $links[] = '<a id="' . $id . '" href="?' . implode('&', qs($t)) . '">' . $id . '</a>';
}
print implode(' | ', $links);
?>
<div id="log" style="text-align:center;font-size:100px;font:georgia;margin:50px"><h2>…</h2></div>
<script><?php print str_replace(
  array('"$component$"', '"$samples$"'),
  array(json_encode((array) $component), $samples),
  file_get_contents('./performance.min.js')
); ?></script>
</body>
</html>

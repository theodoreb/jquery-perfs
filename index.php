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
$samples = !empty($_GET['s']) ? $_GET['s'] : 10;
$js = !empty($_GET['no_js']) && $_GET['no_js'] == 1 ? 0 : 1;


$tests = array(
  'raw' => array('raw'),
  'jquery' => array('jquery.min'),
  'core' => array('core.min'),
  'ajax' => array('core.min', 'ajax.min'),
  'attributes' => array('core.min', 'callbacks.min', 'support.min', 'attributes.min'),
  'callbacks' => array('core.min', 'callbacks.min'),
  'css' => array('core.min', 'callbacks.min', 'support.min', 'css.min'),
  'data' => array('core.min', 'data.min'),
  'deferred' => array('core.min', 'deferred.min'),
  'dimensions' => array('core.min', 'dimensions.min'),
  'effects' => array('core.min', 'callbacks.min', 'support.min', 'effects.min'),
  'event' => array('core.min', 'callbacks.min', 'support.min', 'attributes.min', 'data.min', 'event.min'),
  'manipulation' => array('core.min', 'callbacks.min', 'support.min', 'manipulation.min'),
  'offset' => array('core.min', 'offset.min'),
  'queue' => array('core.min', 'queue.min'),
  'support' => array('core.min', 'callbacks.min', 'support.min'),
  'traversing'  => array('core.min', 'traversing.min'),
);


?><!doctype html>
<html>
<head>
  <meta charset="utf8">
  <title>jQuery perf rundown</title>
  <?php if (!empty($query) && $component !== 'raw'): ?>
  <script src="./scripts.php?<?php print implode("&", $query); ?>"></script>
  <?php endif; ?>
</head>
<body style="text-align:center;">

<?php
  $links = array();
  foreach ($tests as $id => $t) {
    $links[] = '<a id="'. $id .'" href="?' . implode('&', qs($t)) . '">' . $id . '</a>';
  }
  print implode(' | ', $links);
  ?>

<div id="log" style="text-align:center;font-size:100px;font:georgia;margin:50px"><h2>…</h2></div>
<script><?php print str_replace(
  array('"$component$"', '"$js$"', '"$samples$"'),
  array(json_encode((array) $component), $js, $samples),
  file_get_contents('./performance.min.js')
); ?></script>
</body>
</html>

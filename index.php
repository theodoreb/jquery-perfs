<?php

/**
 * This is the dependency array of the different jQuery parts.
 *
 * Each batch of files will load without error. I'm only interested about the loading,
 * I'm not trying to split everything and have it working properly.
 */
$dependencies = array(
  'core'          => array('core.min'),
  'jquery'        => array('jquery.min'),
  'sizzle'        => array('sizzle.min'),
  'ajax'          => array('core.min', 'ajax.min'),
  'callbacks'     => array('core.min', 'callbacks.min'),
  'data'          => array('core.min', 'data.min'),
  'deferred'      => array('core.min', 'deferred.min'),
  'dimensions'    => array('core.min', 'dimensions.min'),
  'offset'        => array('core.min', 'offset.min'),
  'queue'         => array('core.min', 'queue.min'),
  'effects'       => array('core.min', 'callbacks.min', 'effects.min'),
  'support'       => array('core.min', 'callbacks.min', 'support.min'),
  'attributes'    => array('core.min', 'callbacks.min', 'support.min', 'attributes.min'),
  'css'           => array('core.min', 'callbacks.min', 'support.min', 'css.min'),
  'manipulation'  => array('core.min', 'callbacks.min', 'support.min', 'manipulation.min'),
  'traversing'    => array('core.min', 'sizzle.min',    'sizzle-jquery.min', 'traversing.min'),
  'event'         => array('core.min', 'callbacks.min', 'support.min', 'data.min', 'event.min'),
);

ksort($dependencies);

// Helper.
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
  <!--

    jQuery is loaded to make everything available to the jQuery part we're currently testing.
    This allow for meaningful error margin of benchmark.js for each part of jQuery.

  -->
  <script src="jquery/jquery.min.js"></script>
  <script src="benchmark.min.js"></script>

  <?php

  foreach ($dependencies  as $script => $dependencies) {
    $scripts[$script] = load($script . '.min');
  }
  ?>

  <script>
    var scripts = <?php print json_encode($scripts/*, JSON_PRETTY_PRINT*/); ?>;
  </script>

  <style>
    body {color:#555753;}
    table {margin:20px auto;border-collapse:collapse;}
    tr:first-child {border-bottom:1px solid #d3d7cf;font-weight:bold;}
    tr:hover {color:#2e3436;background:#eeeeec;}
    td {padding:5px;text-align:right;}
    td:first-child {text-align:left;}
  </style>
</head>
<body>
<script src="jquery-bench.js"></script>
</body>
</html>

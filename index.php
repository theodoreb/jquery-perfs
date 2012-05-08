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

  <?php foreach ($dependencies  as $script => $dependencies): ?>
  <script type="text/cache" id="<?php print $script; ?>">
    <?php print load($script . '.min'); ?>
  </script>
  <?php endforeach; ?>

  <style>ul {margin:20px;list-style:none;float:left;}</style>
</head>
<body>
<!--

  jQuery is loaded to make everything available to the jQuery part we're currently testing.
  This allow for meaningful error margin of benchmark.js for each part of jQuery.

-->
<script src="jquery/jquery.min.js"></script>
<script src="benchmark.min.js"></script>
<script>
(function () {
  var
    parent = document.body,
    table = document.createElement('table'),
    head = table.insertRow(-1),
    text = function (element, text) { var txt = document.createTextNode(text); element.appendChild(txt); };

  text(head.insertCell(-1), 'Component');
  text(head.insertCell(-1), 'Mean (ms)');
  text(head.insertCell(-1), 'Error (ms)');

  parent.appendChild(table);

  function cycle (e) {
    var
      t = e.target,
      row = table.insertRow(-1);

    row.id = 'result-' + t.name;
    text(row.insertCell(-1), t.name);
    text(row.insertCell(-1), (t.stats.mean*1000).toFixed(2).replace('.', ','));
    text(row.insertCell(-1), (t.stats.moe *1000).toFixed(2).replace('.', ','));
  }

  function wrapEvalTest (code) {
    return function () {
      // Add a random operation to avoid caching.
      eval(code + '; "' + Math.random() +'";');
    }
  }

  function benchOnload () {
    var
      scripts = document.querySelectorAll('script[id]'),
      suite = new Benchmark.Suite({'onCycle': cycle});

    // Get all the scripts.
    for (var i = 0, il = scripts.length; i < il; i += 1) {
      suite.add(scripts[i].id, wrapEvalTest(scripts[i].innerHTML));
    }
    // Async otherwise browser hangs.
    suite.run({async: true});
  }

  if (window.addEventListener) {
    window.addEventListener('load', benchOnload, false);
  }
  else {
    window.attachEvent('onload', benchOnload);
  }
}());
</script>
</body>
</html>

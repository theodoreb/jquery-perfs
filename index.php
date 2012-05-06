<?php

function load($c) {
  $path = './jquery/' . $c . '.js';
  if (file_exists($path)) {
    return file_get_contents($path);
  }
  return '';
}


$tests = array(
  'jquery' => array('jquery.min'),
  'sizzle' => array('sizzle.min'),
  'core' => array('core.min'),
  'ajax' => array('core.min', 'ajax.min'),
  'attributes' => array('core.min', 'callbacks.min', 'support.min', 'attributes.min'),
  'callbacks' => array('core.min', 'callbacks.min'),
  'css' => array('core.min', 'callbacks.min', 'support.min', 'css.min'),
  'data' => array('core.min', 'data.min'),
  'deferred' => array('core.min', 'deferred.min'),
  'dimensions' => array('core.min', 'dimensions.min'),
  'effects' => array('core.min', 'callbacks.min', 'effects.min'),
  'event' => array('core.min', 'callbacks.min', 'support.min', 'data.min', 'event.min'),
  'manipulation' => array('core.min', 'callbacks.min', 'support.min', 'manipulation.min'),
  'offset' => array('core.min', 'offset.min'),
  'queue' => array('core.min', 'queue.min'),
  'support' => array('core.min', 'callbacks.min', 'support.min'),
  'traversing' => array('core.min', 'traversing.min'),
);


?><!doctype html>
<html>
<head>
  <meta charset="utf8">
  <title>jQuery perf rundown</title>

  <?php

  foreach ($tests  as $id => $test) {
    print '<script type="text/cache" id="'. $id . '">';
    foreach ($test as $script) {
      print "\n//$script\n" . load($script);
    }
    print "\n</script>\n";
  }

   ?>
</head>
<body>
<table style="margin:50px">
  <thead><tr><th>component</th><th>mean (ms)</th><th>error (ms)</th></tr></thead>
  <tbody id="log"></tbody>
</table>
<script src="benchmark.js"></script>
<script>
  var suite;
  window.addEventListener('load', function () {

    var scripts = document.querySelectorAll('script[id]'),
        log = document.querySelector('#log'),
        scache = {},
        results = {};

    function addTest(code) {
      return function () {
        eval(code);
      }
    }

    for (var i = 0, il = scripts.length; i < il; i += 1) {
      scache[scripts[i].id] = scripts[i].innerHTML;
    }

    suite = new Benchmark.Suite;
    for (var n in scache) { if (n === 'traversing') {
      suite.add(n, addTest(scache[n]));
    }}
    suite.on('cycle', function (e) {
      var t = e.target;
      log.innerHTML += '<tr><td>' + t.name +'</td><td>'+ (t.stats.mean*1000).toFixed(8).replace('.', ',') + '</td><td>' + (t.stats.moe*1000).toFixed(8).replace('.',',') + '</td></tr>';
    });
    suite.on('complete', function() {
      //console.log('end');
    });
    suite.run({async: true});

  }, false);
</script>
</body>
</html>

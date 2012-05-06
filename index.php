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
  'traversing' => array('core.min', 'sizzle.min', 'sizzle-jquery.min', 'traversing.min'),
);


?><!doctype html>
<html>
<head>
  <meta charset="utf8">
  <title>jQuery perf rundown</title>

  <?php

  foreach ($tests  as $id => $test) {
    print '<script id="'. $id . '">';
    print "\n//$id\n" . load($id . '.min');
    //foreach ($test as $script) {
    //  print "\n//$script\n" . load($id . '.min');
    //}
    print "\n</script>\n";
  }

   ?>
</head>
<body>
<ul id="comp" style="margin:50px;list-style:none;"><li><strong>component</strong></li></ul>
<ul id="mean" style="margin:50px;list-style:none;"><li><strong>mean (ms)</strong></li></ul>
<ul id="error" style="margin:50px;list-style:none;"><li><strong>error (ms)</strong></li></ul>
<script src="benchmark.js"></script>
<script>
  function benchOnload () {

    var scripts = document.querySelectorAll('script[id]'),
        comp = document.querySelector('#comp'),
        mean = document.querySelector('#mean'),
        error = document.querySelector('#error'),
        scache = {},
        results = {};

    var suite;

    function addTest(code) {
      return function () {
        eval(code);
      }
    }

    for (var i = 0, il = scripts.length; i < il; i += 1) {
      scache[scripts[i].id] = scripts[i].innerHTML;
    }

    suite = new Benchmark.Suite;

    for (var n in scache) {
      suite.add(n, addTest(scache[n]));
    }
    suite.on('cycle', function (e) {
      var t = e.target;
      comp.innerHTML += '<li>' + t.name + '</li>';
      mean.innerHTML += '<li>' + (t.stats.mean*1000).toFixed(2).replace('.', ',') + '</li>';
      error.innerHTML += '<li>' + (t.stats.moe*1000).toFixed(2).replace('.', ',') + '</li>';
    });
    suite.on('complete', function() {
      //console.log('end');
    });

    suite.run({async: true});
  }

  if (window.addEventListener) {
    window.addEventListener('load', benchOnload, false);
  }
  else {
    window.attachEvent('onload', benchOnload);
  }
</script>
</body>
</html>

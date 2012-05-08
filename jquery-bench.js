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

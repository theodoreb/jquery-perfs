if (window['performance']) {
  window.addEventListener('load', function (e) {
    "use strict";
    setTimeout(function () {
      var
        json, n, nl,
        c = "$component$",
        body = document.body,
        perf = window['performance'],
        timing = perf.timing,
        domLoading = timing.domLoading,
        oldRaw = JSON.parse(localStorage.getItem('RAW')) || [],
        oldC = JSON.parse(localStorage.getItem(c)) || [],
        get = [
          /*'domLoading',*/ 'domInteractive', 'domContentLoadedEventStart', 'domContentLoadedEventEnd', 'domComplete'
        ],
        data = {};

      function sum (a, b) {return a + b;}

      function print (el, data) {
        el.innerHTML = [
          '<h2 style="font-size:50px">', c.join(', ') , '</h2>',
          '<ul style="list-style:none;margin:0;padding:0;">',
            //'<li>', data.domContentLoadedEventEnd - data.domContentLoadedEventStart, '&thinsp;ms</li>',
            //'<li>', data.domComplete - data.domContentLoadedEventStart, '&thinsp;ms</li>',
            '<li style="color:gray">', data.domComplete, '&thinsp;ms</li>',
            '<li>', (oldC.length && oldRaw.length) ?
              Math.round(oldC.reduce(sum) / oldC.length - oldRaw.reduce(sum) / oldRaw.length) + '&thinsp;ms' :
              'N/A', '</li>',
            '<li><button type="button" onclick="window.location.reload();" style="font-weight:bold;font-size:20px;">RELOAD</button></li>',
          '</ul>',
          '<p style="font-size:15px">' +
            'Time in gray is the difference between domComplete and domLoading for this page<br/>' +
            'Time in black is the difference between the raw time average and this page loading average.</p>',
          '<button type="button" onclick="localStorage.clear();" style="color:red;">Clear data</button>'
        ].join('');
      }

      // transform dates to relative times
      for (n = 0, nl = get.length; n < nl; n += 1) {
        data[get[n]] = timing[get[n]] - domLoading;
      }

      oldC.push(data.domComplete);
      json = JSON.stringify(oldC);
      localStorage.setItem(c.join(',') || 'RAW', json);

      var log = document.getElementById('log');
      print(log, data);
      /*
       var xhr = new XMLHttpRequest();
       xhr.open("POST", 'log.php', true);
       xhr.onreadystatechange = function (e) {
       if (xhr.readyState === 4 && xhr.status === 200) {
       var log = document.getElementById('log');
       print(log, data);
       }
       };
       xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
       xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
       xhr.send('data=' + json);
       */
    }, 50);
  }, false);
}

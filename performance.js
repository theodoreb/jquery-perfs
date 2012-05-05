if (window['performance']) {
  window.addEventListener('load', function (e) {
    "use strict";
    setTimeout(function () {
      var
        minData = "$samples$",
        json, n, nl,
        c = "$component$",
        body = document.body,
        perf = window['performance'],
        timing = perf.timing,
        domLoading = timing.domLoading,
        oldC = JSON.parse(localStorage.getItem(c)) || [],
        get = [
          /*'domLoading',*/ 'domInteractive', 'domContentLoadedEventStart', 'domContentLoadedEventEnd', 'domComplete'
        ],
        data = {};

      function sum (a, b) {
        return a + b;
      }
      function excludeMobile(a) {
        if (a > 2000) {
          return false;
        }
        return a;
      }

      function print (el, data) {
        var filC = oldC.filter(excludeMobile);
        var excluded = oldC.length - filC.length;
        el.innerHTML = [
          '<h2 style="font-size:50px">', c.join(', ') , '</h2>',
          '<ul style="list-style:none;margin:0;padding:0;">',
            //'<li>', data.domContentLoadedEventEnd - data.domContentLoadedEventStart, '&thinsp;ms</li>',
            //'<li>', data.domComplete - data.domContentLoadedEventStart, '&thinsp;ms</li>',
            '<li style="color:gray">', (data.domInteractive) + '&thinsp;ms ', '</li>',
            '<li>',
              Math.round(filC.reduce(sum) / filC.length) + '&thinsp;ms',
              (excluded > 0 ? '<br/><small style="font-size:12px!important;color:gray;">excluded: ' + excluded + '</small>' : ''),
            '</li>',
            //'<li><button type="button" onclick="window.location.reload();" style="font-weight:bold;font-size:20px;">RELOAD</button></li>',
          '</ul>',
          '<p style="font-size:15px">',
            'Time in gray is the difference between domComplete and domLoading for this page<br/>',
            'Time in black is the domComplete average.</p>',
          '<button type="button" onclick="localStorage.clear();" style="color:red;">Clear data</button>'
        ].join('');
      }

      // transform dates to relative times
      for (n = 0, nl = get.length; n < nl; n += 1) {
        data[get[n]] = timing[get[n]] - timing.domLoading;
      }

      oldC.push(data.domComplete);
      json = JSON.stringify(oldC);
      localStorage.setItem(c.join(',') || 'raw', json);

      if (oldC.length > minData) {
        var log = document.getElementById('log');
        print(log, data);
      }
      else {
        // reload until we get "minData" points.
        setTimeout(function () {
          var link = document.querySelector('a[href$="' + window.location.search.replace(/&r=.+$/, '') + '"]');
          window.location = link.getAttribute('href') + '&r='+ Math.random();
        }, 500);
      }
    }, 50);
  }, false);
}

<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['EEOC Detail Summery', 'Male', 'Female', { role: 'annotation' } ],
          ['White', 10, 24, ''],
          ['Black or African American', 16, 22, ''],
          ['Native Hawaiian or Other Pacific Islander', 28, 19, ''],
          ['Asian', 43, 29, ''],
          ['American Indian or Alaska Native', 33, 17, ''],
          ['Two or More Races', 38, 7, ''],
          ['Hispanic or Latino', 18, 23, '']
        ]);

        var options = {
          width: 600,
          height: 400,
          legend: { position: 'top', maxLines: 3 },
          bars: 'horizontal',
          isStacked: true
        };
     

        var chart = new google.charts.Bar(document.getElementById('barchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
    </script>
  </head>
  <body>
    <div id="barchart_material" style="width: 900px; height: 500px;"></div>
  </body>
</html>
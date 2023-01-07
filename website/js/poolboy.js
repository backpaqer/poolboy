function drawChart(timeinterval) {  
    $.ajax({
        url: "php/get_graph.php",
        type: "POST",
        data: {newtimeframe: timeinterval, sensor: "ds18b20_temp", label: "Temp"},
        dataType: "json",
        success: function (jsonData) {
            // Create our data table out of JSON data loaded from server.
            var data =new google.visualization.DataTable(jsonData);
            var options = {
            title: 'Air Temperature',
            curveType: 'function'
            };
            var chart = new google.visualization.LineChart(document.getElementById('ds18b20_temp_graph'));
            chart.draw(data, options);
        }
    });
    $.ajax({
        url: "php/get_graph.php",
        type: "POST",
        data: {newtimeframe: timeinterval, sensor: "atlas_temp", label: "Temp"},
        dataType: "json",
        success: function (jsonData) {
            // Create our data table out of JSON data loaded from server.
            var data =new google.visualization.DataTable(jsonData);
            var options = {
            title: 'Pool Temperature',
            curveType: 'function'
            };
            var chart = new google.visualization.LineChart(document.getElementById('atlas_temp_graph'));
            chart.draw(data, options);
        }
    });

    $.ajax({
        url: "php/get_graph.php",
        type: "POST",
        data: {newtimeframe: timeinterval, sensor: "ph", label: "pH"},
        dataType: "json",
        success: function (jsonData) {
            // Create our data table out of JSON data loaded from server.
            var data =new google.visualization.DataTable(jsonData);
            var options = {
            title: 'pH',
            curveType: 'function'
            };
            var chart = new google.visualization.LineChart(document.getElementById('ph_graph'));
            chart.draw(data, options);
        }
    });
    $.ajax({
        url: "php/get_graph.php",
        type: "POST",
        data: {newtimeframe: timeinterval, sensor: "orp", label: "mV"},
        dataType: "json",
        success: function (jsonData) {
            // Create our data table out of JSON data loaded from server.
            var data =new google.visualization.DataTable(jsonData);
            var options = {
            title: 'Oxidation Reduction Potential',
            curveType: 'function'
            };
            var chart = new google.visualization.LineChart(document.getElementById('orp_graph'));
            chart.draw(data, options);
        }
    });
    $.ajax({
        url: "php/get_graph.php",
        type: "POST",
        data: {newtimeframe: timeinterval, sensor: "ec", label: "ppm"},
        dataType: "json",
        success: function (jsonData) {
            // Create our data table out of JSON data loaded from server.
            var data =new google.visualization.DataTable(jsonData);
            var options = {
            title: 'Salinity',
            curveType: 'function'
            };
            var chart = new google.visualization.LineChart(document.getElementById('ec_graph'));
            chart.draw(data, options);
        }
    });
}

function updateClock(){
  //Configure initial variables

  tday = new Array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
  tmonth = new Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec");
  var d = new Date();
  var nday = d.getDay(), nmonth = d.getMonth(), ndate = d.getDate(), nyear = d.getYear(), nhour = d.getHours(), nmin = d.getMinutes(), nsec = d.getSeconds(), ap;

      if(nhour==0)
      {ap = " AM"; nhour = 12;}
      else if(nhour<12)
      {ap = " AM";}
      else if(nhour==12)
      {ap = " PM";}
      else if(nhour>12)
      {ap = " PM"; nhour -= 12;}
      if(ndate<2 && ndate>0)
      {ndate += "<sup>st</sup>";}
      else if(ndate<3 && ndate>1)
      {ndate += "<sup>nd</sup>";}
      else if(ndate<4 && ndate>2)
      {ndate += "<sup>rd</sup>";}
      else
      {ndate += "<sup>th</sup>";}
      if(nyear<1000) nyear += 1900;
      if(nmin<=9) nmin = "0"+nmin;
      //if(nsec<=9) nsec = "0"+nsec;

  //Compose the string for display

  var currentDateString = tmonth[nmonth] + " " + ndate + ", " + nyear + " ";
  var currentDayString = tday[nday];
  var currentTimeString = nhour + ":" + nmin + " " + ap;

  document.getElementById("mydate").innerHTML = currentDateString;
  document.getElementById("myday").innerHTML = currentDayString;
  document.getElementById("mytime").innerHTML = currentTimeString;
}

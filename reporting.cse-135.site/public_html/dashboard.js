window.addEventListener('DOMContentLoaded', init);
staticData = null;
perfData = null;
weekIds = new Set();

weekLoad = [];
allLoad = [];

function init(){
  get('static', '', barGraph);
}

function fillGrid(data){
  datacopy = [];
  for (let i = 0; i < data.length; i ++){
    if (!data[i].dateTime){
      continue;
    }
    val = { dateTime: data[i].dateTime, 
            language: data[i].language, 
            screenWidth: data[i].outerWidth, 
            screenHeight: data[i].outerHeight, 
            browser: data[i].userAgent, 
            sessionID: data[i].session  };
    datacopy.push(val);
  }
  document.getElementById("static-grid").setAttribute('data', JSON.stringify(datacopy));
  return;
}

function summary(data){
  //Do stuff here
  sessions = new Map();
  clickWeekSessions = new Set();
  clicks = 0;
  weekClicks = 0;
  for (let i = 0; i < data.length; i++){
    if (data[i].type === 'click'){
      clicks++;
      if (weekIds.has(data[i].session)){
        weekClicks++;
        clickWeekSessions.add(data[i].session);
      }
    }
    if (!Number(data[i].timestamp))
      continue;
    if (!sessions.has(data[i].session))
      sessions.set(data[i].session, Number(data[i].timestamp));
    else
      sessions.set(data[i].session, Math.max( Number(data[i].timestamp), sessions.get(data[i].session) ) );
  }

  totalClickAvg = Math.floor(clicks * 100 / sessions.size) / 100;
  weekClickAvg = Math.floor(weekClicks * 100 / clickWeekSessions.size) / 100;
  document.getElementById('week-click').innerText = '' + weekClickAvg + ' clicks';
  document.getElementById('total-click').innerText = '' + totalClickAvg + ' clicks';

  sessCount = 0;
  weekTime = 0;
  totalTime = 0;
  sessions.forEach((val, k) => {
    totalTime += val;
    if (weekIds.has(k))
      weekTime += val;
      sessCount++;
  });
  weekTime = Math.floor(weekTime * 100 / sessCount) / 100;
  totalTime = Math.floor(totalTime * 100 / sessions.size) / 100;
  document.getElementById('week-session').innerText = '' + weekTime + ' ms';
  document.getElementById('total-session').innerText = '' + totalTime + ' ms';
}

function barGraph(data){
  fillGrid(data);
  connChart(data);
  screenChart(data);
  langChart(data);
  staticData = data;
  get('performance', '', pieGraph);
  //Visits: past week, before that, undated
  visits = [0, 0, 0];

  //2D array to count entries
  //first dimension for time of day, with each index corresponding with 0:00 - 5:59, 6:00 - 11:59, etc.
  //second dimension for day, starting w/ Sunday = 0
  entryTimeCounts = [   [0,0,0,0,0,0,0],
                        [0,0,0,0,0,0,0],
                        [0,0,0,0,0,0,0],
                        [0,0,0,0,0,0,0]  ]; 

  week = new Date();
  week.setDate(week.getDate() - 7);
  for (let i = 0; i < data.length; i++){
    if (!data[i].dateTime){
      visits[2]++;
      continue;
    }

    timeString = data[i].dateTime.slice(1,-1);
    //format: YYYY-MM-DD HH:MM
    arr = timeString.split(/[\s-:]+/)
    time = new Date(parseInt(arr[0]), parseInt(arr[1]) - 1, parseInt(arr[2]), parseInt(arr[3]), parseInt(arr[4]));
    if(time >= week){
      weekIds.add(data[i].session);
      visits[0]++;
    } else {
      visits[1]++;
    }
    entryTimeCounts[ Math.floor(time.getHours() / 6) ][time.getDay()]++;
  }

  document.getElementById('week-visit').innerText = visits[0];
  document.getElementById('past-visit').innerText = visits[1];
  document.getElementById('undated-visit').innerText = visits[2];

  var configBar = {
    'type': 'bar',
    'background-color': 'white',
    'title': {
      'text': 'Time of Visit by Weekday',
      'adjust-layout': true,
    },
     'legend': {
      'layout': 'x2',
      'alpha': 0.05,
      'shadow': false,
      'align': 'center',
      'adjust-layout': true,
      'marker': {
        'type': 'circle',
        'border-color': 'none',
        'size': '10px'
      },
      'toggle-action': 'hide',
    },
    'plot': {
      'bars-space-left': 0.15,
      'bars-space-right': 0.15,
    },
     'series': [{
        'values': entryTimeCounts[0],
        'alpha': 0.95,
        'borderRadiusTopLeft': 7,
        'background-color': 'gray',
        'text': 'Midnight to 5:59 AM'
      },
      {
        'values': entryTimeCounts[1],
        'alpha': 0.95,
        'borderRadiusTopLeft': 7,
        'background-color': 'blue',
        'text': '6:00 AM to 11:59 AM'
      },
      {
        'values': entryTimeCounts[2],
        'alpha': 0.95,
        'borderRadiusTopLeft': 7,
        'background-color': 'green',
        'text': 'Noon to 5:59 PM'
      },
      {
        'values': entryTimeCounts[3],
        'borderRadiusTopLeft': 7,
        'alpha': 0.95,
        'background-color': 'orange',
        'text': '6:00 PM to 11:59 PM'
      }
    ],
    'scaleX': {
      'values': [
        'Sunday',
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday'
      ],
      'placement': 'default',
    }
  }

  zingchart.render({
    id: 'bar-chart',
    data: configBar
  });
}

// colors: ['#F05D5E', '#0F7173', '#7895A5', '#272932', '#D8A47F', '#ACF39D', '#FF9F1C', '#5F0F40', '#FFD5FF', '#141B41', '#36151E']
function screenChart(data){
  counts = new Map();
  for (let i = 0; i < data.length; i++){
    type = data[i].outerWidth + 'x' + data[i].outerHeight;
    if(!type || type === 'x')
      continue;
    if(!counts.has(type))
      counts.set(type, 0);
    counts.set(type, counts.get(type) + 1);
  }
  pairs = [];
  counts.forEach((v, k) => pairs.push([v, k]));
  
  pairs.sort(function(a, b){
    return (a[0] > b[0]) ? -1 : 1;
  });

  graphData = [];
  pairs.forEach(p => graphData.push([p[1], p[0], 1]));
  console.log(pairs);
  console.log(graphData);

  var configBar = {
    "type":"variwide",
    "title":{
      "text":"Screen Resolutions",
      "adjustLayout":true
    },
    "source":{
      "text":".",
      "adjustLayout":true
    }, 
    "options":{
      "data": graphData
    },
    "scaleX":{
      "label":{
        "text": "Resolution",
        "fontSize": 18,
        "offsetY" : 10,
        "bold":false
      },
      "item":{
        "color":"#000",
        "fontSize": 12,
        "angle":330
      }
    },
    "scaleY":{
      "label":{
        "text": "Freq.",
        "fontSize": 18,
        "bold":true
      },
        "guide":{
        "lineStyle":"solid"    
      }
    },
    "plot":{
      "valueBox":{
        "text" : "%data-value",
        "color" : "#000",
        "overlap" : false
      },
      "tooltipText" : "<span style='font-size:17px;color:%color'><b>%plot-text</b></span><br><br>Requests: <b>%data-value</b><br>"
    },
    "tooltip" : {
      "padding" : 10,
      "fontSize" : 12,
      "backgroundColor":"#fff",
      "alpha" : 0.9,
      "color" : "#000",
      "align" : "left",
      "borderRadius" : 7,
      "borderWidth" : 2,
      "offsetY" : 5,
      "shadow" : true,
      "shadowDistance" : 2,
      "borderColor" : "%color-1",
      "placement" : "node:top",
      "callout" : true,
      "text" : "<span style='font-size:17px;color:%color;font-weight:bold;'>\u220e %plot-text</span><br><br>Requests: <b>%data-value</b><br>"
    }    
  };

  zingchart.render({
    id: 'lang-chart',
    data: configBar
  });

}

//Map pie to data + colors for a max of 5 colors
//pairs = array of [counts, title]
function mapPieColors(pairs, colors){
  if (pairs.size === 1){
    return [{values: [1], text: pairs[0][1], backgroundColor: colors[0]}];
  }
  ret = [];
  sum = 0;
  for (let i = 0; i < 5 && i < pairs.length; i++)
    sum += pairs[i][0];

  for (let i = 0; i < 5 && i < pairs.length; i++){
    val = {
      values:[ Math.floor(pairs[i][0] * 100 / sum) / 100 ],
      text: pairs[i][1],
      backgroundColor: colors[i],
      detached: false
    };
    if (i == 0)
      val.detached = true;
    ret.push(val);
  }
  return ret;
}

function langChart(data){
  counts = new Map();
  for (let i = 0; i < data.length; i++){
    type = data[i].language;
    if(!type)
      continue;
    if(!counts.has(type))
      counts.set(type, 0);
    counts.set(type, counts.get(type) + 1);
  }
  pairs = [];
  counts.forEach((v, k) => pairs.push([v, k]));
  
  pairs.sort(function(a, b){
    return (a[0] > b[0]) ? -1 : 1;
  });

  if (pairs.length > 5){
    pairs[4][1] = 'Other';
    for (let i = 5; i < pairs.length; i++)
      pairs[4][0] += pairs[i][0];
  }

  graphData = mapPieColors(pairs, ['#F2756C', '#CAE6E9', '#E8CF2E', '#78CBD6', '#D5C7F2']);

  var configPie = {
    type: 'pie',
     title: {
       text: 'Languages Requested',
       align: 'center',
       offsetX: 10,
       fontSize: 25
    },
    valueBox: {
      placement: 'out',
      text: '%t\n%npv%',
      fontFamily: 'Open Sans'
    },
    legend: {
      backgroundColor: '#eee',
      fontSize: '1.5em',
      width: '150em'
    },
    tooltip: {
      fontSize: '18',
      fontFamily: 'Open Sans',
      padding: '5 10',
      text: '%npv%'
    },
    series: graphData
  }

  zingchart.render({
    id: 'screen-chart',
    data: configPie
  });
}


function connChart(data){
  counts = new Map();
  for (let i = 0; i < data.length; i++){
    type = data[i].effectiveType;
    if(!type)
      continue;
    if(!counts.has(type))
      counts.set(type, 0);
    counts.set(type, counts.get(type) + 1);
  }
  pairs = [];
  counts.forEach((v, k) => pairs.push([v, k]));
  
  pairs.sort(function(a, b){
    return (a[0] > b[0]) ? -1 : 1;
  });

  if (pairs.length > 5){
    pairs[4][1] = 'Other';
    for (let i = 5; i < pairs.length; i++)
      pairs[4][0] += pairs[i][0];
  }

  graphData = mapPieColors(pairs, ['#CD94D3', '#85A1C6', '#43D7B9', '#BD8E95', '#D6E5F7']);

  var configPie = {
    type: 'pie',
     title: {
       text: 'Connection Types',
       align: 'center',
       offsetX: 10,
       fontSize: 25
    },
    valueBox: {
      placement: 'out',
      text: '%t\n%npv%',
      fontFamily: 'Open Sans'
    },
    legend: {
      backgroundColor: '#eee',
      fontSize: '1.5em',
      width: '150em'
    },
    tooltip: {
      fontSize: '18',
      fontFamily: 'Open Sans',
      padding: '5 10',
      text: '%npv%'
    },
    series: graphData
  }

  zingchart.render({
    id: 'conn-chart',
    data: configPie
  });
}

function pieGraph(data){
  perfData = data;

  get('activity', '', summary);
  //time to load: <400ms, 400-600, 600-800, 800+
  //4 categories
  total = 0
  counts = [0,0,0,0];
  
  for (let i = 0; i < data.length; i++){
    let time = Number(data[i].duration);
    if (time && time >0)
      allLoad.push(time);
    if (weekIds.has(data[i].session)){
      weekLoad.push(time);
    }

    if (time < 400)
      counts[0]++;
    else if (time < 600)
      counts[1]++
    else if (time < 800)
      counts[2]++
    else
      counts[3]++
    total++;
  }

  sum = 0;
  weekLoad.forEach(time => sum += time);
  weekAvg = Math.floor(sum * 100 / weekLoad.length) / 100;
  document.getElementById('week-load').innerText = '' + weekAvg + ' ms';

  sum = 0;
  allLoad.forEach(time => sum += time);
  allAvg = Math.floor(sum * 100 / weekLoad.length) / 100;
  document.getElementById('total-load').innerText = '' + allAvg + ' ms';

  for (let i = 0; i < 4; i++)
    counts[i] = Math.floor(100 * counts[i] / total) / 100;

  var configPie = {
    type: 'pie',
     title: {
       text: 'Time to Load',
       align: 'center',
       offsetX: 10,
       fontSize: 25
    },
    valueBox: {
      placement: 'out',
      text: '%t\n%npv%',
      fontFamily: 'Open Sans'
    },
    legend: {
      backgroundColor: '#eee',
      fontSize: '1.5em',
      width: '150em'
    },
    tooltip: {
      fontSize: '18',
      fontFamily: 'Open Sans',
      padding: '5 10',
      text: '%npv%'
    },
    series: [{
      values: [counts[0]],
      text: '< 400 ms',
      backgroundColor: '#92BCEA',
      detached: true
    },
    {
      values: [counts[1]],
      text: '400-599 ms',
      backgroundColor: '#5E7A97'
    },
    {
      values: [counts[2]],
      text: '600-799 ms',
      backgroundColor: '#617073'
    },
    {
      text: '800+ms',
      values: [counts[3]],
      backgroundColor: '#171A21'
    }]
  }

  zingchart.render({
    id: 'pie-chart',
    data: configPie
  });
}

//Not used
function graph(data){
  x = [];
  y = [];
  offsety = [];
  clicks = 0;
  positions = 0;
  
  data.sort(function(a, b){
    return (Number(a.timestamp) < Number(b.timestamp)) ? -1 : 1;
  });

  for (let i = 0; i < data.length; i++){
    x.push([data[i].timestamp, data[i].x]);
    y.push([data[i].timestamp, data[i].y]);
    offsety.push([data[i].timestamp, data[i].offsety]);
    if (data[i].type === 'position')
      positions++;

    if (data[i].type === 'click')
      clicks++;
    
  }  


  var configLine = {
    /*'scale-x': {
      'min-value': '7000',
      'step': '250',
      'max-value': '10000'
    },*/
    'title': {
      'text': 'Cursor Position Over Time',
      'font-size': '24px',
      'adjust-layout': true
    },
    'plotarea': {
        'margin': 'dynamic 45 60 dynamic',
      },
      'legend': {
        'layout': 'float',
        'background-color': 'none',
        'border-width': 0,
        'shadow': 0,
        'align': 'center',
        'adjust-layout': true,
        'toggle-action': 'remove',
        'item': {
          'padding': 7,
          'marginRight': 17,
          'cursor': 'hand'
        }
      },
    'type': 'line',
    'series': [
      { 
        'values': x,
        'text': 'X-value'
      },
      { 
        'values': y, 
        'text': 'Y-value'
      },
      { 
        'values': offsety,
        'text': 'OffsetY-value'
      }
    ]
  }

  zingchart.render({
    id: 'scatter',
    data: configLine
  });
}

function get(endpoint, id, next){
    fetch(
      'https://cse-135.site/php_api/' + endpoint + '/' + id
    ).then(
      function(response){
        if (response.status !== 200) {
          return [];
        }

        response.json().then(function(data) {
          next(data);
        });
      }
    );
}

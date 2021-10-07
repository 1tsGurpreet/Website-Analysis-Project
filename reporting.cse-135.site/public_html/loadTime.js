window.addEventListener('DOMContentLoaded', init);
staticData = null;
perfData = null;
weekIds = new Set();
monthIds = new Set();

weekPerf = [];
monthPerf = [];

function init(){
  get('static', '', barGraph);
}

function barGraph(data){
  staticData = data;
  //Visits: past week, past 30 das, before that, undated
  visits = [0, 0, 0, 0];

  week = new Date();
  week.setDate(week.getDate() - 7);
  //We're going to call 30 days a month
  month = new Date();
  month.setDate(month.getDate() - 30);

  for (let i = 0; i < data.length; i++){
    if (!data[i].dateTime){
      visits[3]++;
      continue;
    }

    timeString = data[i].dateTime.slice(1,-1);
    //format: YYYY-MM-DD HH:MM
    arr = timeString.split(/[\s-:]+/)
    time = new Date(parseInt(arr[0]), parseInt(arr[1]) - 1, parseInt(arr[2]), parseInt(arr[3]), parseInt(arr[4]));
    if(time >= week){
      weekIds.add(data[i].session);
      monthIds.add(data[i].session);
      visits[0]++;
      visits[1]++;
    } else if (time >= month){
      monthIds.add(data[i].session);
      visits[1]++;
    } else
      visits[2]++;
  }

  get('performance', '', summary);
  document.getElementById('week-visit').innerText = visits[0];
  document.getElementById('month-visit').innerText = visits[1];
  document.getElementById('past-visit').innerText = visits[2];
  document.getElementById('undated-visit').innerText = visits[3];
  document.getElementById('total-visit').innerText = (visits[3] + visits[2] + visits[1]);
}

function summary(data){
  perfData = data;
  weekLoad = [];
  monthLoad = [];
  pastLoad = [];
  allLoad = [];
  
  for (let i = 0; i < data.length; i++){
    let time = Number(data[i].duration);
    if (time && time >0)
      allLoad.push(time);
    else
      continue;

    if (weekIds.has(data[i].session)){
      weekLoad.push(time);
      weekPerf.push(data[i]);
    }
    if (monthIds.has(data[i].session)){
      monthLoad.push(time);
      monthPerf.push(data[i]);
    }
  }

  sum = 0;
  weekLoad.forEach(time => sum += time);
  weekAvg = 'NaN';
  if (weekLoad.length > 0 )
    weekAvg = Math.floor(sum * 100 / weekLoad.length) / 100;
  document.getElementById('week-load').innerText = '' + weekAvg + ' ms';

  sum = 0;
  monthLoad.forEach(time => sum += time);
  monthAvg = 'NaN';
  if (monthLoad.length > 0 )
    monthAvg = Math.floor(sum * 100 / monthLoad.length) / 100;
  document.getElementById('month-load').innerText = '' + monthAvg + ' ms';

  sum = 0;
  allLoad.forEach(time => sum += time);
  allAvg = 'NaN';
  if (allLoad.length > 0 )
    allAvg = Math.floor(sum * 100 / weekLoad.length) / 100;
  document.getElementById('total-load').innerText = '' + allAvg + ' ms';

  color1 = ['#92BCEA', '#5E7A97', '#617073', '#171A21'];
  color2 = ['#0F7173','#A2A2ff', '#7895A5', '#272932'];
  color3 = ['#D8A47F', '#FF9F1C', '#5F0F40', '#F05D5E'];

  graphPie(data, 'Time to Load (All Time)', 'pie-chart', color1);
  graphPie(weekPerf, 'Time to Load (Past 7 Days)', 'week-pie-chart', color2);
  graphPie(monthPerf, 'Time to Load (Past 30 Days)', 'month-pie-chart', color3);

  graphBar(weekPerf, 'Percent of time spent in each interval (Last 7 days)', 'week-percent-chart');
  graphBar(monthPerf, 'Percent of time spent in each interval (Last 30 days)', 'month-percent-chart');
}

function graphPie(data, title, chartId, colors){
  //time to load: <400ms, 400-600, 600-800, 800+
  //4 categories
  total = 0
  counts = [0,0,0,0];
  
  for (let i = 0; i < data.length; i++){
    let time = Number(data[i].duration);
    if (!time)
      continue;

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

  for (let i = 0; i < 4; i++)
    counts[i] = Math.floor(100 * counts[i] / total) / 100;

  var configPie = {
    type: 'pie',
    title: {
       text: title,
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
      backgroundColor: colors[0],
      detached: true
    },
    {
      values: [counts[1]],
      text: '400-599 ms',
      backgroundColor: colors[1]
    },
    {
      values: [counts[2]],
      text: '600-799 ms',
      backgroundColor: colors[2]
    },
    {
      text: '800+ms',
      values: [counts[3]],
      backgroundColor: colors[3]
    }]
  }

  zingchart.render({
    id: chartId,
    data: configPie
  });
}

function graphBar(data, title, graphId){
  //colors hard coded
  colors = ['#87080c', '#02b67d', '#a26769', '#2a3b7a', '#35605a', '#7678ed', '#88776d'];

  //total ms for <200, 200-400, ..., 800-1000, 1000+ (6 categories)
  total = [0,0,0,0,0,0];

  //2D array
  //first dimension is which interval it is eg. start to fetchStart
  //second dimension is which ms bucket it falls under
  counts = [[0,0,0,0,0,0],
            [0,0,0,0,0,0],
            [0,0,0,0,0,0],
            [0,0,0,0,0,0],
            [0,0,0,0,0,0],
            [0,0,0,0,0,0],
            [0,0,0,0,0,0],  ];
  for(let i = 0; i < data.length; i++){
    duration = Number(data[i].duration);
    if (!duration || duration < 1)
      continue;
    bucket = Math.min(Math.floor(duration / 200), 5);
    total[bucket] += duration;

    //redirect
    duration = data[i].fetchStart - data[i].startTime;
    if (duration)
     counts[0][bucket] += duration;

    //connecting
    duration = data[i].requestStart - data[i].fetchStart;
    if (duration)
      counts[1][bucket] += duration;

    //request sent
    duration = data[i].responseStart - data[i].requestStart;
    if (duration)
      counts[2][bucket] += duration;

    //response
    duration = data[i].responseEnd - data[i].responseStart;
    if (duration)
      counts[3][bucket] += duration;

    //HTML parsed
    duration = data[i].domInteractive - data[i].responseEnd;
    if (duration)
      counts[4][bucket] += duration;

    //webpage loaded
    duration = data[i].loadEventStart - data[i].domInteractive;
    if (duration)
      counts[5][bucket] += duration;

    //onload events
    duration = data[i].loadEventEnd - data[i].loadEventStart;
    if (duration)
      counts[6][bucket] += duration;
  }

  //Turn them into percentages:
  for(let i = 0; i < counts.length; i++){
    for (let bucket = 0; bucket < counts[i].length; bucket++){
      counts[i][bucket] = Math.floor(counts[i][bucket] * 10000 / total[bucket]) / 100;
    }
  }

  var barConfig = {
    type: "hbar",
    plotarea: {
      adjustLayout:true
    },
    title: {
       text: title,
       align: 'center',
       fontSize: 25,
       adjustLayout: true
    },
    plot: {
      stacked: true,
    },
    scaleX: {
      label:{
        text:"Overall Load Time",
        fontSize: 18
      },
      labels:["< 200 ms","200-399 ms","400-599 ms","600-799 ms","800-1000 ms","1000+ ms"]
    },
    scaleY: {
      label:{
        text:"Percent",
        fontSize: 18
      },
      minValue: 0,
      maxValue: 100,
      stepValue: 10
    },
    legend: {
      adjustLayout: true,
      backgroundColor: '#eee',
      toggleAction: 'hide'
    },
    series: [
      {
        values: counts[0],
        stack:1,
        backgroundColor: colors[0],
        text: 'Redirection'
      },
      {
        values: counts[1],
        stack:1,
        backgroundColor: colors[1],
        text: 'Connecting'
      },
      {
        values: counts[2],
        stack:1,
        backgroundColor: colors[2],
        text: 'Request Sent'
      },
      {
        values: counts[3],
        stack:1,
        backgroundColor: colors[3],
        text: 'HTTP Response'
      },
      {
        values: counts[4],
        stack:1,
        backgroundColor: colors[4],
        text: 'HTML Parsing'
      },
      {
        values: counts[5],
        stack:1,
        backgroundColor: colors[5],
        text: 'Webpage Loading'
      },
      {
        values: counts[6],
        stack:1,
        backgroundColor: colors[6],
        text: 'OnLoad Events'
      },
    ]
  };
  
  zingchart.render({ 
    id : graphId, 
    data : barConfig
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


window.addEventListener("DOMContentLoaded", init);

function init(){
  get('activity', '941ec2a4-1649-4fc4-8a9a-0c07c156141d', graph);
  get('performance', '', pieGraph);
}

//Not used for now
function findASession(data){
  get('activity', data[0].session, graph);
}

function pieGraph(data){
  //time to load: <400ms, 400-500, 500-600, 600-700, 700-800, 800-900, 900+
  //7 categories
  total = 0
  counts = [0,0,0,0,0,0,0];
  
  for (let i = 0; i < data.length; i++){
    let time = Number(data[i].duration);
    console.log(time);
    if (time < 400)
      counts[0]++;
    else if (time < 500)
      counts[1]++
    else if (time < 600)
      counts[2]++
    else if (time < 700)
      counts[3]++
    else if (time < 800)
      counts[4]++
    else if (time < 900)
      counts[5]++
    else
      counts[6]++
    total++;
  }
  for (let i = 0; i < 7; i++)
    counts[i] = Math.floor(100 * counts[i] / total) / 100;

  var configPie = {
    "type": 'pie',
     title: {
       text: 'Time to Load',
       align: "center",
       offsetX: 10,
       fontSize: 25
     },
    legend: {
      'background-color': "#eee",
    },
    "valueBox": {
      "placement": 'out',
      "text": '%t\n%npv%',
      "fontFamily": "Open Sans"
    },
    "tooltip": {
      "fontSize": '18',
      "fontFamily": "Open Sans",
      "padding": "5 10",
      "text": "%npv%"
    },
    "series": [{
      "values": [counts[0]],
      "text": "< 400 ms",
      "backgroundColor": '#171A21',
    },
    {
      "values": [counts[1]],
      "text": "400-499 ms",
      "backgroundColor": '#617073',
      "detached": true
    },
    {
      "values": [counts[2]],
      "text": '500-599 ms',
      "backgroundColor": '#5E7A97',
      "detached": true
    },
    {
      "text": '600-699 ms',
      "values": [counts[3]],
      "backgroundColor": '#92BCEA'
    },
    {
      "text": '700-799 ms',
      "values": [counts[4]],
      "backgroundColor": '#6A71F0'
    },
    {
      "text": '800-899 ms',
      "values": [counts[5]],
      "backgroundColor": '#FAE1DF'
    },
    {
      "text": '900+ ms',
      "values": [counts[6]],
      "backgroundColor": '#E4C3AD'
    }]
  }

  zingchart.render({
    id: 'pie-chart',
    data: configPie
  });
}

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
    /*"scale-x": {
      "min-value": "7000",
      "step": "250",
      "max-value": "10000"
    },*/
    "title": {
      "text": "Cursor Position Over Time",
      "font-size": "24px",
      "adjust-layout": true
    },
    "plotarea": {
        "margin": "dynamic 45 60 dynamic",
      },
      "legend": {
        "layout": "float",
        "background-color": "none",
        "border-width": 0,
        "shadow": 0,
        "align": "center",
        "adjust-layout": true,
        "toggle-action": "remove",
        "item": {
          "padding": 7,
          "marginRight": 17,
          "cursor": "hand"
        }
      },
    "type": 'line',
    "series": [
      { 
        "values": x,
        "text": "X-value"
      },
      { 
        "values": y, 
        "text": "Y-value"
      },
      { 
        "values": offsety,
        "text": "OffsetY-value"
      }
    ]
  }

  var configBar = {
    "type": "bar",
    "background-color": "white",
    "title": {
      "text": "Clicks and Cursor Positions recorded",
      "adjust-layout": true,
    },
     "legend": {
      "layout": "x3",
      "alpha": 0.05,
      "shadow": false,
      "align": "center",
      "adjust-layout": true,
      "marker": {
        "type": "circle",
        "border-color": "none",
        "size": "10px"
      },
      "toggle-action": "hide",
    },
    "plot": {
      "bars-space-left": 0.15,
      "bars-space-right": 0.15,
    },
     "series": [{
        "values": [
          clicks
        ],
        "alpha": 0.95,
        "borderRadiusTopLeft": 7,
        "background-color": "purple",
        "text": "Clicks",
      },
      {
        "values": [
          positions
        ],
        "borderRadiusTopLeft": 7,
        "alpha": 0.95,
        "background-color": "orange",
        "text": "Positions Recorded"
      }
    ],
    "scaleX": {
      "values": [
        "Session " + data[0].session
      ],
      "placement": "default",
    }
  }

  zingchart.render({
    id: 'line-3-chart',
    data: configLine,
  });

  zingchart.render({
    id: 'bar-2-chart',
    data: configBar
  });
}

function get(endpoint, id, next){
    fetch(
      '/php_api/' + endpoint + '/' + id
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


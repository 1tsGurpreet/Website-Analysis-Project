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

var myConfig4 = {
  "type": "scatter",
  "series": [{
      "values": [
        [1, 9],
        [2, 15],
        [3, 21],
        [4, 30],
        [5, 40],
        [6, 59],
        [7, 60],
        [8, 75],
        [9, 81],
        [10, 99]
      ]
    },
    {
      "values": [
        [0.9, 3],
        [2.1, 13],
        [3.5, 25],
        [4.9, 35],
        [5.3, 41],
        [6.5, 57],
        [7.1, 61],
        [8.7, 70],
        [9.2, 82],
        [9.9, 95]
      ]
    },
    {
      "values": [
        [0.1, 9],
        [1.8, 21],
        [1.9, 29],
        [4.1, 33],
        [4.5, 39],
        [6.9, 51],
        [7.4, 64],
        [8.9, 70],
        [9, 75],
        [9.3, 93]
      ]
    },
    {
      "values": [
        [0.3, 11],
        [0.9, 15],
        [1.1, 24],
        [2.3, 29],
        [2.9, 30],
        [3.3, 35],
        [5.6, 67],
        [6.9, 70],
        [7.3, 71],
        [8.9, 90]
      ]
    },
    {
      "values": [
        [0.5, 5],
        [1.9, 5],
        [2.5, 10],
        [3.1, 30],
        [6.5, 45],
        [6.9, 74],
        [7.2, 50],
        [7.8, 56],
        [8, 61],
        [8.5, 71]
      ]
    }
  ]
};
 
zingchart.render({
  id: 'myChart',
  data: myConfig4,
  height: 400,
  width: "100%"
});






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


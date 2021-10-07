/**
 * collector.js
 *
 * Creator: Camdyn Rasque
 * Date: August 17th, 2021
 *
 * Description: This script will collect all of the static, performance, and
 * activity data for a user visiting a webpage
 *
 * Note: You may modify this file as much as you like, so long as you do not 
 * remove any of the metrics that it collects.
 */


/**
 * I've created one singular data object to hold everything for you for ease of
 * use. I've exported it so you can import it to your own modules for some
 * cleaner code if you like. I've initialized everything to null or empty
 * arrays so that you can see what data is available to you easily. If anything
 * is still null when you try to read it, then either that event hasn't
 * occurred yet or that data isn't available in the user's browser (e.g. the 
 * connection object inside the static object is a chromium feature I believe).
 * I implore you to be curious, poke around, and look up any metrics that you
 * are unfamiliar with.
 * 
 * Note: The static and performance objects have a "ready" key, this is just to
 * let you know that those objects have been set and are ready to be sent.
 * Activity doesn't have one since it's continuous
 */
export const data = {
  static: {
    userAgent: null,
    language: null,
    acceptsCookies: null,
    dateTime: null,
    screenDimmensions: {
      inner: {
        innerWidth: null,
        innerHeight: null
      },
      outer: {
        outerWidth: null,
        outerHeight: null
      }
    },
    connection: {
      downlink: null,
      effectiveType: null,
      rtt: null,
      saveData: null
    },
    ready: false,
    session: null
  },
  performance: {
    startTime: null,
    fetchStart: null,
    requestStart: null,
    responseStart: null,
    responseEnd: null,
    domInteractive: null,
    domContentLoadedEventStart: null,
    domContentLoadedEventEnd: null,
    domComplete: null,
    loadEventStart: null,
    loadEventEnd: null,
    duration: null,
    transferSize: null,
    decodedBodySize: null,
    session: null,
    ready: false
  },
  activity: {
    mousePosition: [],
    mouseClicks: [],
    keystrokes: {
      keydown: [],
      keyup: []
    },
    timing: {
      pageEnter: null,
      pageLeave: null,
      currPage: null
    },
    session: null
  }
};

// Get the current time as soon as this script loads for an accurate page enter
data.activity.timing.pageEnter = new Date().getTime();
// Get the URL path as well since that does not require the page to load
data.activity.timing.currPage = window.location.pathname;
// Right before the user leaves the page, capture the time and store it
window.addEventListener('beforeunload', () => {
  data.activity.timing.pageLeave = new Date().getTime();
});

/**
 * Collects all of the static data outlined in the data object above
 */
function collectStaticData() {
  data.static.userAgent = navigator.userAgent;
  data.static.language = navigator.language;
  data.static.acceptsCookies = navigator.cookieEnabled;
  data.static.screenDimmensions = {
    inner: {
      innerWidth: window.innerWidth,
      innerHeight: window.innerHeight
    },
    outer: {
      outerWidth: window.outerWidth,
      outerHeight: window.outerHeight
    }
  };
  if (navigator.connection) {
    data.static.connection = {
      downlink: navigator.connection.downlink,
      effectiveType: navigator.connection.effectiveType,
      rtt: navigator.connection.rtt,
      saveData: navigator.connection.saveData
    };
  }
  var d = new Date();
  data.static.dateTime = d.getFullYear() + "-" + ("0"+(d.getMonth()+1)).slice(-2) + "-" + ("0" + d.getDate()).slice(-2) + " " + ("0" + d.getHours()).slice(-2) + ":" + ("0" + d.getMinutes()).slice(-2);
  data.static.ready = true;
}

/**
 * Collects all of the performance data outlined in the data object above
 */
function collectPerformanceData() {
  let perf = performance.getEntriesByType('navigation')[0];
  // Safari doesn't support the new PerformanceNavigationTiming API yet
  perf ||= performance.timing;
  // Call this method every 250ms to check if loading has finished
  if (perf.loadEventEnd == 0) {
    setTimeout(collectPerformanceData, 250);
  } else {
    data.performance.startTime = perf.startTime;
    data.performance.fetchStart = perf.fetchStart;
    data.performance.requestStart = perf.requestStart;
    data.performance.responseStart = perf.responseStart;
    data.performance.responseEnd = perf.responseEnd;
    data.performance.domInteractive = perf.domInteractive;
    data.performance.domContentLoadedEventStart = perf.domContentLoadedEventStart;
    data.performance.domContentLoadedEventEnd = perf.domContentLoadedEventEnd;
    data.performance.domComplete = perf.domComplete;
    data.performance.loadEventStart = perf.loadEventStart;
    data.performance.loadEventEnd = perf.loadEventEnd;
    data.performance.duration = perf.duration;
    data.performance.transferSize = perf.transferSize;
    data.performance.decodedBodySize = perf.decodedBodySize;
    data.performance.ready = true;

    let endpoint = '/php_api/';
    retryUntilSuccess(endpoint + 'performance', JSON.stringify(data.performance));
    sendAct(endpoint);
    /*fetch(
      endpoint + 'performance', {
        method: 'post',
        headers: {
          "Content-type": "application/json"
        },
        body: JSON.stringify(data.performance)
      }
    );*/
  }
}

/**
 * Binds all of the event listeners for mouse clicks and keystrokes
 */
function bindActivityEvents() {
  let mousemoveEvents = 0;

  // Record every 10th mouse coordinate inside the window (there will be a lot)
  window.addEventListener('mousemove', e => {
    mousemoveEvents += 1;
    if (mousemoveEvents % 10 != 0) return;
    let newMouseMove = {
      coordinates: {
        clientX: e.clientX,
        clientY: e.clientY,
        layerX: e.layerX,
        layerY: e.layerY,
        offsetX: e.offsetX,
        offsetY: e.offsetY,
        pageX: e.pageX,
        pageY: e.pageY,
        screenX: e.screenX,
        screenY: e.screenY,
        x: e.x,
        y: e.y,
      },
      altKey: e.altKey,
      ctrlKey: e.ctrlKey,
      shiftKey: e.shiftKey,
      timestamp: e.timeStamp
    };
    data.activity.mousePosition.push(newMouseMove);
  });

  // Record all mouse clicks inside the window
  window.addEventListener('click', e => {
    let newClick = {
      coordinates: {
        clientX: e.clientX,
        clientY: e.clientY,
        layerX: e.layerX,
        layerY: e.layerY,
        offsetX: e.offsetX,
        offsetY: e.offsetY,
        pageX: e.pageX,
        pageY: e.pageY,
        screenX: e.screenX,
        screenY: e.screenY,
        x: e.x,
        y: e.y,
      },
      altKey: e.altKey,
      ctrlKey: e.ctrlKey,
      shiftKey: e.shiftKey,
      timestamp: e.timeStamp
    };
    data.activity.mouseClicks.push(newClick);
  });

  // Record all keydowns inside the window
  window.addEventListener('keydown', e => {
    let newKeydown = {
      key: e.key,
      code: e.code,
      altKey: e.altKey,
      ctrlKey: e.ctrlKey,
      shiftKey: e.shiftKey,
      timestamp: e.timeStamp
    };
    data.activity.keystrokes.keydown.push(newKeydown);
  });

  // Record all keyups inside the window
  window.addEventListener('keyup', e => {
    let newKeyup = {
      key: e.key,
      code: e.code,
      altKey: e.altKey,
      ctrlKey: e.ctrlKey,
      shiftKey: e.shiftKey,
      timestamp: e.timeStamp
    };
    data.activity.keystrokes.keyup.push(newKeyup);
  });
}

/**
 * The "initialize" function here begins the collector program by calling all
 * of the necessary methods. Organizing the code this way makes sure that
 * nothing runs before it is ready to run.
 */
function init() {
  collectStaticData();
  collectPerformanceData();
  bindActivityEvents();
  // Send initial data here
  data.static.session = uuidv4();
  data.performance.session = data.static.session;
  data.activity.session = data.static.session;
  let endpoint = '/php_api/';
  retryUntilSuccess(endpoint + 'static', JSON.stringify(data.static));

  /*fetch(
    endpoint + 'static', {
      method: 'post',
      headers: {
        "Content-type": "application/json"
      },
      body: JSON.stringify(data.static)
    }
  )*/
  setInterval(function(){sendAct(endpoint)}, 5000);
}

function sendAct(endpoint){
  retryUntilSuccess(endpoint + 'activity', JSON.stringify(data.activity));
  /*fetch(
    endpoint + 'activity', {
      method: 'post',
      headers: {
        "Content-type": "application/json"
      },
      body: JSON.stringify(data.activity)
    }
  );*/
  //clear activity except for session
  data.activity.mousePosition = [];
  data.activity.mouseClicks = [];
  data.activity.keystrokes.keydown = [];
  data.activity.keystrokes.keyup = [];
}

//limit it to trying 100 times
function retryUntilSuccess(addr, toSend, limit = 100){
  if (limit == 0)
    return;
  fetch(
    addr, {
      method: 'post',
      headers: {
        "Content-type": "application/json"
      },
      body: toSend
    }
  ).then(function(response){
    if (response.status != 201){
        retryUntilSuccess(addr, toSend, limit - 1);
    }
  });  
}

function uuidv4() {
  return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
    var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
    return v.toString(16);
  });
}

// The initilize function will run once the DOM has been parsed which gives
// some time for things to load
window.addEventListener('DOMContentLoaded', init);

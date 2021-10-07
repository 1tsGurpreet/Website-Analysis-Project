window.addEventListener("DOMContentLoaded", init);

function init(){
    get('static', document.getElementById("static-grid"));
    get('performance', document.getElementById("perf-grid"));
    get('activity', document.getElementById("act-grid"));
}

function get(endpoint, obj){
    fetch(
      '/php_api/' + endpoint
    ).then(
      function(response){
        if (response.status !== 200) {
          obj.setAttribute('data', '[]');
          return;
        }

        response.json().then(function(data) {
          obj.setAttribute('data', JSON.stringify(data));
          return;
        });
      }
    );
}

function show(toShow){
    document.getElementById("static-grid").style.display = "none";
    document.getElementById("perf-grid").style.display = "none";
    document.getElementById("act-grid").style.display = "none";
    document.getElementById(toShow).style.display = "table";
}

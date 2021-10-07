window.addEventListener("DOMContentLoaded", init);

function init(){
    document.getElementById("cool").addEventListener("click", addPulse);
    document.getElementById("uncool").addEventListener("click", rmPulse);
}

function addPulse(){
    document.getElementById("sq").classList.add("sq-animate");
}

function rmPulse(){
    document.getElementById("sq").classList.remove("sq-animate");
}

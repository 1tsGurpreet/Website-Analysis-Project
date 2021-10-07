window.addEventListener("DOMContentLoaded", init);

function init(){
    document.getElementById("headingBtn").addEventListener("click", changecolor);

}

function changecolor(){
    document.getElementById("mainheading").style.color="red";
}



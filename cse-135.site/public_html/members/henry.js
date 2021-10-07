//void buton1 = document.getElementById("clickon")

//buton1.addEventListener("click", function(){ clickon.style.background = "lightblue" });
window.addEventListener("DOMContentLoaded", show);

function show(){
    document.getElementById("Btn").addEventListener("click", changecolor);

}

function changecolor(){
    document.getElementById("clickon").style.color="blue";
    alert("Thank you, this is my favorite color!");
}

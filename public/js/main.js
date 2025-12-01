const p1 = document.querySelector("#p1");
const p2 = document.querySelector("#p2");
const p3 = document.querySelector("#p3");
const p4 = document.querySelector("#p4");
const btnP2 = document.querySelector("#btnP2");
const btnP3 = document.querySelector("#btnP3");
const btnP4 = document.querySelector("#btnP4");

p2.style.display = "none";
p3.style.display = "none";
p4.style.display = "none";

btnP2.addEventListener("click", function(event) {
    event.preventDefault();
    p1.style.display = "none";
    p2.style.display = "block";
    p3.style.display = "none";
    p4.style.display = "none";
});

btnP3.addEventListener("click",  function(event) {
    event.preventDefault();
    p1.style.display = "none";
    p2.style.display = "none";
    p3.style.display = "block";
    p4.style.display = "none";
});

btnP4.addEventListener("click",  function(event) {
    event.preventDefault();
    p1.style.display = "none";
    p2.style.display = "none";
    p3.style.display = "none";
    p4.style.display = "block";
});
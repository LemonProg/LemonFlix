let pp = document.querySelectorAll("#pp");

function sleep(milliseconds) {
    var start = new Date().getTime();
    for (var i = 0; i < 1e7; i++) {
      if ((new Date().getTime() - start) > milliseconds){
        break;
      }
    }
}

function fadeStart(pp) {
    pp.addEventListener("click", () => {
        document.body.style.animationName = "fadeEnd";
        document.body.style.animationDuration = "0.5s";
    });
};

let i = 0;

while (i <= 2) {
    fadeStart(pp[i]);
    i++;
}


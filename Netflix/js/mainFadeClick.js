let pp = document.querySelectorAll("#pp");

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


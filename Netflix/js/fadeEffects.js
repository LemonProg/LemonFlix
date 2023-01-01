let form = document.querySelectorAll(".streaming_form")

function fade(form) {
    form.addEventListener("submit", () => {
        document.body.style.animationName = "fade";
        document.body.style.animationDuration = "1s";
    });
};

let i = 0;

while (i <= 35) {
    fade(form[i]);
    i++;
}


let form = document.querySelectorAll(".streaming_form")

function fade(form) {
    form.addEventListener("submit", () => {
        document.body.style.animationName = "fade";
        document.body.style.animationDuration = "1s";
    });
};

fade(form[0]);
fade(form[1]);
fade(form[2]);
fade(form[3]);
fade(form[5]);
fade(form[6]);
fade(form[7]);
fade(form[8]);
fade(form[9]);
fade(form[10]);
fade(form[11]);

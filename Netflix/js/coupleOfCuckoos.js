let input = document.querySelector("#coupleCuckoos");
let popup = document.querySelector("#popup");
let parent = document.querySelector("#parent_streaming");
let closeBtn = document.querySelector("#close")

input.addEventListener("click", (ev) => {
    ev.preventDefault();

    parent.style.animationName = "fadePopUp";
    parent.style.animationDuration = "0.2s";
    parent.style.animationFillMode = "forwards";

    input.style.transform = "scale(1.2)";
    input.style.marginTop = "20px";
    input.style.marginLeft = "40px";
    input.style.marginRight = "40px";
    input.style.transitionDelay = "0s";

    popup.setAttribute("open", true);
});

closeBtn.addEventListener("click", () => {
    parent.style.animationName = "fadePopOut";
    parent.style.animationDuration = "0.2s";
    parent.style.animationFillMode = "forwards";

    input.style.transform = "scale(1)";
    input.style.marginTop = "0px";
    input.style.marginLeft = "0px";
    input.style.marginRight = "0px";

    popup.removeAttribute("open", true);
});
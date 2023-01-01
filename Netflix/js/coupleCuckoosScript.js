let input = document.querySelector("#coupleCuckoos");
let popup = document.querySelector("#popup");
let parent = document.querySelector("#parent_streaming");
let closeBtn = document.querySelector("#close")

input.addEventListener("click", (ev) => {
    ev.preventDefault();

    parent.style.animationName = "fadePopUp";
    parent.style.animationDuration = "0.2s";
    parent.style.animationFillMode = "forwards";

    document.body.style.overflowY = "hidden";

    popup.setAttribute("open", true);
});

closeBtn.addEventListener("click", () => {
    parent.style.animationName = "fadePopOut";
    parent.style.animationDuration = "0.2s";
    parent.style.animationFillMode = "forwards";

    document.body.style.overflowY = "auto";

    popup.removeAttribute("open", true);
});
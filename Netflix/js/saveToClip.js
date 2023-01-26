const clipBtn = document.querySelector("#saveBtn");
const clipVal = document.querySelector("#copyCode");

clipBtn.addEventListener("click", (ev) => {
    ev.preventDefault();

    navigator.clipboard.writeText(clipVal.textContent);

});


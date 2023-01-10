const searchForm = document.querySelector("#searchForm");
const search = document.querySelector("#searchBar");
const h1 = document.querySelector('h1');

searchForm.addEventListener("submit", (ev) => {
    ev.preventDefault();
});

let typingTimer;
let doneTypingInterval = 1000;

//on keyup, start the countdown
search.addEventListener('keyup', () => {
    h1.innerText = "Recherche : " + search.value;
    clearTimeout(typingTimer);
    if (search.value) {
        typingTimer = setTimeout(doneTyping, doneTypingInterval);
    }
});

//user is "finished typing," do something
function doneTyping () {
    searchForm.submit();
}
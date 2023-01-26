nbr_watched = 2;
p_watched = 0;
container_watched = document.querySelector(".watched_container");
section_watched = document.querySelector("#watched_section");
g_watched = document.getElementById("g_watched");
d_watched = document.getElementById("d_watched");

container_watched.style.width = (1680*nbr_watched) + "px";

function afficherMasquer() {
    if(p_watched==-nbr_watched+1) {
        d_watched.style.display = "none";
    } else {
        d_watched.style.display = "block";
    }

    if(p_watched==0) {
        g_watched.style.display = "none";
    } else {
        g_watched.style.display = "block";
    }

}

afficherMasquer();

g_watched.style.visibility = "hidden";
d_watched.style.visibility = "hidden";

section_watched.addEventListener("mouseover", () => {
    g_watched.style.visibility = "visible";
    d_watched.style.visibility = "visible";
});
section_watched.addEventListener("mouseout", () => {
    g_watched.style.visibility = "hidden";
    d_watched.style.visibility = "hidden";
});


d_watched.addEventListener("click", () => {
    if(p_watched>-nbr_watched+1) {
        p_watched--;
        container_watched.style.transform = "translate("+p_watched*1700+"px)";
        container_watched.style.transition = "all 0.5s ease";

        afficherMasquer();
    }
});


g_watched.addEventListener("click", () => {
    if(p_watched<0) {
        p_watched++;
        container_watched.style.transform = "translate("+p_watched*1680+"px)";
        container_watched.style.transition = "all 0.5s ease";

        afficherMasquer();
    }
});
nbr_watched = 3;
p_watched = 0;
container_watched = document.querySelector(".watched_container");
section_watched = document.querySelector("#watched_section");
g_watched = document.getElementById("g_watched");
d_watched = document.getElementById("d_watched");
last_watched = document.getElementById("lastOne_watched");
seconde_watched = document.getElementById("secondeOne_watched");

if (last_watched != null) {
    last_watched.style.opacity = "30%";

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
    
            last_watched.style.opacity = "100%";
            last_watched.style.transition = "all 0.2s ease";

            seconde_watched.style.opacity = "30%";
            seconde_watched.style.transition = "all 0.2s ease";
    
            afficherMasquer();
        }
    });
    
    
    g_watched.addEventListener("click", () => {
        if(p_watched<0) {
            p_watched++;
            container_watched.style.transform = "translate("+p_watched*1680+"px)";
            container_watched.style.transition = "all 0.5s ease";
    
            last_watched.style.opacity = "30%";
            last_watched.style.transition = "all 0.2s ease";

            seconde_watched.style.opacity = "100%";
            seconde_watched.style.transition = "all 0.2s ease";
    
            afficherMasquer();
        }
    });
} else {
    g_watched.style.visibility = "hidden";
    d_watched.style.visibility = "hidden";
}



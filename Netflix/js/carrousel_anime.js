document.body.onload=function() {
    nbr = 2;
    p = 0;
    container = document.querySelector(".anime_container");
    section = document.querySelector(".anime_section")
    g = document.getElementById("g");
    d = document.getElementById("d");
    last = document.getElementById("lastOne");
    seconde = document.getElementById("secondeOne");

    last.style.opacity = "30%";

    container.style.width = (1680*nbr) + "px";

    function afficherMasquer() {
        if(p==-nbr+1) {
            d.style.display = "none";
        } else {
            d.style.display = "block";
        }
    
        if(p==0) {
            g.style.display = "none";
        } else {
            g.style.display = "block";
        }
    
    }

    afficherMasquer();

    g.style.visibility = "hidden";
    d.style.visibility = "hidden";

    section.addEventListener("mouseover", () => {
        g.style.visibility = "visible";
        d.style.visibility = "visible";
    });
    section.addEventListener("mouseout", () => {
        g.style.visibility = "hidden";
        d.style.visibility = "hidden";
    });


    d.addEventListener("click", () => {
        if(p>-nbr+1) {
            p--;
            container.style.transform = "translate("+p*1700+"px)";
            container.style.transition = "all 0.5s ease";
            last.style.opacity = "100%";
            last.style.transition = "all 0.2s ease";
            seconde.style.opacity = "30%";
            seconde.style.transition = "all 0.2s ease";

            afficherMasquer();
        }
    });

    
    g.addEventListener("click", () => {
        if(p<0) {
            p++;
            container.style.transform = "translate("+p*1680+"px)";
            container.style.transition = "all 0.5s ease";
            last.style.opacity = "30%";
            last.style.transition = "all 0.2s ease";
            seconde.style.opacity = "100%";
            seconde.style.transition = "all 0.2s ease";

            afficherMasquer();
        }
    });

};
function iniciar() {
    medio = document.getElementById('video201749113059');
    reproducir = document.getElementById('reproducir');
    reproducir.addEventListener('click', presionar, false);
    
}

function presionar() {
    if (!medio.paused && !medio.ended) {
        medio.pause();
        
        window.clearInterval(bucle);
    } else {
        medio.webkitRequestFullScreen();
        medio.play();
        
        
    }
}

function estado() {
    if (!medio.ended) {
       
    } else {
        medio.webkitRequestFullScreen();
        reproducir.innerHTML = '>';
        window.clearInterval(bucle);
    }
}


window.addEventListener('load', iniciar, false);
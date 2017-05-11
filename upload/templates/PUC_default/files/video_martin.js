jQuery(document).ready(function($) {

    $('video').removeAttr("controls"); //rebuebo controls

    if ($('.2-col').length > 0) { //agrego dimencion para 2 columnas
        $('.estimulo').addClass("col-md-6");
        $('.pregunta').addClass("col-md-6");

    }


    $('video').click(function() {
        var isPlaying = this.currentTime > 0 && !this.paused && !this.ended && this.readyState > 2;

        if (!isPlaying) {

            this.play();

            if (this.requestFullscreen) {
                this.requestFullscreen();

            } else if (this.mozRequestFullScreen) {
                this.mozRequestFullScreen();

            } else if (this.webkitRequestFullscreen) {
                this.webkitRequestFullscreen();

            }
            this.play();
            if (this.onplaying) {
                this.setAttribute('controls', '');

            }


        } else {
            this.pause();
        }

    });
});;
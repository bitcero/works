(function($){

    function createPlayer(url, type){

        if(type == 'iframe'){
            return url;
        }

        if(url.indexOf("youtube.com") != -1){
            var videoId = url.match(/^.*[?&]v=([\w-]{11})/);
            if(videoId && undefined != videoId[1]){
                return '//www.youtube.com/embed/' + videoId[1] + '?rel=0&autoplay=1';
            }
            return url;
        }

        if(url.indexOf("youtu.be") != -1){
            var videoId = url.match(/^http.*youtu\.be\/([a-zA-Z\d]+)$/);
            if(videoId && undefined != videoId[1]){
                return '//www.youtube.com/embed/' + videoId[1] + '?rel=0&autoplay=1';
            }
            return url;
        }

        if(url.indexOf('vimeo.com') != -1){
            var videoId = url.match(/.*.\/([0-9]{3,}).*/);
            if(videoId && undefined != videoId[1]){
                return '//player.vimeo.com/video/' + videoId[1] + '?autoplay=1';
            }
            return url;
        }

        if(url.indexOf('dailymotion.com/video') != -1){
            var videoId = url.match(/^.*dailymotion\.com\/video\/([a-zA-Z0-9]+)/);
            if(videoId && undefined != videoId[1]){
                return '//www.dailymotion.com/embed/video/' + videoId[1] + '?autoplay=1';
            }
            return url;
        }

        if(url.indexOf('//www.dailymotion.com/embed/video/') != -1){
            return url;
        }

        return url;

    }

    $(".work-images .video-item").click(function(){

        var url = createPlayer($(this).attr('href'), $(this).data('type'));

        var $content = '<div class="works-video-player"><div class="embed-responsive embed-responsive-16by9">' +
            '<iframe class="embed-responsive-item" src="' + url + '" allowfullscreen></iframe>' +
            '</div></div>';

        $.colorbox({html: $content});
        return false;

    });

}(jQuery));
/*!
 * More info at [www.rmcommon.com](http://www.rmcommon.com)
 *
 * Author:  Eduardo Cortés
 * URI:     http://eduardocortes.mx
 * Parte del proyecto "Professional Works"
 *
 * Copyright (c) 2016, Eduardo Cortés Hervis
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

(function($){

    var to_focus;
    var $instance;

    this.WorksForm = function(){
        this.identifier = 'Professional Works Project Form';

        $instance = this;
    }

    WorksForm.prototype.init = function(){

        // Set date time picker
        $("#work-schedule").datetimepicker({
            format: 'Y-m-d H:i'
        });

        // Status change
        $("#work-status").change( function(){

            switch ( $("#work-status").val() ){
                case 'draft':
                    $("#work-submit-forms").html( workLang.savedraft );
                    break;
                case 'public':
                    $("#work-submit-forms").html( workLang.savepublish );
                    break;
                case 'private':
                    $("#work-submit-forms").html( workLang.saveprivate );
                    break;
                case 'scheduled':
                    $("#work-submit-forms").html( workLang.saveschedule );
                    break;

            }

            if ( $(this).val() == 'private' ){

                $("#visibility-groups").slideDown( 250 );
                $("#visibility-schedule").slideUp( 250 );
                return;

            }

            if ( $(this).val() == 'scheduled' ){

                $("#visibility-groups").slideUp( 250 );
                $("#visibility-schedule").slideDown( 250, function(){

                } );
                return;

            }

            $("#visibility-groups").slideUp( 250 );
            $("#visibility-schedule").slideUp( 250 );


        } );

        // Thumbnails control
        $("body").on('click', '#work-images-container > span > a', function(){

            var id = $(this).parent().data("id");
            $("#image-" + id).remove();
            $(this).parent().fadeOut(250, function(){$(this).remove();});
            return false;

        } );

        // Custom fields
        $("a#add-field-name").click(function(){
            if ($(this).text()=='Add New'){
                $("#dmeta-sel").hide();
                $("#dmeta").show();
                $(this).text("Cancel");
            } else {
                $("#dmeta-sel").show();
                $("#dmeta").hide();
                $(this).text("Add New");
            }
        });

        $("#add-field").click(function(){
            if ($("#dvalue").val()=='') return;

            if ($("#dmeta-sel").is(":visible")){
                name = $("#dmeta-sel").val();
            } else if($("#dmeta-sel").length>0) {
                name = $("#dmeta").val();
                $("#dmeta-sel").show();
                $("#dmeta").hide();
                $("#dmeta").val('');
                $(this).text("Add New");
            } else {
                name = $("#dmeta").val();
                $("#dmeta").val('');
                $(this).text("Add New");
            }

            var field = '<div class="row">' +
                '<div class="col-sm-4">' +
                '<div class="form-group">' +
                '<input type="text" class="form-control" name="meta_name[]" value="'+name+'" /><br>' +
                '<button type="button" class="btn btn-warning btn-sm delete-meta">Delete</button>' +
                '</div>' +
                '</div>';
            field += '<div class="col-sm-8">' +
                '<div class="form-group">' +
                '<textarea class="form-control" name="meta_value[]">'+$("#dvalue").val()+'</textarea>' +
                '</div>' +
                '</div>' +
                '</div>';
            $("#dvalue").val('');
            $("#existing-meta > .row:first-child").after(field);

        });

        $("#existing-meta").on("click", ".delete-meta", function(){
            $(this).parent().parent().parent().remove();

        });

        $("#add-meta-button").click(function(){

            if ($(this).html()==workLang.addnew){
                $("#dmeta-sel").hide();
                $("#dmeta").show();
                $(this).html(workLang.cancel);
            } else {
                $("#dmeta-sel").show();
                $("#dmeta").hide();
                $(this).html(workLang.addnew);
            }

            return false;
        });

        // Submit project
        $("#work-submit-forms").click( function(){

            var blocker = '<div id="work-blocker"></div><div id="work-blocker-info"><span>'+workLang.check+'</span><button type="button" class="btn btn-info">'+workLang.cancel+'</button>';
            blocker += '<button class="btn btn-danger">'+workLang.ok+'</button></div>';
            $("body").append(blocker);

            $("#work-blocker").fadeIn('fast', function(){
                $("#work-blocker-info").fadeIn('fast');

                $instance.verifyFields();

            });

            $("#work-blocker-info button").click(function(){
                $("#work-blocker-info").fadeOut('fast', function(){
                    $("#work-blocker").fadeOut('fast', function(){
                        $("#work-blocker-info").remove();
                        $("#work-blocker").remove();
                        $(to_focus).focus();
                    });

                });

            });

        } );

        $("#project-videos input[data-id='video-url']").on('input change', function(){
            $instance.showVideoControl();
        });

        $("#project-videos button[data-trigger='add-video']").click(function(){
            $instance.addVideo();
        });

        $('body').on('click', "#project-videos .edit-video", (function(){
            $instance.editVideo($(this));
            return false;
        });

        $('body').on("click", '#project-videos .delete-video', function(){
            $instance.deleteVideo($(this));
            return false;
        });

    };

    /**
     * Deletes a specific video and its data
     * @param item
     */
    WorksForm.prototype.deleteVideo = function(item){

        if(undefined == item){
            return false;
        }

        var id = $(item).parents('li').data('id');
        if(id <= 0){
            return false;
        }

        if(!confirm(workLang.confirmDelete)){
            return false;
        }

        $(item).cuSpinner({icon: 'svg-rmcommon-spinner-02'});

        var params = {
            CUTOKEN_REQUEST: $("#cu-token").val(),
            action: 'delete-video',
            work: $("#work-id").val(),
            id: id
        };

        $.post('works.php', params, function(response){
            $(item).cuSpinner();

            if(!cuHandler.retrieveAjax(response)){
                return false;
            }

            var container = $('#project-videos .work-videos-container > ul');

            $(container).find('li[data-id="' + response.id + '"]').fadeOut(300, function(){
                $(this).remove();
            });

        }, 'json');

    };

    /**
     * Show video data for edition
     * @param item
     */
    WorksForm.prototype.editVideo = function(item){

        if(undefined == item){
            return false;
        }

        var id = $(item).parents('li').data('id');
        if(id <= 0){
            return false;
        }

        $(item).cuSpinner({icon: 'svg-rmcommon-spinner-02'});

        var params = {
            CUTOKEN_REQUEST: $("#cu-token").val(),
            action: 'edit-video',
            work: $("#work-id").val(),
            id: id
        };

        $.get('works.php', params, function(response){

            $(item).cuSpinner();

            if(!cuHandler.retrieveAjax(response)){
                return false;
            }

            var container = $('#project-videos .video-controls');
            $(container).find('input[data-id="video-title"]').val(response.video.title);
            $(container).find('input[data-id="video-description"]').val(response.video.description);
            $(container).find('input[data-id="video-image"]').val(response.video.image).blur();
            $(container).find('input[data-id="video-url"]').val(response.video.url);
            $(container).find('input[data-id="video-id"]').val(response.video.id);
            $(container).find('button[data-trigger="add-video"]').html(workLang.update);

            $instance.showVideoControl();


        }, 'json');

    };

    /**
     * Get all data from video controls
     */
    WorksForm.prototype.parseVideoData = function(){

        var data = {
            url : $("#project-videos input[data-id='video-url']").val(),
            title : $("#project-videos input[data-id='video-title']").val(),
            description : $("#project-videos input[data-id='video-description']").val(),
            image : $("#project-videos input[data-id='video-image']").val(),
            work : $("#work-id").val(),
            id : $("#project-videos input[data-id='video-id']").val()
        };

        return data;

    };

    WorksForm.prototype.resetVideoControls = function(){
        $("#project-videos input[data-id='video-url']").val('');
        $("#project-videos input[data-id='video-title']").val('');
        $("#project-videos input[data-id='video-description']").val('');
        $("#project-videos input[data-id='video-image']").val('');
        $("#project-videos input[data-id='video-id']").val('');
        $("#project-videos button[data-trigger='add-video']").html(workLang.addVideo);
        this.hideVideoControls();
    };

    /**
     * Show control for videos
     */
    WorksForm.prototype.showVideoControl = function(){

        var url = $("#project-videos input[data-id='video-url']").val();

        if(url.length <= 8){
            $instance.resetVideoControls();
            $instance.hideVideoControls();
            return false;
        }

        if(false == this.validURL(url)){
            return false;
        }

        $("#project-videos .video-controls .hidden-controls").slideDown(250);

    };

    /**
     * Hide hidden video controls
     */
    WorksForm.prototype.hideVideoControls = function(){

        if($("#project-videos .video-controls .hidden-controls").is(':visible')){
            $("#project-videos .video-controls .hidden-controls").slideUp(250);
        }
    };

    /**
     * Verify if provided URL value is valid
     */
    WorksForm.prototype.validURL = function(url){

        if(undefined == url || '' == url){
            return false;
        }

        var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.?)+[a-z]{2,}|'+ // domain name
            '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
            '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
            '(\\#[-a-z\\d_]*)?$','i'); // fragment locator

        // Check if is URL
        if(pattern.test(url)){
            return 'url';
        }

        // Check if is Iframe
        if(url.substring(0, 8) == '<iframe '){
            return 'iframe';
        }

        // Check if is object
        if(url.substring(0, xoUrl.length - 1) == xoUrl){
            return 'local';
        }

        return false;

    };

    /**
     * Get parameters from video player
     * @param url
     */
    WorksForm.prototype.parseVideoPlayer = function(url, type){

        var baseData = {
            type : type,
            url: '',
            fullScreen: true
        };

        if('url' == type || 'local' == type){
            baseData.url = url;
            baseData.fullScreen = true;
            return baseData;
        }

        var object = url.match(/<iframe.+?<\/iframe>/g);

        baseData.url = $(object[0]).attr('src');
        baseData.fullScreen = 1;
        return baseData;

    };

    /**
     * Perform action to add a new video to project
     * @returns {boolean}
     */
    WorksForm.prototype.addVideo = function(){

        var data = this.parseVideoData();
        // Check if video type is valid
        var type = this.validURL(data.url);

        if(false == type){
            //$("#project-videos button[data-trigger='add-video']").cuSpinner();
            this.showNotification(workLang.noVideoUrl, 'error');
            return false;
        }

        if(undefined == data.title || '' == data.title){
            //$("#project-videos button[data-trigger='add-video']").cuSpinner();
            this.showNotification(workLang.noVideoTitle, 'error');
            return false;
        }

        $("#project-videos button[data-trigger='add-video']").cuSpinner({icon: 'svg-rmcommon-spinner-14'});
        var player = this.parseVideoPlayer(data.url, type);

        var params = {
            CUTOKEN_REQUEST: $("#cu-token").val(),
            action: data.id > 0 ? 'update-video' : 'add-video',
            url: player.url,
            title: data.title,
            description: data.description,
            image: data.image,
            id: data.work,
            type: type,
            fullScreen: player.fullScreen,
            video: data.id
        };

        $.post('works.php', params, function(response){

            $("#project-videos button[data-trigger='add-video']").cuSpinner();

            if(!cuHandler.retrieveAjax(response)){
                return false;
            }

            var item = $("<a />");
            $(item)
                .attr('title', response.video.title)
                .attr('href', response.video.url)
                .attr('target', '_blank')
                .css('background-image', 'url(' + response.video.image + ')');

            var icon = $("<span />");
            cuHandler.loadIcon('svg-rmcommon-video', icon);

            $(item).append(icon);

            var listItem = $('<li data-id="' + response.video.id + '" />');
            $(listItem)
                .css('display', 'none')
                .append(item);

            var controls = $("<div />");
            $(controls)
                .addClass('controls')
                .append('<a href="#" class="edit-video">' + workLang.edit + '</a>')
                .append('<a href="#" class="delete-video">' + workLang.delete + '</a>');

            $(listItem).append(controls);

            // Remove previous video conteol if exists
            if($("#project-videos .work-videos-container ul > li[data-id='"+ response.video.id +"']").length > 0){
                $("#project-videos .work-videos-container ul > li[data-id='"+ response.video.id +"']").remove();
            }

            $("#project-videos .work-videos-container ul").append(listItem);
            $(listItem).fadeIn(300);

            $instance.resetVideoControls();


        }, 'json');

    };

    WorksForm.prototype.showNotification = function(text, type){
        type = undefined == type || '' == type ? 'info' : type;
        var icon = '';
        var alert = '';

        switch(type){
            case 'error':
                icon = 'svg-rmcommon-error';
                alert = 'alert-danger';
                break;
            case 'success':
                icon = 'svg-rmcommon-ok';
                alert = 'alert-success';
                break;
            case 'info':
            default:
                icon = 'svg-rmcommon-info-solid';
                alert = 'alert-info';
                break;
        };

        cuHandler.notify({
            type: alert,
            icon: icon,
            text: text
        });
    };

    /**
     * Verify all fields content
     */
    WorksForm.prototype.verifyFields = function(){

        if($("#work-title").val()==''){
            $("#work-blocker-info span").html(workLang.notitle).addClass('work-error');
            $("#work-blocker-info button:last-child").fadeIn('fast');
            $("#work-blocker-info button.btn-info").fadeOut('fast');
            to_focus = $("#work-title");
            return;
        }

        // Check status
        if ( $("#work-status").val() == 'private' ){
            if ( $("#visibility-groups input:checked").length <= 0 ){
                $("#work-blocker-info span").html(workLang.nogroup).addClass('work-error');
                $("#work-blocker-info button:last-child").fadeIn('fast');
                $("#work-blocker-info button.btn-info").fadeOut('fast');
                to_focus = $("#work-status");
                return;
            }

        }

        if( $("#work-status").val() == 'scheduled' && $("#work-schedule").val() == '' ){

            $("#work-blocker-info span").html(workLang.notime).addClass('work-error');
            $("#work-blocker-info button:last-child").fadeIn('fast');
            $("#work-blocker-info button.btn-info").fadeOut('fast');
            to_focus = $("#work-schedule");
            return;

        }

        if ( $("#work-categories input:checked").length <= 0 ){

            $("#work-blocker-info span").html(workLang.nocategory).addClass('work-error');
            $("#work-blocker-info button:last-child").fadeIn('fast');
            $("#work-blocker-info button.btn-info").fadeOut('fast');
            to_focus = $("#work-categories .checkbox:first-child input");
            return;

        }

        if ( 'undefined' !== typeof ( tinymce ) )
            tinymce.activeEditor.save();
        else if ( 'undefined' !== typeof ( mdEditor ) )
            mdEditor.save('description');

        var params = '';
        $("form").each( function() {
            params += params=='' ? $(this).serialize() : '&' + $(this).serialize();
        });

        $("#work-blocker-info span").html(workLang.saving).removeClass('work-error');

        $.post("works.php", params, function(response){

            if(response.error>0){
                $instance.show(response.message, 1);
                if(response.token!='')
                    $("#XOOPS_TOKEN_REQUEST").val(response.token);
                else
                    window.location.href = window.location.href;

                return false;
            }

            $instance.showMessage(response.message, 0);
            $("#work-blocker-info").addClass('blocker-success');
            $("#work-blocker-info button:last-child").html(workLang.done);
            if(response.token!='')
                $("#XOOPS_TOKEN_REQUEST").val(response.token);

            //$("#work-permalink span").html(response.data.permalink);

            /*if($("#work-custom-url").length>0){
             $("#work-custom-url input").val(response.data.custom_url);
             }*/

            if(response.data.redirect!=undefined)
                window.location.href = response.data.redirect;

            if ( $("#works-action").val() == 'save' )
                window.location.href = 'works.php?action=edit&amp;id=' + response.data.id;

            $("#work-title-id").val( response.data.title_id );

        }, 'json');

    };

    /**
     * Show blocker message
     * @param msg
     * @param error
     */
    WorksForm.prototype.showMessage = function(msg, error){
        if(error>0){
            $("#work-blocker-info span").html(msg).addClass('work-error');
            $("#work-blocker-info button:last-child").fadeIn('fast');
            $("#work-blocker-info button.btn-info").fadeOut('fast');
        } else {
            $("#work-blocker-info span").html(msg).removeClass('work-error');
            $("#work-blocker-info button:last-child").fadeIn('fast').removeClass("btn-danger").addClass("btn-success");
            $("#work-blocker-info button.btn-info").fadeOut('fast');
        }

        setTimeout(function(){
            $instance.closeMessage();
        }, 15000);
    };

    /**
     * Hide blocker message
     */
    WorksForm.prototype.closeMessage = function(){
        $("#work-blocker-info").fadeOut('fast', function(){
            $("#work-blocker").fadeOut('fast', function(){
                $("#work-blocker-info").remove();
                $("#work-blocker").remove();
            });

        });
    };

    // Initializer
    $(document).ready(function(){
        var worksForm = new WorksForm();
        worksForm.init();
    })

}(jQuery));


function work_add_images( data, container ){

    var i = 0;
    var container = $("#work-images-container");
    var html = '';

    if ( data.length == undefined )
        data = [data];

    for (i = 0; i < data.length; i++ ){

        html = '<span data-id="'+data[i].id+'" style="background-image: url('+data[i].thumbnail+');"><a href="#"><span class="fa fa-times"></span></a></span>';
        html += '<input type="hidden" name="images[]" id="image-'+data[i].id+'" value="' + data[i].url + '|' + data[i].title +'">';
        container.append(html);

    }

}

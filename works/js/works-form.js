/*
--------------------------------------------------------------
Professional Works
Advanced Portfolio System
Author: BitC3R0 <i.bitcero@gmail.com>
Email: i.bitcero@gmail.com
License: GPL 2.0
--------------------------------------------------------------
*/

var to_focus;

$(document).ready(function(){

    $("#work-schedule").datetimepicker({
        format: 'Y-m-d H:i'
    });

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

    $("#add-images").click( function(){

    } );

    $("body").on('click', '#work-images-container > span > a', function(){

        var id = $(this).parent().data("id");
        $("#image-" + id).remove();
        $(this).parent().fadeOut(250, function(){$(this).remove();});
        return false;

    } );

    /**
     * Custom data
     */
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

    /**
     * Send all data
     */
    $("#work-submit-forms").click( function(){

        var blocker = '<div id="work-blocker"></div><div id="work-blocker-info"><span>'+workLang.check+'</span><button type="button" class="btn btn-info">'+workLang.cancel+'</button>';
        blocker += '<button class="btn btn-danger">'+workLang.ok+'</button></div>';
        $("body").append(blocker);

        $("#work-blocker").fadeIn('fast', function(){
            $("#work-blocker-info").fadeIn('fast');

            works_verify_fields();

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

});

function works_verify_fields(){

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
            works_show_message(response.message, 1);
            if(response.token!='')
                $("#XOOPS_TOKEN_REQUEST").val(response.token);
            else
                window.location.href = window.location.href;

            return false;
        }

        works_show_message(response.message, 0);
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

        alert( response.work.id );

        if ( $("#works-action").val() == 'save' )
            window.location.href = 'works.php?action=edit&amp;id=' + response.work.id;

        $("#work-title-id").val( response.work.title_id );

    }, 'json');

}


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

function works_show_message(msg, error){

    if(error>0){
        $("#work-blocker-info span").html(msg).addClass('work-error');
        $("#work-blocker-info button:last-child").fadeIn('fast');
        $("#work-blocker-info button.btn-info").fadeOut('fast');
    } else {
        $("#work-blocker-info span").html(msg).removeClass('work-error');
        $("#work-blocker-info button:last-child").fadeIn('fast').removeClass("btn-danger").addClass("btn-success");
        $("#work-blocker-info button.btn-info").fadeOut('fast');
    }

    setTimeout('work_close_msg()', 15000);

}

function work_close_msg(){
    $("#work-blocker-info").fadeOut('fast', function(){
        $("#work-blocker").fadeOut('fast', function(){
            $("#work-blocker-info").remove();
            $("#work-blocker").remove();
        });

    });
}
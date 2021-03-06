/*
 * LimeSurvey
 * Copyright (C) 2007 The LimeSurvey Project Team / Carsten Schmitz
 * All rights reserved.
 * License: GNU/GPL License v2 or later, see LICENSE.php
 * LimeSurvey is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 *
 *
 * Description: Javascript file for templates. Put JS-functions for your template here.
 *
 *
 * $Id:$
 */


/*
 * The function focusFirst puts the Focus on the first non-hidden element in the Survey.
 *
 * Normally this is the first input field (the first answer).
 */
function focusFirst(Event)
{

    $('#limesurvey :input:visible:enabled:first').focus();

}
/*
 * The focusFirst function is added to the eventlistener, when the page is loaded.
 *
 * This can be used to start other functions on pageload as well. Just put it inside the 'ready' function block
 */

/* Uncomment below if you want to use the focusFirst function */
/*
$(document).ready(function(){
    focusFirst();
});
*/


$(document).ready(function()
{
 //-------------------------------------------------------------------------------------------------------------
    //--------------------------------Cambios al registro----------------------------------------------------------
    //-------------------------------------------------------------------------------------------------------------
   
   $("#register_attribute_1").parent().parent().remove();
    $(".register-form .container #limesurvey").prepend('<div id="frut" class="form-group col-sm-12"></div>');
    $("#frut").append('<label for="register_attribute_1" class="control-label col-md-4"><span class="text-danger asterisk"></span>Rut:</label>');
    $("#frut").append('<div class="col-sm-12 col-md-6"><input id="register_attribute_1" class="form-control input-sm" type="text" value="" name="register_attribute_1"></div>');
    if ($("#frut").length) {
        var token = localStorage.getItem("puc_rut");
        $("#register_attribute_1").val(token);
    }

    function llenarselect_c(datos, elemento) {
        $(elemento).empty();
        $(elemento).append('<option value="" disabled selected>selecione...</option>');
        $.each(datos, function(i, item) {
            $(elemento).append('<option value="' + item + '">' + item + '</option>');
        });
    }

    function llenarselect_e(datos, elemento1) {
        $(elemento1).empty();
        $(elemento1).append('<option value="" disabled selected>selecione....</option>');
        $.each(datos, function(i, item) {
            $(elemento1).append('<option value="' + item.valor + '">' + item.nombre + '</option>');
        });
    }


    $("#register_attribute_1").rut();
    $("#register_attribute_2").parent('div').html('<input id="register_attribute_2" type="radio" name="register_attribute_2" value="Municipal">Municipal<br><input id="register_attribute_2" type="radio" name="register_attribute_2" value="Particular subencionado">Particular subencionado<br>');
    $("#register_attribute_4").parent('div').html('<input id="register_attribute_4" type="radio" name="register_attribute_4" value="Femenino">Femenino<br><input id="register_attribute_4" type="radio" name="register_attribute_4" value="Masculino">Masculino<br>');
    $("#register_attribute_3").replaceWith('<select id="register_attribute_3" name ="register_attribute_3"></select>');

    var attr5_nivel = $("#register_attribute_5");
    var paretDivAttr5 = $("#register_attribute_5").parent('div');
    $("#register_attribute_5").remove();

    var checkNivel = '<input id="opt1" type="checkbox" name ="opt1" value="1-Ed. parvularia">Ed. parvularia<br>';
    checkNivel += '<input id="opt2" type="checkbox" name ="opt2" value="2-1 a 6">1 a 6 <br>';
    checkNivel += '<input id="opt3" type="checkbox" name ="opt3" value="3-7 a IV° medio">7 a IV° medio<br>';
    checkNivel += attr5_nivel.get(0).outerHTML;
    paretDivAttr5.html(checkNivel);
    $("#register_attribute_5").hide();







    //$("#register_attribute_5").parent('div').append('<input id="register_attribute_5" type="hidden" name ="register_attribute_5" >');

    //$("#register_attribute_5").replaceWith('<select id="register_attribute_5" name ="register_attribute_5"></select>');
    // validacion del submit 
    $("#limesurvey").submit(function(event) {

        var $da = $("#register_attribute_1").val();
        //var $c1= $("#register_attribute_5").val();
        var tmpstr = "";
        for (i = 0; i < $da.length; i++)
            if ($da.charAt(i) != ' ' && $da.charAt(i) != '.' && $da.charAt(i) != '-')
                tmpstr = tmpstr + $da.charAt(i);


        var texto = tmpstr;
        var $de = $.validateRut(texto);

        if ($de === false) {
            alert("El rut " + $da + " es invalido ");
            event.preventDefault();
        }
        if ($("#opt1").prop("checked") == true && $("#opt3").prop("checked") == true && $("#opt2").prop("checked") == true) {

            //las tres opciones escojidas 
            $("#register_attribute_5").val($("#opt1").val() + " ; " + $("#opt2").val() + " ; " + $("#opt3").val());

        } else if ($("#opt2").prop("checked") == true) {
            if ($("#opt1").prop("checked") == true) { //opcion 1 y 2    
                $("#register_attribute_5").val($("#opt1").val() + " ; " + $("#opt2").val());
            } else if ($("#opt3").prop("checked") == true) { //opcion 2 y 3           
                $("#register_attribute_5").val($("#opt2").val() + " ; " + $("#opt3").val());
            } else { //solo opcion 2
                $("#register_attribute_5").val($("#opt2").val());
            }
        } else {
            event.preventDefault();
            window.location.replace("http://localhost/limesurvey/index.php"); //nesecita cambiar la dirrecion 
        }
    });


    $.ajax({
        url: "/limesurvey/ws/info_c.php", //nesecita cambiar la dirrecion 
        type: "GET",
        success: function(result) {
            // result=JSON.parse(result);
            llenarselect_e(result.re.region, "#register_attribute_3");
        }

    });


    //--------------------------------------------------------------------------------------------------------
    //-------------------------------------Fin de las modificacion del registro-------------------------------
    //--------------------------------------------------------------------------------------------------------
    // Scroll to first error
    if($(".input-error").length > 0) {
        $('#bootstrap-alert-box-modal').on('hidden.bs.modal', function () {
            console.log('answer error found');
            $firstError = $(".input-error").first();
            $pixToScroll = ( $firstError.offset().top - 100 );
            $('html, body').animate({
                 scrollTop: $pixToScroll + 'px'
             }, 'fast');
        });
    }


    // Make the label clickable
    $('.label-clickable').each(function(){
        var $that    = $(this);
        var attrId = $that.attr('id');
        if(attrId!=undefined){
            attrId = attrId.replace("label-", "");
        } else {
            attrId = "";
        }
        var $inputEl = $("#"+attrId);
        $that.on('click', function(){
            console.log($inputEl.attr('id'));
            $inputEl.trigger( "click" );
        });
    });

    $('.if-no-js').hide();

    if($(window).width() < 768 )
    {
        // nothing
    }

    //var outerframeDistanceFromTop = 50;
    //topsurveymenubar
    var topsurveymenubarHeight = $('#topsurveymenubar').innerHeight();
    var outerframeDistanceFromTop = topsurveymenubarHeight;
    // Manage top container
    if(!$.trim($('#topContainer .container').html()))
    {
        $('#topContainer').hide();
    }
    else
    {
        $('#topContainer').css({
            top: topsurveymenubarHeight+'px',
        });

        $topContainerHeight = $('#topContainer').height();
        outerframeDistanceFromTop += $topContainerHeight;
    }

    if(!$.trim($('#surveynametitle').html()))
    {
        if(!$.trim($('#surveydescription').html()))
        {
            $('#survey-header').hide();
        }
    }

    $('#outerframeContainer').css({marginTop:outerframeDistanceFromTop+'px'});

    $('.language-changer').each(function(){
        $that = $(this);
        if(!$.trim($that.children('div').html()))
        {
            $that.hide();
        }
    });

    $('.group-description-container').each(function(){
        $that = $(this);
        if(!$.trim($that.children('div').html()))
        {
            $that.hide();
        }
    });

    // Hide question help container if empty
    $('.questionhelp').each(function(){
        $that = $(this);
        if(!$.trim($that.html()))
        {
            $that.hide();
        }
    });


    // Load survey button
    if ($('#loadallbtnlink').length > 0){
        $('#loadallbtnlink').on('click', function()
        {
            $('#loadallbtn').trigger('click');
        });
    }

    // Save survey button
    if ($('#saveallbtnlink').length > 0){
        $('#saveallbtnlink').on('click', function()
        {
            $('#saveallbtn').trigger('click');
        });
    }

    // clearall
    if ($('#clearallbtnlink').length > 0){
        $('#clearallbtnlink').on('click', function()
        {
            $('#clearall').trigger('click');
        });
    }

    // Question index
    if($('.linkToButton').length > 0){
        $('.linkToButton').on('click', function()
        {
            $btnToClick = $($(this).attr('data-button-to-click'));
            $btnToClick.trigger('click');
            return false;
        });
    }


    // Errors
    if($('.emtip').length>0)
    {
        // On Document Load
        $('.emtip').each(function(){
            if($(this).hasClass('error'))
            {
                $(this).parents('div.questionhelp').removeClass('text-info').addClass('text-danger');
            }
        });

        // On em change
        $('.emtip').each(function(){
            $(this).on('classChangeError', function() {
                $parent = $(this).parent('div.questionhelp');
                $parent.removeClass('text-info',1);
                $parent.addClass('text-danger',1);

                if ($parent.hasClass('hide-tip'))
                {
                    $parent.removeClass('hide-tip',1);
                    $parent.addClass('tip-was-hidden',1);
                }

                $questionContainer = $(this).parents('div.question-container');
                $questionContainer.addClass('input-error');
            });

            $(this).on('classChangeGood', function() {
                $parent = $(this).parents('div.questionhelp');
                $parent.removeClass('text-danger');
                $parent.addClass('text-info');
                if ($parent.hasClass('tip-was-hidden'))
                {
                    $parent.removeClass('tip-was-hidden').addClass('hide-tip');
                }
                $questionContainer = $(this).parents('div.question-container');
                $questionContainer.removeClass('input-error');
            });
        });
    }

    // Hide the menu buttons at the end of the Survey
    if($(".hidemenubutton").length>0)
    {
        $('.navbar-right').hide();
    }

    // Survey list footer
    if($('#surveyListFooter').length>0)
    {
        $surveyListFooter = $('#surveyListFooter');
        $('#outerframeContainer').after($surveyListFooter);
    }

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
    //martin video------------------------------------------------------------------------------------------------
    $('video').removeAttr("controls"); //remuevo controls
    $('video').attr('controlslist', 'nodownload');//incorporo attr. para eliminar boton de descarga  
    if ($('.2-col').length > 0) { //agrego dimencion para 2 columnas
        $('.estimulo').addClass("col-md-6");
        $('.pregunta').addClass("col-md-6");

    }


    $('video').click(function() {
       var isPlaying = this.currentTime > 0 && !this.paused && !this.ended && this.readyState > 2;//evaluamos estadodel video 

        if (!isPlaying) {//si lo anterior es falso

            this.play();//iniciamos reproduccion

            if (this.requestFullscreen) {//fullscreen para explorer
                this.requestFullscreen();

            } else if (this.mozRequestFullScreen) {//fullscreen para firefox - opera
                this.mozRequestFullScreen();

            } else if (this.webkitRequestFullscreen) {//fullscreen para crome
                this.webkitRequestFullscreen();

            }
            
           
            this.setAttribute('controls', '');//incorporamos controles 

            


        } 

    });
    
//martin video------------------------------------------------------------------------------------------------
       
});


window.alert = function(message, title) {
    if($("#bootstrap-alert-box-modal").length == 0) {
        $("body").append('<div id="bootstrap-alert-box-modal" class="modal fade">\
            <div class="modal-dialog">\
                <div class="modal-content">\
                    <div class="modal-header" style="min-height:40px;">\
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>\
                        <h4 class="modal-title"></h4>\
                    </div>\
                    <div class="modal-body"><p></p></div>\
                    <div class="modal-footer">\
                        <a href="#" data-dismiss="modal" class="btn btn-default">Close</a>\
                    </div>\
                </div>\
            </div>\
        </div>');
    }
    $("#bootstrap-alert-box-modal .modal-header h4").text(title || "");
    $("#bootstrap-alert-box-modal .modal-body p").text(message || "");

    $(document).ready(function()
    {
        $("#bootstrap-alert-box-modal").modal('show');
    });
};

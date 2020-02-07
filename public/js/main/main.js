/**
 * All common functions, which are needed anywhere anytime and within this plugin is defined here.
 */

var ajaxURL = document.getElementById("ajax-url").value;
function resetForm(){
    jQuery("form.ui.form")[0].reset();
    jQuery('.aj-link input').val("");
    jQuery(".aj-link form button").attr("disabled",true);
    jQuery(".process-action").removeClass("loading");
    jQuery("select").dropdown('clear');
}
function printForm(elementId) {
    var prtContent = document.getElementById(elementId);
    var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
    var cssURL = document.getElementById("css-url").val;


    WinPrint.document.write(prtContent.innerHTML);


    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();
    WinPrint.close();
}
function reponseMessage(res,selector){

    if(res.response == true && typeof res.message != "undefined"){
        jQuery(selector).removeClass("error");
        jQuery(selector).addClass("success");
        jQuery(selector+" .header").text("Completed");
        jQuery(selector+" .response").text(res.message);
        jQuery(selector).removeClass("dont-show transition hidden");
    }else{
        jQuery(selector).removeClass("success");
        jQuery(selector).addClass("error");
        jQuery(selector+" .header").text("Failed");
        jQuery(selector+" .response").text(res.message);
        jQuery(selector).removeClass("dont-show transition hidden");
    }

    if(selector.length){

        // setTimeout(function(){ jQuery(selector+' .close').trigger('click'); }, 5000);
    }

}

function manageLoader(show = false, className = ""){
    if(show && className !== ""){
        jQuery(className).addClass('active').removeClass('dont-show');

    }else{
        if(className !== "")
            jQuery(className).removeClass('active');
        else
            jQuery(".dimmer").removeClass('active');
    }
}
function reloadPage(sec = 0){
    if(sec!=0){
        setTimeout(function(){window.location.reload();},sec);
    }else{
        setTimeout(function(){window.location.reload();},500);
    }

}

/**
 * required parameters --> targetHTML, returnType, payLoad
 * @param param
 */
function ajaxCall(param = {},callback = "" ){

        if(typeof param.targetHTML != "undefined" &&
            typeof param.returnType != "undefined" &&
            typeof param.payLoad != "undefined"){
            var availTypes = ['POST','GET'];
            var type = (typeof param.callType == "undefined" || availTypes.indexOf((param.callType).toUpperCase()) < 0 ) ? "POST" : param.callType;
            // param.source = jQuery(".aj-link").data("source");
            manageLoader(true, param.loader);
            jQuery(param.targetHTML).addClass("active loader");
            jQuery.ajax({
                url: ajaxURL,
                type: type,
                cache: false,
                data: param.payLoad,

                // beforeSend: function(xhr){xhr.setRequestHeader('X-Source-Token', param.source);},
                function(){}
            }).done(function(res){
                jQuery(param.targetHTML).removeClass("active loader");
                manageLoader(false, param.loader);
                if(typeof param.onResponse != "undefined")
                    param.onResponse;

                if(param.returnType=="HTML"){
                    jQuery(param.targetHTML).html(res);
                }else if(param.returnType=="JSON"){

                    reponseMessage(res, param.targetHTML);

                }
                if(res.response){
                    if(callback!="")
                        callback(res);
                }else{
                    if(callback!="")
                        callback(res);
                }

            }).fail(function(res){
                console.log(res);
                manageLoader(false, param.loader);
                resetForm();

            });
        }else{
            throw "Required parameter(s) missing: ";
        }

}

function ajaxCallJSON(param = {},callback = "" ){

    if(typeof param.targetHTML != "undefined" &&
        typeof param.returnType != "undefined" &&
        typeof param.payLoad != "undefined"){
        var availTypes = ['POST','GET'];
        var type = (typeof param.callType == "undefined" || availTypes.indexOf((param.callType).toUpperCase()) < 0 ) ? "POST" : param.callType;
        // param.source = jQuery(".aj-link").data("source");
        manageLoader(true, param.loader);
        jQuery(param.targetHTML).addClass("active loader");
        jQuery.ajax({
            url: ajaxURL,
            type: type,
            cache: false,
            dataType: 'json',
            // contentType:'application/json',
            data: param.payLoad,

            // beforeSend: function(xhr){xhr.setRequestHeader('X-Source-Token', param.source);},
            function(){}
        }).done(function(res){
            jQuery(param.targetHTML).removeClass("active loader");
            manageLoader(false, param.loader);
            if(typeof param.onResponse != "undefined")
                param.onResponse;

            if(param.returnType=="HTML"){
                jQuery(param.targetHTML).html(res);
            }else if(param.returnType=="JSON"){

                reponseMessage(res, param.targetHTML);

            }
            if(res.response){
                if(callback!="")
                    callback(res);
            }else{
                if(callback!="")
                    callback(res);
            }

        }).fail(function(res){
            console.log(res);
            manageLoader(false, param.loader);
            resetForm();

        });
    }else{
        throw "Required parameter(s) missing: ";
    }

}


function fileUploader(param = {},callback = "" ){

    if(typeof param.targetHTML != "undefined" &&
        typeof param.returnType != "undefined" &&
        typeof param.payLoad != "undefined"){
        var availTypes = ['POST','GET'];
        var type = (typeof param.callType == "undefined" || availTypes.indexOf((param.callType).toUpperCase()) < 0 ) ? "POST" : param.callType;
        param.source = jQuery(".aj-link").data("source");
        manageLoader(true, param.loader);


        jQuery.ajax({
            url: ajaxURL,
            type: type,
            cache: false,
            contentType: false,
            processData: false,
            data: param.payLoad,
            beforeSend: function(xhr){xhr.setRequestHeader('X-Source-Token', param.source);},
            function(){}
        }).done(function(res){
            // jQuery(".ui.button").removeClass("loader");
            manageLoader(false, param.loader);

            if(typeof param.onResponse != "undefined")
                param.onResponse;

            if(param.returnType=="HTML"){
                jQuery(param.targetHTML).html(res);
            }else if(param.returnType=="JSON"){

                reponseMessage(res, param.targetHTML);

                // setTimeout(function(){ jQuery(param.targetHTML+' i.close').trigger('click'); }, 2500);
            }
            if(callback!="")
                callback(res);
        }).fail(function(res){
            manageLoader(false, param.loader);
            resetForm();

        });
    }else{
        throw "Required parameter(s) missing: ";
    }

}

function dynamicModal(){

    jQuery('.modal.dn-modal').modal({closable:true}).modal('show');
    jQuery(document).on("submit","form",function(e){
        e.preventDefault();
    });

}


function commonJS(){
    jQuery(document).on("click",".send-parent-consent",function(){
        var data = {};
        data.action = jQuery(this).data('action');
        data.formData = jQuery("form[name='parentsPermission']").serialize();

        let param = {};
        param.targetHTML = ".parent-resp-info";
        param.returnType = "JSON";
        param.callType = "POST";
        param.payLoad = data;
        ajaxCall(param,reloadPage);
    })
}

jQuery(document).ready(function($){
    $('select.dropdown').dropdown();
    $('.menu .item').tab();
    var ajaxUrl = jQuery(".aj-link").data("url");
    jQuery('.include-dt').DataTable();
    commonJS();


    /**
     * close the alert
     */
    $(document)
        .on('click','.message .close', function() {
            $(this)
                .closest('.message')
                .transition('fade');
        }).on("click",'.ui.butston',function(){
            jQuery(".ui.button").removeClass("loader");
            jQuery(this).addClass("loader");
    });

    jQuery(".aj-link form, .front-end-forms form").on("submit",function(e){
        e.preventDefault();
    });

    /**
     * Open modal window
     * @param options
     * @returns {*}
     */
    jQuery.fn.openModal = function (options) {
        var defaults = {
            title: "Modal",
            target: "collection",
            type: "loadMessage",
            message: "Error occurred!",
            preloaded: false,
            preloadcontent: ""
        };
        var modalOptions = jQuery.extend({},defaults, options);

        return this.each(function () {
            var o = modalOptions;
            jQuery(".dn-title").html(o.title);
            var data = o;
            data.action = "loadModalContent";
            data.typeOfContent = o.type;

            let param = {};
            param.targetHTML = ".dn-content";
            param.returnType = "HTML";
            param.callType = "POST";
            param.payLoad = data;
            param.loader = "."+o.target+"-loader";

            ajaxCall(param,dynamicModal);
        });
    }
});

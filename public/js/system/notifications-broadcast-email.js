jQuery(document).ready(function($){
    jQuery(document).on("click",".class-selection-email",function(){
        var data = {};
        data.action = jQuery(this).data('action');

        data.selectedClass = jQuery(".desiredTerms:checked").val();

        let param = {};
        param.targetHTML = ".notifications-broadcast-resp";
        param.returnType = "JSON";
        param.callType = "POST";
        param.payLoad = data;

        ajaxCall(param);

    }).on("click",'.broadcast-email',function(){
        var data = {};
        data.action = jQuery(this).data('action');

        var classes = [];
        jQuery('input[name="chosenTerm[]"]:checked').each(function(){
            classes.push($(this).val());
        });
        /*var classes = $.map($('input[name="chosenTerm[]"]:checked'), function(c){
                return c.value;
            });*/

        // console.log(array);/
        data.selectedClass = jQuery(".chosenTerm:checked").val();
        data.withPastor = jQuery("#with-pastor:checked").val();
        data.withParent = jQuery("#with-parent:checked").val();
        data.emailContent_ifr = jQuery("#broadCastEmailContent_ifr").contents().find('body').html();
        data.emailSubject = jQuery("#emailSubject").val();

        let param = {};
        param.targetHTML = ".notifications-broadcast-resp";
        param.returnType = "JSON";
        param.callType = "POST";
        param.payLoad = data;

        ajaxCall(param);
    });
});
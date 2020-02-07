jQuery(document).ready(function($){
    jQuery(document).on("click",".charge-another-half",function(){
        var data = {};
        data.action = jQuery(this).data('action');
         // = [];//jQuery.map($('input[name="chosenTerm[]"]:checked'), function(c){return c.value; })
        var classes = [];
        jQuery('input[name="chosenTerm[]"]:checked').each(function(){
            classes.push($(this).val());
        });
        data.selectedClass = classes;
        let param = {};
        param.targetHTML = ".notifications-fees-resp";
        param.returnType = "JSON";
        param.callType = "POST";
        param.payLoad = data;

        ajaxCall(param);
    });
});
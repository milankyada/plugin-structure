jQuery(document).ready(function(){
    jQuery(document).on("click",".ask-another-portrait",function(){
        var data = jQuery(this).data('dt');
        data.action = jQuery(this).data('action');
        data.subject = jQuery(this).data('requirement');
        data.studentEmail = jQuery(this).data('email');

        let param = {};
        param.targetHTML = ".student-info-port";
        param.returnType = "JSON";
        param.callType = "POST";
        param.payLoad = (data);
        ajaxCall(param,reloadPage);

        // ajaxCallJSON(param);
    });
});

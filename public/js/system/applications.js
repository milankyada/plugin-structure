jQuery(document).ready(function(){
    jQuery(document).on("click",".view-pastor-resp",function(){

        var options = {};
        options.type = "inForm";
        options.title = "Pastor's Response";
        options.target = "loadPastorResponse";

        options.token = jQuery(this).closest("td.rec-applications").data('token');


        jQuery(this).openModal(options);

    }).on("click",".upload-pastor-response",function(){

        var options = {};
        options.type = "inForm";
        options.title = "Upload Pastor's Response";
        options.target = "loadFileUpload";

        options.token = jQuery(this).closest("td.rec-applications").data('token');
        options.studentId = jQuery(this).closest("td.rec-applications").data('studentid');

        jQuery(this).openModal(options);

    }).on("click",".preview-file",function(){

        var options = {};
        options.type = "inForm";
        options.title = "Pastor's Response";
        options.target = "loadFilePreview";

        options.token = jQuery(this).closest("td.rec-applications").data('token');
        options.fileURL = jQuery(this).data('file');
        options.ext = jQuery(this).data('ext');
        options.studentId = jQuery(this).closest("td.rec-applications").data('studentid');

        jQuery(this).openModal(options);

    }).on("click",".delete-student",function(){

        var data = {};
        data.action = jQuery(this).data('action');
        data.studentId = jQuery(this).data('studentid');
        data.deleteRecordFor = "student";

        let param = {};
        param.targetHTML = ".student-info-resp";
        param.returnType = "JSON";
        param.callType = "POST";
        param.payLoad = data;

        ajaxCall(param,reloadPage);

    }).on("click",".open-email-popup",function(){

        var options = {};
        options.type = "inForm";
        options.title = "Quick Email";
        options.target = "loadNewEmail";

        options.token = jQuery(this).closest("td.rec-applications").data('token');
        options.studentId = jQuery(this).closest("td.rec-applications").data('studentid');
        options.studentName = jQuery(this).data('studentname');
        options.pastorName = jQuery(this).data('pastorname');
        options.studentEmail = jQuery(this).data('studentemail');
        options.pastorEmail = jQuery(this).data('pastoremail');
        options.formNumber = jQuery(this).data('formnumber');
        jQuery(this).openModal(options);

    }).on("click",".view-more-info",function(){

        var options = {};
        options.type = "inForm";
        options.title = "More Information";
        options.target = "loadMoreInfo";

        options.token = jQuery(this).closest("td.rec-applications").data('token');
        options.studentId = jQuery(this).closest("td.rec-applications").data('studentid');
        options.formNumber = jQuery(this).data('formnumber');
        options.desiredTerms = jQuery(this).data('terms');
        jQuery(this).openModal(options);

    }).on("click",".send-payment-link",function(){

        var data = {};
        data.action = jQuery(this).data('action');
        data.studentId = jQuery(this).closest("td.rec-applications").data('studentid');
        data.deleteRecordFor = "student";

        let param = {};
        param.targetHTML = ".student-info-resp";
        param.returnType = "JSON";
        param.callType = "POST";
        param.payLoad = data;

        ajaxCall(param);
    }).on("click",".show-receipt",function(){
        var options = {};
        options.type = "inForm";
        options.title = jQuery(this).data('title');
        options.target = "loadReceiptView";

        options.stdid = jQuery(this).closest("td.payment-rec-applications").data('studentid');
        options.id = jQuery(this).data('id');
        options.paidBy = jQuery(this).data('paidby');
        options.ext = jQuery(this).data('ext');
        options.part = jQuery(this).data('part');

        jQuery(this).openModal(options);
    }).on("click",".upload-receipt",function(){
        var options = {};
        options.type = "inForm";
        options.title = "Upload Cheque";
        options.target = "loadFileUpload";

        options.id = jQuery(this).data('id');
        options.paidBy = jQuery(this).data('paidby');
        options.part = jQuery(this).data('part');
        options.emailAddress = jQuery(this).data('email');
        options.formnumber = jQuery(this).data('formnumber');
        options.course = jQuery(this).data('course');
        options.studentId = jQuery(this).closest("td.payment-rec-applications").data('studentid');

        jQuery(this).openModal(options);
    });
});
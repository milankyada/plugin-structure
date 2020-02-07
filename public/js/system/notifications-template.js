jQuery(document).ready(function(){
    jQuery(document).on("click",".add-new-template",function(){
        var data = {};
        data.action = jQuery(this).data('action');
        data.emailContent_ifr = jQuery("#emailContent_ifr").contents().find('body').html();
        data.emailSubject = jQuery(".email-subject").val();
        data.emailTemplateName = jQuery(".email-template-name").val();

        let param = {};
        param.targetHTML = ".notifications-resp";
        param.returnType = "JSON";
        param.callType = "POST";
        param.payLoad = data;

        ajaxCall(param,reloadPage);
    }).on("click",".assign-template",function(){
            var data = {};
            data.action = jQuery(this).data('action');

            data.snc = jQuery(".save-and-continue option:selected").val();
            data.backToSchool = jQuery(".back-to-school option:selected").val();
            data.stuToSchoolOnline = jQuery(".student-to-school-online option:selected").val();
            data.stuToStudentOnline = jQuery(".student-to-himself-online option:selected").val();
            data.sendToPastor = jQuery(".send-to-pastor option:selected").val();
            data.paymentLinkToStudent = jQuery(".payment-link-to-student option:selected").val();
            data.paymentReceipt = jQuery(".payment-receipt option:selected").val();
            data.classSelection = jQuery(".class-selection option:selected").val();
            data.askParentConsent = jQuery(".ask-parent-consent option:selected").val();
            data.firstPortrait = jQuery(".first-portrait option:selected").val();
            data.secondPortrait = jQuery(".update-portrait option:selected").val();

            let param = {};
            param.targetHTML = ".notifications-assign";
            param.returnType = "JSON";
            param.callType = "POST";
            param.payLoad = data;

            ajaxCall(param);
        })
        .on("change",'.template-to-update',function(){
            var templateToUpdate = jQuery("option:selected",this).val();
            if(templateToUpdate != ""){
                jQuery(".update-template").attr("disabled",false);
                jQuery(".add-new-template").attr("disabled",true);
                var data = {};
                data.action = "loadEmailTemplate";
                data.templateToUpdate = templateToUpdate;
                jQuery(".template-to-update").val(templateToUpdate);
                let param = {};
                param.targetHTML = ".notifications-resps";
                param.returnType = "JSON";
                param.callType = "POST";
                param.payLoad = data;

                ajaxCall(param,loadEmailTemplateToUpdate);
            }else{
                jQuery(".update-template").attr("disabled",true);

                clearForm();
                // jQuery(".add-new-template").attr("disabled",false);
            }


        })
        .on("keyup","#for-new-template :input",function(){
            validateNewTemplate();
        })
        .on("click",".update-template",function(){
            var data = {};
            data.action = jQuery(this).data('action');
            data.emailContent_ifr = jQuery("#emailContent_ifr").contents().find('body').html();
            data.emailSubject = jQuery(".email-subject").val();
            data.emailTemplateName = jQuery(".email-template-name").val();
            data.templateId = jQuery(".template-to-update").val();

            let param = {};
            param.targetHTML = ".notifications-resp";
            param.returnType = "JSON";
            param.callType = "POST";
            param.payLoad = data;

            ajaxCall(param,reloadPage);
        }).on("click",".save-registrar-email",function(){
            var data = {};
            data.action = jQuery(this).data('action');

            data.registrarEmail = jQuery(".registrar-email").val();

            let param = {};
            param.targetHTML = ".notifications-resp-registrar";
            param.returnType = "JSON";
            param.callType = "POST";
            param.payLoad = data;

            ajaxCall(param);
    }).on("click",".test-attachment",function(){
        var data = {};
        data.action = jQuery(this).data('action');
        let param = {};
        param.targetHTML = ".notifications-resp-registrar";
        param.returnType = "JSON";
        param.callType = "POST";
        param.payLoad = data;
        ajaxCall(param);
    });
    var mediaUploader;
    jQuery('#upload-picture-button').on('click',function(e) {
        e.preventDefault();
        if( mediaUploader ){
            mediaUploader.open();
            return;
        }

        mediaUploader = wp.media.frames.file_frame = wp.media({
            title: 'Select a pdf',
            button: {
                text: 'Select File'
            },
            multiple: false
        });

        mediaUploader.on('select', function(){
            attachment = mediaUploader.state().get('selection').first().toJSON();
            jQuery('#upload_image').val(attachment.url);
            jQuery('#upload_image_fileName').val(attachment.filename);

            // jQuery('#user-picture-preview').css('background-image','url(' + attachment.url + ')');
        });

        mediaUploader.open();

    });

    jQuery('#upload_image_button').click(function($) {

        var data = {};
        data.action = jQuery(this).data('action');

        data.handBookURL = jQuery("#upload_image").val();
        data.fileName = jQuery("#upload_image_fileName").val();

        let param = {};
        param.targetHTML = ".notifications-resp-registrar";
        param.returnType = "JSON";
        param.callType = "POST";
        param.payLoad = data;

        ajaxCall(param,reloadPage);
        return false;
    });

});
function validateNewTemplate() {
    var templateName = jQuery(".email-template-name").val();
    var emailContent = jQuery("#emailContent_ifr").contents().find('body').html();
    var templateToUpdate = jQuery(".template-to-update").val();
    var emailSubject = jQuery(".email-subject").val();
    if(templateName !="" && emailContent != "" && emailSubject != "" && templateToUpdate ==""){
        jQuery(".add-new-template").attr("disabled",false);
    }else{
        jQuery(".add-new-template").attr("disabled",true);
    }
}

function loadEmailTemplateToUpdate(res){
    if(typeof res.data != "undefined"){
        var data = JSON.parse(res.data);
        jQuery(".email-template-name").val(data.emailTemplateName);
        jQuery("#emailContent_ifr").contents().find('body').html(data.emailContent);
        jQuery(".email-subject").val(data.emailSubject);
    }
}

function clearForm(){
    jQuery("#for-new-template").trigger('reset');
    validateNewTemplate();
}
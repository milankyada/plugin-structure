
function showEle(selector){
    if(jQuery(selector).hasClass('dont-show')){
        jQuery(selector).removeClass('dont-show');
    }
}
function hideEle(selector){
    if(!jQuery(selector).hasClass('dont-show')){
        jQuery(selector).addClass('dont-show');
    }
}

function openDialog(res){

    var firstName = jQuery("input[name='firstName']").val();

    var options = {};
    options.type = "inForm";
    options.title = "Registration";
    options.target = "saveNContinue";
    var data = {};
    data.tokenURL = res.tokenURL;
    data.name = res.name;
    options.preloadcontent = JSON.stringify(data);//res.message;//{fname:firstName,message:res.message};
    options.tokenURL = res.tokenURL;
    options.name = res.name;
    var extendedOptions = jQuery.extend({},options, data);

    jQuery(this).openModal(extendedOptions);

}

function openPastorFormToDownload(res){
    if(res.response){
        portraitUpload();
        var pastorDelivery = jQuery(".send-to-pastor:checked").val();
        if(pastorDelivery == 'downloadLink'){
            window.open(res.tokenURL);
        }
    }

}
function closeDialog(){

    jQuery(".modal").modal("hide dimmer").modal('hide');

}

jQuery(document).ready(function($){
    /*$('.ui.modal')
        .modal('show')
    ;*/
    var dateToday = new Date();
    var year = dateToday.getFullYear();
    var startYear = year-30;
   $(".datepicker").datepicker({dateFormat: 'yy-mm-dd',changeMonth: true, minDate: dateToday});
   $(".bibleSchoolWhen").datepicker({dateFormat: 'yy',
       changeYear: true,
       yearRange:+startYear+":"+year,
       changeMonth: false,
       onClose: function(dateText, inst) {
           var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
           $(this).datepicker('setDate', new Date(year, 1));
           $(".ui-datepicker-month").hide();
           $(".ui-datepicker-calendar").hide();
       }
   });
    $(".bibleSchoolWhen").focus(function () {
        $(".ui-datepicker-month").hide();

    });


   $(".dob-dt").datepicker({dateFormat: 'yy-mm-dd',changeMonth: true,yearRange: "-30:+0", changeYear:true});

    jQuery(".send-to-pastor").on("change",function(){
        var answer = jQuery(this).val();
        if(answer == 'emailLink'){
            showEle(".pastor-email input");
            hideEle(".pastor-email a");
            // jQuery(".pastor-email input").removeClass("dont-show");
        }else{
            showEle(".pastor-email a");
            hideEle(".pastor-email input");
        }
    });

    jQuery(".attended-bible-school").on("change",function(){
        var answer = jQuery(this).val();
        if(answer == 'yes'){
            showEle(".school-location");
            // hideEle(".pastor-email a");
            // jQuery(".pastor-email input").removeClass("dont-show");
        }else{
            // showEle(".pastor-email a");
            hideEle(".school-location");
        }
    });


    /**
     * SAVE AND CONTINUE
     */

    jQuery(document).on("click",".save-continue",function(){
        var data = {};
        data.action = jQuery(this).data('action');
        data.formData = jQuery("form[name='studentInfo']").serialize();

        let param = {};
        param.targetHTML = ".student-info-resp";
        param.returnType = "JSON";
        param.callType = "POST";
        param.payLoad = data;

        ajaxCall(param,openDialog);
    }).on("click",".send-link",function(){
        var data = {};
        data.action = jQuery(this).data('action');
        data.linkSendEmail = jQuery(".send-to-email").val();
        data.linkToSend = jQuery(".link-to-send").val();
        data.nameOfStudent = jQuery(".name-of-student").val();

        let param = {};
        param.targetHTML = ".send-link-resp";
        param.returnType = "JSON";
        param.callType = "POST";
        param.payLoad = data;

        ajaxCall(param,reloadPage);
    }).on("click",".submit-info",function(){
        var data = {};
        data.action = jQuery(this).data('action');
        // data.formData = new FormData(jQuery('#studentInfo')[0]);//jQuery("form[name='studentInfo']").serialize();
        data.formData = jQuery("form[name='studentInfo']").serialize();

        let param = {};
        param.targetHTML = ".student-info-resp";
        param.returnType = "JSON";
        param.callType = "POST";
        param.payLoad = data;


        ajaxCall(param,openPastorFormToDownload);


    }).on("change",'form',function(){
        if(!formValidation()){
            jQuery(".submit-info").attr("disabled",false);
            jQuery(".save-continue").attr("disabled",false);
            jQuery(".error-messages").hide();
        }else{
            jQuery(".error-messages").show();
            jQuery(".submit-info").attr("disabled",true);
            jQuery(".save-continue").attr("disabled",true);
        }
    });
});

function portraitUpload(){
    var form_data = new FormData();

    form_data.append("fileUploads", document.getElementById('studentInfoFile').files[0]);
    form_data.append("action","portraitUpload");

    form_data.append("studentEmail",jQuery('input[name="email"]').val());
    let param = {};
    param.targetHTML = ".student-info-resp";
    param.returnType = "JSON";
    param.callType = "POST";
    param.payLoad = form_data;
    param.loader = ".file-upload-loader";

    fileUploader(param);
}
function formValidation() {
    var error = false;
    jQuery(".field-required").each(function(){
        var className = jQuery(this).attr("name");
        if(jQuery(this).val()===''){
            error = true;

            var errorCode = jQuery(this).data("msg");
            var errors = errorMSGStudntForm();
            var errorMessage = errors[errorCode];
            addErrorListStudents(className,errorMessage);
            jQuery(this).removeClass('error-caught').addClass("error-caught");

        }else{
            jQuery("."+className).remove();
        }

        if(jQuery(this).attr("type")==="radio" || jQuery(this).attr("type")==="checkbox"){
            var attrName = jQuery(this).attr("name");
            if(!jQuery("input[name='"+attrName+"']").is(":checked")){
                error = true;

                var errorCode = jQuery(this).data("msg");
                var errors = errorMSGStudntForm();
                var errorMessage = errors[errorCode];
                addErrorListStudents(className,errorMessage);
                jQuery(this).removeClass('error-caught').addClass("error-caught");
            }
        }
    });
    return error;
}
function errorMSGStudntForm(){
    return {
        'APP_DATE':"Application date is empty.",
        'PEMAILADDRESS':"Parent's email address is empty.",
        'MEDCONDITION': "Have you had medical condition before?",
        'AGE' : "Age is missing",
        'STEMAIL' : "Student Email is missing",
        'COUNTRY' : "Country name is missing",
        'POSTAL' : "Zipcode is missing",
        'CITY' : "City is missing",
        'PASTORNAME' : "Pastor name is missing",
        'PLNAME' : "Parent's First name is missing",
        'PFNAME' : "Parent's Last name is missing",
        'FNAME' : "Student First name is missing",
        'LNAME' : "Student Last name is missing",
        'STATE' : "State/Province is missing",
        'STADDRESS' : "Address is missing",
        'DOB': "Date of birth is missing",
        'CONTACT_BY': "Way to contact pastor information is missing"
    };
}
function addErrorListStudents(className, errorMessage){

    jQuery('.'+className).remove();
    var $errorList = jQuery(".error-list");
    var htmlContent = $errorList.html();
    htmlContent += '<li class="'+className+'">'+errorMessage+'</li>';
    $errorList.html(htmlContent);
    jQuery(".error-messages").removeClass('dont-show');
}
jQuery(document).ready(function(){
    jQuery(document).on("click",'.send-to-school',function(){
        var data = {};
        data.action = jQuery(this).data('action');
        data.formData = jQuery("form[name='pastorInfo']").serialize();

        let param = {};
        param.targetHTML = ".pastor-info-resp";
        param.returnType = "JSON";
        param.callType = "POST";
        param.payLoad = data;


        ajaxCall(param);
    }).on("change",'form',function(){
        if(!formValidation()){
            jQuery(".send-to-school").attr("disabled",false);

        }else{
            jQuery(".send-to-school").attr("disabled",true);

        }
    });

    jQuery(".is-member").on("change",function(){
        var answer = jQuery(this).val();
        if(answer == 'yes'){
            showEle(".time-period");

        }else{

            hideEle(".time-period");
        }
    });
    jQuery(".pre-problem").on("change",function(){
        var answer = jQuery(this).val();
        if(answer.toLowerCase() == 'yes'){
            showEle(".pre-prob-location");

        }else{

            hideEle(".pre-prob-location");
        }
    });
});

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
function formValidation() {
    var error = false;
    jQuery(".field-required").each(function(){
        if(jQuery(this).val()===''){
            error = true;
            jQuery(this).removeClass('error-caught').addClass("error-caught");
        }
    });
    return error;
}
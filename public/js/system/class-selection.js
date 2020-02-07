jQuery(document).ready(function(){

    var terms = jQuery(".avail-time-slots").val();
    var termIDArr = terms.split(",");

    jQuery(document).on("change",".select-class",function(){
        var timeSlot = jQuery(this).data('cat');
        var classId = jQuery(this).data('class');
        var hiddenField = ".comb-"+timeSlot+"-"+classId;
        var priority = jQuery(this,'.select-class option:selected').val();
        jQuery(hiddenField).val("");


        if(jQuery(this,'.select-class option:selected').val()!== ""){
            var finalValue = timeSlot+"-"+priority+"-"+classId;
            jQuery(hiddenField).val(finalValue);
            jQuery(".tc-"+timeSlot+"-"+classId).val(finalValue);

        }

        var sameChoiceLimit = validateRepeatedChoices();
        var numOfChoice = verifyClassSelection();
        if(sameChoiceLimit || numOfChoice){
            if(numOfChoice){
                jQuery(".submit-choices").attr("disabled",true);
                if(jQuery(".error-messages").hasClass("dont-show")){
                    jQuery(".error-messages").removeClass("dont-show");
                }
            }else{

                if(!jQuery(".error-list").html()){
                    jQuery(".error-messages").removeClass("dont-show").addClass("dont-show");
                }
            }

            if(sameChoiceLimit){
                jQuery(".submit-choices").attr("disabled",true);
                addErrorList("same-in-row",errorMSG().SAME_CHOICE_IN_ROW);
            }else{
                jQuery(".same-in-row").remove();
                if(!jQuery(".error-list").html()){
                    jQuery(".error-messages").removeClass("dont-show").addClass("dont-show");
                }

            }
        }else {
            jQuery(".error-messages").removeClass("dont-show").addClass("dont-show");
            jQuery(".submit-choices").attr("disabled",false);
        }


    }).on("click",".submit-choices",function(){
        var data = {};
        data.action = jQuery(this).data('action');
        data.formData = jQuery("form[name='classSelectionInfo']").serialize();

        let param = {};
        param.targetHTML = ".student-selection-resp";
        param.returnType = "JSON";
        param.callType = "POST";
        param.payLoad = data;

        ajaxCall(param);
    });
});

function errorMSG() {
    return {
        'C1_LIMIT':"You must have a minimum of three first choice (1)  selections in different class periods before you can submit your choices",
        'C2_LIMIT': "You must select 2 second choice classes",
        'A_LIMIT' : "You can choose only 1 class as an Audit",
        'SAME_CHOICE_IN_ROW': "You cannot select more than 1 first choice in a single class period"
    };
}

/**
 *
 * @returns {boolean}
 */
function validateRepeatedChoices(){
    var error = false;
    var timeSlots = jQuery(".avail-time-slots").val();
    var slots = timeSlots.split(",");

    slots.forEach(function(v,k){
        var firstChoice = 0;
        var secondChoice = 0;
        var auditChoice = 0;
        var selectedChoices = [];
        jQuery("select.time-slot-"+v).each(function(){
            var slotName = jQuery(this,".time-slot-"+v+" option:selected").data('slot');
            if(jQuery(this,".time-slot-"+v+" option:selected").val()=="1")
                firstChoice++;

            if(jQuery(this,".time-slot-"+v+" option:selected").val()=="2")
                secondChoice++;

            if(jQuery(this,".time-slot-"+v+" option:selected").val()=="a")
                auditChoice++;

            selectedChoices[selectedChoices.length] = jQuery(this,".time-slot-"+v+" option:selected").val();

        });

        if(firstChoice > 1 || secondChoice > 1 || auditChoice > 1)
            error = true;

    });

    return error;
}
function addErrorClass(className){
    if(!jQuery(className).hasClass('selection-error')){
        jQuery(className).addClass('selection-error');
    }
}
function removeErrorClass(className){
    jQuery(className).removeClass('selection-error');
}

function verifyClassSelection( ){
    var error = false;
    var message = 'Condition(s) satisfied';
    var firstChoices = 0;
    var secondChoices = 0;
    var auditCourse = 0;

    jQuery(".select-class").each(function(){
        var alreadySelected = jQuery(this,'.select-class option:selected').val();
        if(alreadySelected == 1)
            firstChoices++;

        if(alreadySelected == 2)
            secondChoices++;

        if(alreadySelected == 'a')
            auditCourse++;
    });


    if(firstChoices === 0)
        error = true;

    if(firstChoices > 4 || firstChoices < 3){

        error = true;
        message = errorMSG().C1_LIMIT;
        addErrorList("num-error-c1",message);

    }else{
        jQuery(".num-error-c1").remove();
    }

    if(secondChoices > 2){

        error = true;
        message = errorMSG().C2_LIMIT;
        addErrorList("num-error-c2", message);

    }else{
        jQuery(".num-error-c2").remove();
    }

    if(auditCourse > 1){

        error = true;
        message = errorMSG().A_LIMIT;
        addErrorList("num-error-c3", message);


    }else{
        jQuery(".num-error-c3").remove();
    }

    if(!error){
        error = false;

        // jQuery(".num-error").removeClass("dont-show").addClass("dont-show");
        jQuery('.num-error-icon').removeClass("green check red close").addClass("green check");
        jQuery(".num-of-classes").text('Condition(s) satisfied');
    }
//
    return error;

}

function addErrorList(className, errorMessage){

    jQuery('.'+className).remove();
    var $errorList = jQuery(".error-list");
    var htmlContent = $errorList.html();
    htmlContent += '<li class="'+className+'">'+errorMessage+'</li>';
    $errorList.html(htmlContent);
    jQuery(".error-messages").removeClass('dont-show');
}

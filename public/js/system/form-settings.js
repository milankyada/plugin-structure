jQuery(document).ready(function($){
    var dateToday = new Date();
    // $("#fEndDate, #fStartDate").datepicker({dateFormat: 'yy-mm-dd',changeMonth: true, minDate: dateToday});

    var dateFormat = "yy-mm-dd",
        from = $( "#fStartDate" )
            .datepicker({
                defaultDate: "+1w",
                changeMonth: true,
                numberOfMonths: 4,
                dateFormat: dateFormat,

            })
            .on( "change", function() {
                to.datepicker( "option", "minDate", getDate( this ) );
            }),
        to = $( "#fEndDate" ).datepicker({
                defaultDate: "+1w",
                changeMonth: true,
                numberOfMonths: 4,
                dateFormat: dateFormat
            })
            .on( "change", function() {
                from.datepicker( "option", "maxDate", getDate( this ) );
            });

    function getDate( element ) {
        var date;
        try {
            date = $.datepicker.parseDate( dateFormat, element.value );
        } catch( error ) {

            date = null;
        }
        return date;
    }


    jQuery(document).on("click",".save-form-open",function(){

        var data = {};
        data.action = jQuery(this).data('action');
        data.startDate = jQuery("input[name='fStartDate']").val();
        data.endDate = jQuery("input[name='fEndDate']").val();

        let param = {};
        param.targetHTML = ".form-settings-resp";
        param.returnType = "JSON";
        param.callType = "POST";
        param.payLoad = data;
        ajaxCall(param);
    })
});
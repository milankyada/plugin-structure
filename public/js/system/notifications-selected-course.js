jQuery(document).ready(function(){
    jQuery(document).on("click",".filter-by-term",function(){
        var data = {};
        data.action = jQuery(this).data('action');
        data.termId = jQuery(".select-term").val();

        let param = {};
        param.returnType = "HTML";
        param.callType = "POST";
        param.targetHTML = ".selected-courses";
        param.payLoad = data;

        ajaxCall(param);
    }).on("click",".export-excel",function(){
        var url = jQuery(this).data("url")+'/reports/student-report.php';
        // location.href = url;
        window.open(url, '_blank');
    });
});
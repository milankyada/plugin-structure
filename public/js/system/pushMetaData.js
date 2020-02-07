jQuery(document).ready(function(){

    /**
     * these methods are from semantic ui
     */
    jQuery("select").dropdown("setting",'onChange',function (val) {
        var website = jQuery("#target-web option:selected").val() || null;
        var file = jQuery("#select-file option:selected").val() || null;
        var worksheet = jQuery("#select-a-sheet option:selected").val() || null;
        var posttype = jQuery("#select-post-type option:selected").val() || null;
        var post = jQuery("#select-post option:selected").val() || null;

        if(website!=null && file!=null && worksheet!=null && posttype!=null && post!=null){
            jQuery(".push-btn").attr("disabled",false);
        }else{
            jQuery(".push-btn").attr("disabled",true);
        }
    });

    jQuery("#target-web").dropdown("setting",'onChange',function (val) {
        var data = {};
        data.action = "getFilesForWeb";
        data.targetWeb = val;

        var param = {};
        param.targetHTML = "#select-file";
        param.returnType = "HTML";
        param.payLoad = data;
        jQuery("#select-file").dropdown('clear');
        jQuery("#select-post-type").dropdown('clear');

        ajaxCall(param);
    });

    jQuery("#select-file").dropdown("setting",'onChange',function (val) {
        var data = {};
        data.action = "getSheetsForFile";
        data.fid = jQuery(this).dropdown('get value');
        jQuery("#select-a-sheet").dropdown('clear');
        var param = {};
        param.targetHTML = "#select-a-sheet";
        param.returnType = "HTML";
        param.payLoad = data;
        if(val!='')
            ajaxCall(param);
    });

    jQuery("#select-post-type").dropdown("setting",'onChange',function(val){

        var post = val;//jQuery(this).dropdown('get value');
        var data = {};
        data.action = "getPostsBySite";
        data.postType = post;
        data.website = jQuery("#target-web").dropdown('get value');

        var param = {};
        param.targetHTML = "#select-post";
        param.returnType = "HTML";
        param.payLoad = data;
        jQuery("#select-post").dropdown('clear');

        ajaxCall(param);
    });

    jQuery(".push-btn").on("click",function(){
        var data = {};
        data.website = jQuery("#target-web option:selected").val() || null;
        data.fid = jQuery("#select-file option:selected").val() || null;
        data.worksheet = jQuery("#select-a-sheet option:selected").val() || null;
        data.posttype = jQuery("#select-post-type option:selected").val() || null;
        data.post = jQuery("#select-post option:selected").val() || null;

        data.action = "pushMetaData";

        var param = {};
        param.targetHTML = ".push-meta-data";
        param.returnType = "JSON";
        param.payLoad = data;

        ajaxCall(param);
    })

});
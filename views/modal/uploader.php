<div class="ui center aligned grid">
    <div class="sixteen wide column">
        <a class="ui label preview-file dont-show">
            <span class="file-name"></span>
        </a>
        <form enctype="multipart/form-data" name="fileUploads" id="fileUploads" class="ui form">
            <div class="ui active inverted dimmer dont-show file-upload-loader" id="loader">
                <div class="ui text loader">Loading</div>
            </div>
            <div class="ui blue labels dont-show preview-of-uploaded">

            </div>
            <div class="upload-area field hover-pointer">
                <p class="ui center aligned">Drag and drop a file in this area to upload</p>
            </div>
            <input type="file" id="fileUpload" name="fileUpload" class="dont-show" >

            <input type="hidden" id="studentId" name="studentId" value="<?= @$args['studentId'] ?>">
            <input type="hidden" id="token" name="token" value="<?= @$args['token'] ?>">
            <input type="hidden" id="part" name="part" value="<?= @$args['part'] ?>">
            <input type="hidden" id="course" name="course" value="<?= @$args['course'] ?>">
            <?php
            if(!empty($args['part'])){
                ?>
                <div class="field">
                    <label>Paid Amount By Cheque</label>
                    <input type="text" id="amount" name="amount" value="">
                </div>

            <?php
            }
            ?>
            <input type="hidden" id="emailAddress" name="emailAddress" value="<?= @$args['emailAddress'] ?>">
            <input type="hidden" id="formNumber" name="formNumber" value="<?= @$args['formnumber'] ?>">

            <button class="ui green inverted button process-action" disabled>
                <i class="checkmark icon"></i>
                Upload
            </button>
        </form>
        <div class="ui message dont-show upload-files">
            <i class="close icon"></i>
            <div class="header">

            </div>
            <p class="response"></p>
        </div>
    </div>
</div>
<style>
    .hover-pointer{
        cursor: pointer;
    }
    .upload-area{
        border: 2px dashed lightgray;
        padding: 20px;

        justify-content: center;
        align-items: center;
        text-align: center;
        align-content: center;
    }
    .dont-show{
        display: none !important;
    }
</style>
<script>
    jQuery(document).ready(function(){
        var $previewFile = jQuery(".preview-file").clone();

        jQuery('.upload-area').on("click",function(){
            jQuery("#fileUpload").trigger('click');
        });
        jQuery(".preview-of-uploaded").html('');
        jQuery("#fileUpload").on("change",function(){

            // var $previewFile = jQuery(".preview-file").clone();
            var filesToGo = [];
            var selectedFiles = [];

            var ins = document.getElementById('fileUpload').files.length;

            for (var x = 0; x < ins; x++) {
                var file = document.getElementById('fileUpload').files[x];

                filesToGo.push(document.getElementById('fileUpload').files[x]);

                $previewFile.find(".file-name").text(file.name);
                $previewFile.removeClass("dont-show");
                selectedFiles.push($previewFile[0].outerHTML);
            }
            if(selectedFiles.length>0){
                jQuery("form .process-action").attr("disabled",false)
            }else{
                jQuery("form .process-action").attr("disabled",true)
            }


            var $showPreivew = selectedFiles.join("");
            jQuery(".preview-of-uploaded").html($showPreivew);
            jQuery(".preview-of-uploaded").removeClass("dont-show");
            jQuery(".upload-area").hide();
        });

        /**
         * Upload multiple files operation
         */
        jQuery("form .process-action").on("click",function(){
            var form_data = new FormData();

            form_data.append("fileUploads", document.getElementById('fileUpload').files[0]);
            form_data.append("action","uploadFiles");

            form_data.append("studentId",jQuery("#studentId").val());
            form_data.append("part",jQuery("#part").val());
            form_data.append("emailAddress",jQuery("#emailAddress").val());
            form_data.append("formNumber",jQuery("#formNumber").val());
            form_data.append("course",jQuery("#course").val());
            if(jQuery("#amount")){
                form_data.append("amount",jQuery("#amount").val());
            }


            let param = {};
            param.targetHTML = ".upload-files";
            param.returnType = "JSON";
            param.callType = "POST";
            param.payLoad = form_data;
            param.loader = ".file-upload-loader";

            fileUploader(param);
        });
    })
</script>

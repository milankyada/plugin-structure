
<?php
if(!empty($args['token'])){
    $result = PluginFunc::studentInfoForBackend($args['token']);

    if($result['response']){
        $message = json_decode(base64_decode($result['message']),true);
        $data = (!empty($message)) ? $message : [];
    }else{
        echo $result['message'];
    }
    $imageURL = (!empty($data['studentPortrait'])) ? EX_PLUGIN_URL.'Portrait/'.$data['studentPortrait'] : "";
    $studentName = $data['firstName'].' '.$data['lastName'];

}

?>
<div class="ui two column stackable grid">
    <div class="ui sixteen wide column">
        <div class="ui message dont-show student-info-port">
            <i class="close icon"></i>
            <div class="header">

            </div>
            <p class="response"></p>
        </div>
        <div class="ui centered card">

            <?php
            if(!empty($imageURL)){
            ?>
                <div class="image">
                    <img src="<?= $imageURL?>">
                </div>
            <?php
            }else{
                ?>
                <div class="ui placeholder">
                    <div class="image"></div>
                </div>
            <?php
            }
            ?>


            <div class="content">
                <a class="header"><?= $studentName?></a>
            </div>
            <?php
            if(!empty($imageURL)){
                ?>
                <div class="ui bottom blue attached button ask-another-portrait" data-action="askAnotherPortrait" data-requirement="secondPortrait"  data-email="<?= $data['email']?>" data-dt='<?= json_encode($data) ?>'>
                    Ask for another portrait
                </div>
            <?php
            }else{
                ?>
                <div class="ui bottom orange attached button ask-another-portrait" data-action="askAnotherPortrait" data-requirement="firstPortrait" data-email="<?= $data['email']?>" data-dt='<?= json_encode($data) ?>'>
                    Ask for portrait
                </div>
            <?php
            }
            ?>

        </div>
    </div>

</div>
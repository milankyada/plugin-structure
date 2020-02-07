<div class="ui center aligned grid" style="height:100%">
    <div class="sixteen wide column">
        <?php
        if(!empty($args['ext'])){
            $image = ['jpeg','jpg','png'];
            if(@strtolower($args['ext'])=='pdf'){
                ?>
                <embed src="<?= $args['fileURL']?>" style="width:100%; height:700px;" type="application/pdf">

        <?php
            }
            else if(in_array(@strtolower($args['ext']),$image)){
                ?>
                <iframe src="<?= $args['fileURL']?>" style="width:100%; height:700px;"  frameborder="0"></iframe>
        <?php
            }
        }
        ?>

    </div>
</div>
<h2 class="ui header">
    <i class="cloud upload icon"></i>
    <div class="content">
        Upload a file
        <div class="sub header">Upload Excel Files(s)</div>
    </div>
</h2>
<div class="">

    <div class="ui grid">
        <div class="four wide column">

            <div class="ui grid">
                <div class="sixteen wide column">
                    <div class="ui raised orange segment need-loader">
                        <a class="ui orange ribbon label">Upload a file</a>
                        <a class="ui label preview-file dont-show">
                            <span class="file-name"></span>
                        </a>
                        <form id="fileUploads" class="ui form" enctype="multipart/form-data" name="fileUploads">
                            <div class="ui active inverted dimmer dont-show file-upload-loader" id="loader">
                                <div class="ui text loader">Loading</div>
                            </div>
                            <div class="field">
                                <label>Give a name to file</label>
                                <input type="text" name="give-file-name" placeholder="Give a file name" class="give-file-name">
                                <small>File name is for your reference</small>
                            </div>
                            <div class="field">
                                <label>Select Target WebSite</label>
                                <?php
                                //                        echo sha1(HUB_AUTH);
                                $sites = SiteData::getInfo();
                                ?>
                                <select class="ui dropdown target-web" name="targetWeb" id="target-web">
                                    <option value="">Select Website</option>
                                    <?php
                                    foreach ($sites as $s=>$site){
                                        ?>
                                        <option value="<?= $s?>"><?= $site['name']?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="ui blue labels dont-show preview-of-uploaded">

                            </div>
                            <div class="upload-area field hover-pointer">
                                <p class="ui center aligned">Drag and drop a file in this area to upload</p>
                            </div>
                            <input type="file" id="fileUpload" name="fileUpload" class="dont-show" >
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
                <div class="sixteen wide column">
                    <div class="ui raised blue segment need-loader">
                        <a class="ui blue ribbon label">Acronym & Collection Codes</a>
                        <p><small><b>NOTE: </b>If you're not sure how to use this form then contact Site administrator first</small></p>
                        <form id="loadCollectionCodes" class="ui form" name="loadCollectionCodes">
                            <div class="ui active inverted dimmer dont-show collection-code-loader" id="loader">
                                <div class="ui text loader">Loading</div>
                            </div>
                            <div class="field">
                                <label>Select File</label>
                                <?php
                                $fmodel = new \center\models\FileModel();
                                $files = $fmodel->getAllFile();
                                ?>
                                <select class="ui dropdown" id="collection-code-file">
                                    <?php
                                    if($files['total']>0){
                                        ?>
                                        <option value="">Select a file</option>
                                        <?php
                                        foreach ($files['result'] as $f){
                                            ?>
                                            <option value="<?= $f['id']?>"><?= $f['fileName']?></option>
                                            <?php
                                        }
                                    }else{
                                        ?>
                                        <option value="">No file(s) uploaded</option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="field">
                                <label>For..</label>
                                <select class="ui dropdown" id="imported-to">
                                    <option value="">Import To...?</option>
                                    <option value="collection-code">Import To Collection Code</option>
                                    <option value="acronym">Import To Acronym</option>
                                </select>
                            </div>
                            <button class="ui blue inverted button load-collection-code" id="load-collection-code" disabled="disabled">Load Collection Coeds</button>
                        </form>
                        <div class="ui message dont-show insert-collection-code">
                            <i class="close icon"></i>
                            <div class="header">

                            </div>
                            <p class="response"></p>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <div class="twelve wide column">
            <div class="ui blue segment">
                <div class="ui active inverted dimmer dont-show file-list-loader" id="loader">
                    <div class="ui text loader">Loading</div>
                </div>
                <div class="ui message dont-show file-operation">
                    <i class="close icon"></i>
                    <div class="header">

                    </div>
                    <p class="response"></p>
                </div>
                <table class="ui celled structured table ">
                    <thead>
                        <tr>
                            <th>File</th>
                            <th>Description</th>
                            <th>For Website</th>
                            <th>By</th>
                            <th colspan="2">Uploaded On</th>
                        </tr>
                    </thead>
                    <tbody class="list-of-files">


                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="ui basic modal final-call-modal">
    <div class="ui icon header">
        <i class="archive icon"></i>
        Selected File
    </div>
    <div class="content">
        <p></p>
    </div>
    <div class="actions">
        <div class="ui red basic cancel inverted button">
            <i class="remove icon"></i>
            No
        </div>
        <div class="ui green ok inverted button final-call-collection-code">
            <i class="checkmark icon"></i>
            Yes
        </div>
    </div>
</div>
<?php
wp_enqueue_script("file-upload");
?>
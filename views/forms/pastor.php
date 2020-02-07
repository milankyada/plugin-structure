<?php
if(!empty($_REQUEST['token'])){
    $result = PluginFunc::checkTokenStatusForPastorForm(urlencode($_REQUEST['token']));

    if($result['response']){
        $message = json_decode(base64_decode($result['message']),true);
        $data = (!empty($message)) ? $message : [];
    }else{
        echo $result['message'];
    }

//    echo urlencode($_REQUEST['token']);
/*echo '<pre>';
    echo 'test';
    print_r($data);
echo '</pre>';*/

}
if(1){
    ?>
    <div class="front-end-forms">
        <input type="hidden" id="ajax-url" value="<?= admin_url('admin-ajax.php');?>">

        <button class="ui button print-form" onclick="printForm('pastor-information-form')">Print</button>
        <form class="ui form" id="pastor-information-form" name="pastorInfo">
            <input type="hidden" id="css-url" value="<?=EX_PLUGIN_URL."public/css/semantic.css" ?>">
            <div class="fields">
                <div class="field">
                    <label>Application Number (Office Use Only)</label>
                    <?php
                    $ext = ['readonly'=>'readonly'];
                    $param = array('name'=>'applicationNumber','ext'=>$ext);
                    $param['value']=@$data['formNumber'];
                    $param['class'] = "application-number";

                    FormFields::input($param);
                    ?>
                </div>

            </div>


            <!-- APPLICANT NAME -->
            <div class="field">
                <h4 class="ui header">Applicant Name</h4>
                <div class="">
                    <div class="field">
                        <?php

                        $ext = ['placeholder'=>'Applicant Name','readonly'=>'readonly'];
                        $param = array('name'=>'applicantName','ext'=>$ext);
                        $param['value']=@$data['firstName'].' '.@$data['lastName'];
                        FormFields::input($param);
                        ?>
                        <?php
                        $param = array('name'=>'studentId','type'=>'hidden');
                        $param['value']=@$data['stdId'];
                        FormFields::input($param);
                        ?>
                        <?php

                        $param = array('name'=>'token','type'=>'hidden');
                        $param['value']=@$data['formToken'];
                        FormFields::input($param);
                        ?>

                        <?php

                        $param = array('name'=>'pastorId','type'=>'hidden');
                        $param['value']=@$data['pastorId'];
                        FormFields::input($param);
                        ?>
                    </div>
                </div>
            </div>
            <div class="field">
                <h4 class="ui header">Pastor's Name</h4>
                <div class="two fields">
                    <div class="field">
                        <?php
                        $ext = ['placeholder'=>'First Name'];
                        $param = array('name'=>'firstName','ext'=>$ext);
                        $param['class']=" field-required";
                        FormFields::input($param);
                        ?>
                    </div>
                    <div class="field">
                        <?php
                        $ext = ['placeholder'=>'Last Name'];
                        $param = array('name'=>'lastName','ext'=>$ext);
                        $param['class']=" field-required";
                        FormFields::input($param);
                        ?>
                    </div>
                </div>
            </div>

            <div class="field">
                <h4 class="ui header">Pastor's Address</h4>

                <div class="field">
                    <?php
                    $ext = ['placeholder'=>'Street Address'];
                    $param = array('name'=>'line1','ext'=>$ext);
                    $param['class']=" field-required";
                    FormFields::input($param);
                    ?>
                </div>


                <div class="field">
                    <?php
                    $ext = ['placeholder'=>'Address Line 2'];
                    $param = array('name'=>'line2','ext'=>$ext);

                    FormFields::input($param);
                    ?>
                </div>

                <div class="two fields">
                    <div class="field">
                        <?php
                        $ext = ['placeholder'=>'City'];
                        $param = array('name'=>'city','ext'=>$ext);
                        $param['class']=" field-required";
                        FormFields::input($param);
                        ?>
                    </div>
                    <div class="field">
                        <?php
                        $ext = ['placeholder'=>'State / Province / Region'];
                        $param = array('name'=>'state','ext'=>$ext);
                        $param['class']=" field-required";
                        FormFields::input($param);
                        ?>
                    </div>
                </div>
                <div class="two fields">
                    <div class="field">
                        <?php
                        $ext = ['placeholder'=>'ZIP / Postal Code'];
                        $param = array('name'=>'zip','ext'=>$ext);
                        $param['class']=" field-required";
                        FormFields::input($param);
                        ?>
                    </div>
                    <div class="field">
                        <?php
                        $ext = ['placeholder'=>'Country'];
                        $param = array('name'=>'state','ext'=>$ext,'firstSelect'=>'Select Country');
                        $param['class']=" field-required";
                        $param['options'] = FormFields::listOfCountries();
                        FormFields::select($param);
                        ?>
                    </div>
                </div>
            </div>

            <div class="fields">
                <div class="field">
                    <label>Pastor's Phone</label>
                    <?php
                    $ext = ['placeholder'=>'Pastor Phone'];
                    $param = array('name'=>'phone','ext'=>$ext);
                    $param['class']=" field-required";
                    FormFields::input($param);
                    ?>
                </div>
            </div>

            <h3 class="ui dividing header">Please answer the following questions to the best of your ability</h3>
            <div class="fields">

                <div class="eight wide field">
                    <h4 class="ui header">1. Is the applicant a member? </h4>
                    <div class="inline fields">
                        <div class="field">
                            <div class="ui radio checkbox">
                                <?php
                                $param = array('name'=>'isMember','value'=>'yes','type'=>'radio');
                                $param['checked'] = @$data[$param['name']];
                                $param['class'] = "is-member";
                                $param['id'] = "is-member-yes";
                                FormFields::input($param);
                                ?>
                                <label for="is-member-yes">Yes</label>
                            </div>
                        </div>
                        <div class="field">
                            <div class="ui radio checkbox">
                                <?php
                                $param = array('name'=>'isMember','value'=>'no','type'=>'radio');
                                $param['checked'] = @$data[$param['name']];
                                $param['class'] = "is-member";
                                $param['id'] = "is-member-no";
                                FormFields::input($param);
                                ?>
                                <label for="is-member-no">No</label>

                            </div>
                        </div>
                    </div>
                    <?php

                    /*$param = array('name'=>'memberTimePeriod','type'=>'text');
                    FormFields::input($param);*/
                    ?>
                </div>
                <div class="eight wide field dont-show time-period">
                    <h4 class="ui header">How long?</h4>
                    <?php
                    $param = array('name'=>'timeBnChristian','firstSelect'=>'How long?');
                    $param['options'] = FormFields::timeBeingChristian();
                    FormFields::select($param);
                    ?>
                </div>

            </div>

            <div class="field">
                <h4 class="ui header">2. How do you perceive the applicant's spiritual condition?</h4>
                <div class="inline fields">
                    <div class="field">
                        <?php

                        $param = array('name'=>'spiritualCondition','type'=>'radio','value'=>STRONG);
                        $param['id'] = "sp-strong";
                        FormFields::input($param);
                        ?>
                        <label for="sp-strong">Spiritually Strong</label>
                    </div>
                    <div class="field">
                        <?php

                        $param = array('name'=>'spiritualCondition','type'=>'radio','value'=>GROWING);
                        $param['id'] = "gro-sp";
                        FormFields::input($param);
                        ?>
                        <label for="gro-sp">Growing Spiritually</label>
                    </div>
                    <div class="field">
                        <?php

                        $param = array('name'=>'spiritualCondition','type'=>'radio','value'=>WEAK);
                        $param['id'] = "sp-weak";
                        FormFields::input($param);
                        ?>
                        <label for="sp-weak">Spiritually Weak</label>
                    </div>
                    <div class="field">
                        <?php

                        $param = array('name'=>'spiritualCondition','type'=>'radio','value'=>CARELESS);
                        $param['id'] = "careless-sp";
                        FormFields::input($param);
                        ?>
                        <label for="careless-sp">Careless Spiritually</label>
                    </div>

                </div>

            </div>

            <div class="field">
                <h4 class="ui header">3. Which of the following best describes his/her response to authority in the home and the church?</h4>
                <div class="inline fields">
                    <div class="field">
                        <?php

                        $param = array('name'=>'respToAuthority','type'=>'radio','value'=>VGOOD);
                        $param['id'] = "v-good";
                        FormFields::input($param);
                        ?>
                        <label for="v-good">Very Good</label>
                    </div>
                    <div class="field">
                        <?php

                        $param = array('name'=>'respToAuthority','type'=>'radio','value'=>ONLYGOOD);
                        $param['id'] = "good";
                        FormFields::input($param);
                        ?>
                        <label for="good">Good</label>
                    </div>
                    <div class="field">
                        <?php

                        $param = array('name'=>'respToAuthority','type'=>'radio','value'=>FAIR);
                        $param['id'] = "fair";
                        FormFields::input($param);
                        ?>
                        <label for="fair">Fair</label>
                    </div>
                    <div class="field">
                        <?php

                        $param = array('name'=>'respToAuthority','type'=>'radio','value'=>POOR);
                        $param['id'] = "poor";
                        FormFields::input($param);
                        ?>
                        <label for="poor">Poor</label>
                    </div>
                    <div class="field">
                        <?php

                        $param = array('name'=>'respToAuthority','type'=>'radio','value'=>REBELLIOUS);
                        $param['id'] = "rebellious";
                        FormFields::input($param);
                        ?>
                        <label for="rebellious">Rebellious</label>
                    </div>
                </div>

            </div>

            <div class="field">
                <h4 class="ui header">4. Has the applicant had any problem in previous Bible School experiences?</h4>
                <div class="inline fields">
                    <div class="field">
                        <?php

                        $param = array('name'=>'preProblem','type'=>'radio','value'=>ONLY_YES);
                        $param['id'] = "pre-yes";
                        $param['class'] = "pre-problem";
                        FormFields::input($param);
                        ?>
                        <label for="pre-yes">Yes</label>
                    </div>
                    <div class="field">
                        <?php

                        $param = array('name'=>'preProblem','type'=>'radio','value'=>ONLY_NO);
                        $param['id'] = "prev-no";
                        $param['class'] = "pre-problem";
                        FormFields::input($param);
                        ?>
                        <label for="prev-no">No</label>
                    </div>
                </div>
                <div class="field dont-show pre-prob-location">
                    <label>If yes, where?</label>
                    <?php

                    $param = array('name'=>'problemLocation','type'=>'text');
                    FormFields::input($param);
                    ?>
                </div>
                <div class="field dont-show pre-prob-location">
                    <label>What is the applicant's attitude toward that experience now?</label>
                    <?php

                    $param = array('name'=>'currentAttitude','type'=>'text');
                    FormFields::input($param);
                    ?>
                </div>
            </div>

            <div class="field">
                <h4 class="ui header">5. What type of a person would the applicant tend to be?</h4>
                <div class="inline fields">
                    <div class="field">
                        <?php

                        $param = array('name'=>'tendToBe','type'=>'radio','value'=>LEADER);
                        $param['id'] = "leader";
                        FormFields::input($param);
                        ?>
                        <label for="leader">Leader</label>
                    </div>
                    <div class="field">
                        <?php

                        $param = array('name'=>'tendToBe','type'=>'radio','value'=>FOLLOWER);
                        $param['id'] = "follower";
                        FormFields::input($param);
                        ?>
                        <label for="follower">Follower</label>
                    </div>
                    <div class="field">
                        <?php

                        $param = array('name'=>'tendToBe','type'=>'radio','value'=>LONER);
                        $param['id'] = "loner";
                        FormFields::input($param);
                        ?>
                        <label for="loner">Loner</label>
                    </div>
                </div>
            </div>


            <div class="field">
                <h4 class="ui header">6. How would you describe your relationship with the applicant?</h4>
                <div class="inline fields">
                    <div class="field">
                        <?php

                        $param = array('name'=>'relWithApplicant','type'=>'radio','value'=>ONLYGOOD);
                        $param['id'] = "rel-good";
                        FormFields::input($param);
                        ?>
                        <label for="rel-good">Good</label>
                    </div>
                    <div class="field">
                        <?php

                        $param = array('name'=>'relWithApplicant','type'=>'radio','value'=>IMPROVING);
                        $param['id'] = "rel-imp";
                        FormFields::input($param);
                        ?>
                        <label for="rel-imp">Improving</label>
                    </div>
                    <div class="field">
                        <?php

                        $param = array('name'=>'relWithApplicant','type'=>'radio','value'=>STRAINED);
                        $param['id'] = "rel-strained";
                        FormFields::input($param);
                        ?>
                        <label for="rel-strained">Strained</label>
                    </div>
                    <div class="field">
                        <?php

                        $param = array('name'=>'relWithApplicant','type'=>'radio','value'=>DETERIORATING);
                        $param['id'] = "rel-dete";
                        FormFields::input($param);
                        ?>
                        <label for="rel-dete">Deteriorating</label>
                    </div>
                    <div class="field">
                        <?php

                        $param = array('name'=>'relWithApplicant','type'=>'radio','value'=>DISTANT);
                        $param['id'] = "rel-distant";
                        FormFields::input($param);
                        ?>
                        <label for="rel-distant">Distant</label>
                    </div>
                </div>
            </div>

            <div class="field">
                <h4 class="ui header">7. If the applicant's normal lifestyle is different than what is required for admission at Maranatha, do you feel the applicant will cheerfully make the necessary changes in order to attend there?</h4>
                <div class="inline fields">
                    <div class="field">
                        <?php

                        $param = array('name'=>'lifeStyle','type'=>'radio','value'=>ONLY_YES);
                        $param['id'] = "life-yes";
                        FormFields::input($param);
                        ?>
                        <label for="life-yes">Yes</label>
                    </div>
                    <div class="field">
                        <?php

                        $param = array('name'=>'lifeStyle','type'=>'radio','value'=>ONLY_NO);
                        $param['id'] = "life-no";
                        FormFields::input($param);
                        ?>
                        <label for="life-no">No</label>
                    </div>
                </div>
            </div>

            <div class="field">
                <div class="">
                    <h4 class="ui header">8. Do you recommend this applicant as a student at Maranatha Bible School?</h4>
                    <div class="inline fields">

                        <div class="field">

                            <?php
                            //                            $ext = ['placeholder'=>'Date of Birth'];
                            $param = array('name'=>'applicationApproval','value'=>ONLY_YES,'type'=>'radio');
                            $param['id'] = "app-yes";
                            FormFields::input($param);
                            ?>
                            <label for="app-yes">Yes</label>

                        </div>
                        <div class="field">

                            <?php
                            //                            $ext = ['placeholder'=>'Date of Birth'];
                            $param = array('name'=>'applicationApproval','value'=>ONLY_YES,'type'=>'radio');
                            $param['id'] = "app-no";
                            FormFields::input($param);
                            ?>
                            <label for="app-no">No</label>


                        </div>
                    </div>


                </div>

            </div>

            <div class="field">
                <div class="">
                    <h4 class="ui header">9. Will you review the MBS handbook with the applicant, and help them to understand their responsibility to cooperate and contribute positively toward the work of the Bible School?</h4>
                    <div class="inline fields">

                        <div class="field">

                            <?php
                            //                            $ext = ['placeholder'=>'Date of Birth'];
                            $param = array('name'=>'mbsreview','value'=>ONLY_YES,'type'=>'radio');
                            $param['id'] = "review-app-yes";
                            FormFields::input($param);
                            ?>
                            <label for="review-app-yes">Yes</label>

                        </div>
                        <div class="field">

                            <?php
                            //                            $ext = ['placeholder'=>'Date of Birth'];
                            $param = array('name'=>'mbsreview','value'=>ONLY_NO,'type'=>'radio');
                            $param['id'] = "review-app-no";
                            FormFields::input($param);
                            ?>
                            <label for="review-app-no">No</label>


                        </div>
                    </div>


                </div>

            </div>

            <div class="field">
                <h4 class="ui header">10. Additional comments</h4>
                <div class="field">

                    <?php
                    $ext['placeholder'] = "Additional Comments";
                    $param = array('name'=>'additionalComments','type'=>'text','ext'=>$ext);
                    FormFields::input($param);
                    ?>
                </div>

            </div>



            <!--    <button class="ui button" >Previous</button>-->
            <button class="ui button send-to-school" disabled="disabled" data-action="submitPastorInfo">Submit</button>
            <!--    <button class="ui button" >Save and Continue Later</button>-->
        </form>
        <div class="ui message dont-show pastor-info-resp">
            <i class="close icon"></i>
            <div class="header">

            </div>
            <p class="response"></p>
        </div>
    </div>
    <?php
    wp_enqueue_script('main-js');
    wp_enqueue_script("pastor-form");
    wp_enqueue_style( 'jquery-ui' );
}
?>

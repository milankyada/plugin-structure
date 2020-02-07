<?php
if(!empty($args['token'])){
    $result = PluginFunc::studentInfoForBackend($args['token']);

    if($result['response']){
        $message = json_decode(base64_decode($result['message']),true);
        $data = (!empty($message)) ? $message : [];
    }else{
        echo $result['message'];
    }

}
?>

<div class="front-end-forms">
    <input type="hidden" id="ajax-url" value="<?= admin_url('admin-ajax.php');?>">
    <form class="ui form" name="studentInfo">
        <div class="fields">
            <div class="field">
                <label>Student Application Date</label>
                <?php
                $ext = ['placeholder'=>'Application Date'];
                $ext['readonly'] = "readonly";
                $param = array('name'=>'appDate','ext'=>$ext);
                $param['class'] = "datepicker";
                if(!empty($data[$param['name']]))
                    $param['value'] = @date("Y-m-d",strtotime($data[$param['name']]));
                FormFields::input($param);
                ?>
            </div>

        </div>

        <!-- STUDENT NAME -->
        <div class="field">
            <h4 class="ui header">Student Name</h4>
            <div class="two fields">
                <div class="field">
                    <?php
                    $ext = ['placeholder'=>'First Name'];
                    $ext['readonly'] = "readonly";
                    $param = array('name'=>'firstName','ext'=>$ext);
                    $param['value'] = @$data[$param['name']];
                    FormFields::input($param);
                    ?>

                    <?php
                    global $wp;
                    $param = array('name'=>'pageURL','type'=>'hidden','value'=>home_url( $wp->request ));
                    FormFields::input($param);
                    ?>
                </div>
                <div class="field">
                    <?php
                    $ext = ['placeholder'=>'Last Name'];
                    $ext['readonly'] = "readonly";
                    $param = array('name'=>'lastName','ext'=>$ext);
                    $param['value'] = @$data[$param['name']];
                    FormFields::input($param);
                    ?>
                </div>
            </div>
        </div>

        <!-- PARENTS NAME -->
        <div class="field">
            <h4 class="ui header">Names of Parents *</h4>
            <div class="two fields">
                <div class="field">
                    <?php
                    $ext = ['placeholder'=>'First Name'];
                    $ext['readonly'] = "readonly";
                    $param = array('name'=>'pFirstName','ext'=>$ext);
                    $param['value'] = @$data[$param['name']];
                    FormFields::input($param);
                    ?>
                </div>
                <div class="field">
                    <?php
                    $ext = ['placeholder'=>'Last Name'];
                    $ext['readonly'] = "readonly";
                    $param = array('name'=>'pLastName','ext'=>$ext);
                    $param['value'] = @$data[$param['name']];
                    FormFields::input($param);
                    ?>
                </div>
            </div>
        </div>

        <!-- PASTOR NAME -->
        <div class="field">
            <h4 class="ui header">Name of Pastor*</h4>
            <div class="">
                <div class="field">
                    <?php
                    $ext = ['placeholder'=>'Pastor Name'];
                    $ext['readonly'] = "readonly";
                    $param = array('name'=>'pastorName','ext'=>$ext);
                    $param['value'] = @$data[$param['name']];
                    FormFields::input($param);
                    ?>
                </div>
            </div>
        </div>

        <!-- STUDENT ADDRESS -->
        <div class="field">
            <h4 class="ui header">Student Address</h4>

            <div class="field">
                <?php
                $ext = ['placeholder'=>'Street Address'];
                $ext['readonly'] = "readonly";
                $param = array('name'=>'line1','ext'=>$ext);
                $param['value'] = @$data[$param['name']];
                FormFields::input($param);
                ?>
            </div>


            <div class="field">
                <?php
                $ext = ['placeholder'=>'Address Line 2'];
                $ext['readonly'] = "readonly";
                $param = array('name'=>'line2','ext'=>$ext);
                $param['value'] = @$data[$param['name']];
                FormFields::input($param);
                ?>
            </div>

            <div class="two fields">
                <div class="field">
                    <?php
                    $ext = ['placeholder'=>'City'];
                    $ext['readonly'] = "readonly";
                    $param = array('name'=>'city','ext'=>$ext);
                    $param['value'] = @$data[$param['name']];
                    FormFields::input($param);
                    ?>
                </div>
                <div class="field">
                    <?php
                    $ext = ['placeholder'=>'State / Province / Region'];
                    $ext['readonly'] = "readonly";
                    $param = array('name'=>'state','ext'=>$ext);
                    $param['value'] = @$data[$param['name']];
                    FormFields::input($param);
                    ?>
                </div>
            </div>
            <div class="two fields">
                <div class="field">
                    <?php
                    $ext = ['placeholder'=>'ZIP / Postal Code'];
                    $ext['readonly'] = "readonly";
                    $param = array('name'=>'zip','ext'=>$ext);
                    $param['value'] = @$data[$param['name']];
                    FormFields::input($param);
                    ?>
                </div>
                <div class="field">
                    <?php
                    $ext = ['placeholder'=>'Country'];
                    $ext['readonly'] = "readonly";
                    $param = array('name'=>'state','ext'=>$ext,'firstSelect'=>'Select Country');
                    $param['selected'] = @$data[$param['name']];
                    $param['options'] = FormFields::listOfCountries();
                    FormFields::select($param);
                    ?>
                </div>
            </div>
        </div>

        <!-- STUDENT PHONE-->
        <div class="field">
            <div class="two fields">
                <div class="field">
                    <label>Student Phone</label>
                    <?php
                    $ext = ['placeholder'=>'Student Phone'];
                    $ext['readonly'] = "readonly";
                    $param = array('name'=>'phone','ext'=>$ext);
                    $param['value'] = @$data[$param['name']];
                    FormFields::input($param);
                    ?>
                </div>

                <!-- STUDENT EMAIL-->
                <div class="field">
                    <label>Student Email</label>
                    <?php
                    $ext = ['placeholder'=>'Student Email'];
                    $ext['readonly'] = "readonly";
                    $param = array('name'=>'email','type'=>'email','ext'=>$ext);
                    $param['value'] = @$data[$param['name']];
                    FormFields::input($param);
                    ?>
                </div>
            </div>
        </div>



        <!-- HOME CONGREGATION-->
        <div class="field">
            <div class="fields">
                <div class="twelve wide field">
                    <label>Name of Home Church</label>
                    <?php
                    $ext = ['placeholder'=>'Home Congregation'];
                    $ext['readonly'] = "readonly";
                    $param = array('name'=>'homeCongregation','ext'=>$ext);
                    $param['value'] = @$data[$param['name']];
                    FormFields::input($param);
                    ?>
                </div>

                <!-- DATE OF BIRTH-->
                <div class="four wide field">
                    <label>Date of Birth</label>
                    <?php
                    $ext = ['placeholder'=>'Date of Birth'];
                    $ext['readonly'] = "readonly";
                    $param = array('name'=>'dob','ext'=>$ext);
                    $param['class'] = "dob-dt";
                    if(!empty($data[$param['name']]))
                        $param['value'] = @date("Y-m-d",strtotime($data[$param['name']]));
                    FormFields::input($param);
                    ?>
                </div>
            </div>
        </div>

        <!-- GENDER -->
        <div class="field">
            <div class="fields">
                <div class="field">
                    <h4 class="ui header">Gender</h4>
                    <div class="inline fields">
                        <div class="field">
                            <div class="ui radio checkbox">
                                <?php
                                $ext['readonly'] = "readonly";
                                $param = array('name'=>'gender','value'=>'female','type'=>'radio');
                                $param['checked'] = @$data[$param['name']];
                                FormFields::input($param);
                                ?>
                                <label for="female">Female</label>
                            </div>
                        </div>
                        <div class="field">
                            <div class="ui radio checkbox">
                                <?php
                                $ext['readonly'] = "readonly";
                                $param = array('name'=>'gender','value'=>'male','type'=>'radio');
                                $param['checked'] = @$data[$param['name']];
                                FormFields::input($param);
                                ?>
                                <label for="male">Male</label>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <h4 class="ui header">Age (at the time of application)*</h4>
                    <?php

                    $ext = ['placeholder'=>'Age'];
                    $ext['readonly'] = "readonly";
                    $param = array('name'=>'age','ext'=>$ext);
                    $param['value'] = @$data[$param['name']];
                    FormFields::input($param);
                    ?>
                </div>
            </div>


        </div>
        <!--
        =====================================
        -->
        <!-- OTHER INFO -->

        <div class="field">
            <h4 class="ui dividing header">Other Info</h4>
            <div class="two fields">
                <!-- MEDICAL CONDITIONS -->
                <div class="field">
                    <h4 class="ui header">Do you have any medical condition that administrative staff should be aware of?</h4>
                    <?php
                    $ext = ['placeholder'=>'Medical Conditions'];
                    $ext['readonly'] = "readonly";
                    $param = array('name'=>'medicalConditions','ext'=>$ext);
                    $param['value'] = @$data[$param['name']];
                    FormFields::input($param);
                    ?>
                </div>
                <!-- CHRISTIAN TIME -->
                <div class="field">
                    <h4 class="ui header">How long have you been a Christian?</h4>
                    <?php
                    $ext['readonly'] = "readonly";
                    $param = array('name'=>'timeBnChristian','firstSelect'=>'How long?');
                    $param['selected'] = @$data[$param['name']];
                    $param['options'] = FormFields::timeBeingChristian();
                    FormFields::select($param);
                    ?>
                </div>
            </div>
        </div>

        <div class="field">
            <div class="fields">
                <!-- BIBLE SCHOOL -->
                <div class="field">
                    <h4 class="ui header">Have you attended Bible School before?</h4>
                    <div class="inline fields">
                        <div class="field">
                            <div class="ui radio checkbox">
                                <?php
                                $ext['readonly'] = "readonly";
                                $param = array('name'=>'attendBibleSchool','value'=>'yes','type'=>'radio');
                                $param['checked'] = @$data[$param['name']];
                                FormFields::input($param);
                                ?>
                                <label for="yes">Yes</label>
                            </div>
                        </div>
                        <div class="field">
                            <div class="ui radio checkbox">
                                <?php
                                $ext['readonly'] = "readonly";
                                $param = array('name'=>'attendBibleSchool','value'=>'no','type'=>'radio');
                                $param['checked'] = @$data[$param['name']];
                                FormFields::input($param);
                                ?>
                                <label for="no">No</label>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- BIBLE SCHOOL WHERE & WHEN -->
                <div class="field">
                    <label>If yes, where</label>
                    <?php

                    $param = array('name'=>'bibleSchoolWhere');
                    FormFields::input($param);
                    ?>
                </div>
                <div class="field">
                    <label> and when?</label>
                    <?php

                    $param = array('name'=>'bibleSchoolWhen');
                    FormFields::input($param);
                    ?>
                </div>
            </div>
        </div>

        <div class="field">
            <h4 class="ui header">Check Desired Terms</h4>
            <?php
            $terms = get_posts(['post_type'=>'terms','post_status'=>'publish','posts_per_page'=>-1,'orderby'=>'date','order'=>'ASC']);
            if(!empty($terms)){
                foreach ($terms as $term){
                    $show = (!empty(get_field('show_term',$term->ID))) ? strtolower(get_field('show_term',$term->ID)) : 'no';
                    if($show=='yes'){
                        ?>
                        <div class="field">
                            <div class="ui checkbox ">
                                <?php
                                $param = array('name'=>'desiredTerms[]','value'=>$term->ID,'type'=>'checkbox');
                                $param['id'] = $term->ID;

                                $choices = @explode(",",@$data['desiredTerms']);
                                $param['checked'] = (in_array($param['value'],$choices)) ? $param['value'] : "";
                                FormFields::input($param);
                                ?>
                                <label for="<?= $term->ID?>" class="hover-pointer"><?= $term->post_title ?></label>
                            </div>
                        </div>
            <?php
                    }

                }
            }
            ?>

            <div class="fields">


                <div class="field">
                    <h4 class="ui header">I have read the MBS handbook, and I agree to support the standards and regulations while I attend. I intend to make a positive contribution to the work of the Bible School.</h4>
                    <div class="inline fields">
                        <!-- DESIRED ITEMS -->
                        <div class="field">
                            <div class="ui radio checkbox">
                                <?php
                                $param = array('name'=>'agreeConditions','value'=>'Yes','type'=>'radio');
                                $param['checked'] = @$data[$param['name']];
                                $param['id'] = 'cond-yes';
                                FormFields::input($param);
                                ?>
                                <label for="cond-yes">Yes</label>
                            </div>
                            <div class="ui radio checkbox">
                                <?php
                                $param = array('name'=>'agreeConditions','value'=>'No','type'=>'radio');
                                $param['checked'] = @$data[$param['name']];
                                $param['id'] = 'cond-no';
                                FormFields::input($param);
                                ?>
                                <label for="cond-no">No</label>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <div class="field">
                <h4 class="ui header">We value your parent's involvement and support in your decision to attend Maranatha Bible School. Please provide their email address so we can contact them with a copy of our handbook.</h4>
                <!-- DESIRED ITEMS -->
                <div class="field">
                    <?php
                    $ext = ['placeholder'=>"Parent's email address"];
                    $param = array('name'=>'parentEmailAddress','ext'=>$ext);
                    $param['value'] = @$data[$param['name']];
                    FormFields::input($param);
                    ?>
                </div>
            </div>
        </div>

        <div class="field">
            <div class="">
                <h4 class="ui header">As part of our registration process, we require a reference from your pastor. This can be completed online, or you can download and print the form for him. Your application cannot be processed until this reference has been submitted. *</h4>
                <div class="field">
                    <div class="ui radio checkbox">
                        <?php
                        $param = array('name'=>'contactBy','value'=>'emailLink','type'=>'radio');
                        $param['checked'] = @$data[$param['name']];
                        $param['class'] = "send-to-pastor";
                        $param['id'] = "send-yes";
                        FormFields::input($param);
                        ?>
                        <label for="send-yes">Email a link to my pastor</label>
                        <?= $param['value'] = @$data['pastorEmail'];?>
                    </div>
                </div>
                <div class="field">
                    <div class="ui radio checkbox">
                        <?php
                        $param = array('name'=>'contactBy','value'=>'downloadLink','type'=>'radio');
                        $param['checked'] = @$data[$param['name']];
                        $param['class'] = "send-to-pastor";
                        $param['id'] = "send-no";
                        FormFields::input($param);
                        ?>
                        <label for="send-no">Download and Print</label>

                    </div>

                </div>
                <div class="field pastor-email">
                    &nbsp;&nbsp;    <a class="download-form dont-show"><span class="download-link hover-pointer">Download Form</span></a>
                    <?php
                    $ext = ['placeholder'=>'Pastor Email'];
                    $param = array('name'=>'pastorEmail','ext'=>$ext);
                    $param['value'] = @$data[$param['name']];
                    $param['class'] = "dont-show";
                    FormFields::input($param);
                    ?>
                </div>

            </div>
        </div>


        <!-- CONDITION AGREEMENT -->
        <!-- PARENT CONSENT -->
<!--        <button class="ui black button submit-info" type="submit" data-action="completeStep1">Submit</button>-->
<!--        <button class="ui grey button save-continue" type="submit" data-action="continueLater">Save and Continue Later</button>-->
    </form>
    <div class="ui message dont-show student-info-resp">
        <i class="close icon"></i>
        <div class="header">

        </div>
        <p class="response"></p>
    </div>

</div>


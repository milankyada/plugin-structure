<?php


class FrontEndShortcodes
{

    public function __construct()
    {
        add_shortcode("Load_StudentInformation_Form",array($this,'loadStudentInformationForm'));
        add_shortcode("Load_PastorInformation_Form",array($this,'loadPastorInformationForm'));
    }

    public function loadStudentInformationForm(){

        require_once (EX_PLUGIN_DIR.'/views/forms/student.php');
    }


}
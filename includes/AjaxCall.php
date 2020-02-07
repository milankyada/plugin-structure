<?php


use center\models\SelectedCourses;
use center\models\StudentInfo;

class AjaxCall {
	public function __construct() {


        add_action("wp_ajax_uploadHandBook",array($this,"uploadHandBook"));
        add_action("wp_ajax_testAttachment",array($this,"testAttachment"));
        add_action("wp_ajax_askAnotherPortrait",array($this,"askAnotherPortrait"));


        //TODO: needs to be implemented

	}

	protected function checkData($param)
	{
		$request = is_array($param) ? [] : "";
		if (is_array($param) && count($param) > 0) {
			foreach ($param as $k => $item) {
				$request[$k] = sanitize_text_field($item);
			}
		} else {
			$request = sanitize_text_field($param);
		}
		return $request;
	}


    /**
     * Upload Student Portrait during new registration process and also for already registered students.
     * TODO : Write code to upload portrait for already registered student.
     */
    public function portraitUpload(){
        try {
            $path = EX_FS_IMG;
            $fileNamePrefix = strtotime('now');
            $fileName = "";
            $fileUploadError = "";
            $studentModel = new \center\models\StudentInfo();
            $stMeta = new \center\models\StudentMetaInfo();
            $_REQUEST = $this->checkData($_REQUEST);
            $studentEmail = $_REQUEST['studentEmail'];
            $where = [];
            $where[] = ['email',$studentEmail,'='];
            $result = $studentModel->getSingleStudentMeta($where);
            if(empty($result['total']) && $result['total']<1)
                throw new Exception("No student has registered with $studentEmail email");

            $student = $result['result'][0];

            $stdID = $student['id'];
            $fName = $student['firstName'];
            $isPortraitExist = false;
            if(!empty($student['studentPortrait']))
                $isPortraitExist = true;

            if(!empty($_FILES["fileUploads"]["name"])){
                $errorFlag = false;

                $fileName = $fName.'-'.$stdID.'-'.$fileNamePrefix.'_'.basename($_FILES["fileUploads"]["name"]);

                if (move_uploaded_file($_FILES["fileUploads"]["tmp_name"], $path . '/' . $fileName)) {

                    $meta = [];
                    $meta['stdId'] = $stdID;
                    $meta['meta_key'] = 'studentPortrait';
                    $meta['meta_value'] = $fileName;
                    $metaFormat = ['%d','%s','%s'];

                    // If portrait not exist.
                    if(!$isPortraitExist){

                        $stMeta->insert($meta,$metaFormat);

                    }
                    else{
                        //If portrait already exist.
                        $mWhere = ['stdId'=>$stdID,'meta_key'=>'studentPortrait'];
                        unset($meta['stdId']);
                        unset($meta['studentPortrait']);
                        $mWhereFormat = ['%d','%s'];
                        $metaFormat = ['%s'];
                        $stMeta->updateMetaInfo($meta,$mWhere,$metaFormat,$mWhereFormat);
                    }


                    $meta = [];
                    $meta['meta_value'] = "";
                    $mWhere = ['stdId'=>$stdID,'meta_key'=>'portraitToken'];
                    $metaFormat = ['%s'];
                    $mWhereFormat = ['%d','%s'];
                    $stMeta->updateMetaInfo($meta,$mWhere,$metaFormat,$mWhereFormat);
                    wp_send_json(['response'=>true,'message'=>"Portrait has been uploaded! Thank you."]);
                }
            }
            wp_send_json(['response'=>true,'message'=>"You'll be asked to upload portrait later through the email."]);

        }catch (Exception $e){
            wp_send_json(['response'=>false,'message'=>$e->getMessage()]);
        }

    }

    /**
     * Load modal content from Modal.php
     */
    public function loadModalContent(){
	    try{

            $_REQUEST = $this->checkData($_REQUEST);
            if(empty($_REQUEST['typeOfContent']))
                throw new Exception(UNKNOWN_ERROR_WITH_REQUEST);

            $type = $_REQUEST['typeOfContent'];
            if($type == "loadMessage"){
                echo $_REQUEST['message'];
            }
            else if($type == "inForm"){

                $pr = $_REQUEST['preloadcontent'];
//                $preloadedContent = !empty($_REQUEST['preloadcontent']) ? unserialize(base64_decode($_REQUEST['preloadcontent'])) : [];
                $content['tokenURL'] = $_REQUEST['tokenURL'];
                $content['name'] = $_REQUEST['name'];
                $content = $_REQUEST;
                $target = $_REQUEST['target'];
                if($target == "saveNContinue"){

                    Modal::saveNdContinuePopup($content);
                }
                if($target == "loadPastorResponse"){

                    Modal::loadPastorResponse($content);
                }
                if($target == "loadFileUpload"){

                    Modal::loadFileUpload($content);
                }
                if($target == "loadFilePreview"){

                    Modal::loadFilePreview($content);
                }
                if($target == "loadNewEmail"){

                    Modal::loadNewEmail($content);
                }
                if($target == "loadReceiptView"){

                    Modal::loadReceiptView($content);
                }
                if($target == "loadMoreInfo"){

                    Modal::loadMoreInfo($content);
                }
            }
            else{
                echo "Nothing to show";
            }
        }catch (Exception $e){
	        echo $e->getMessage();
        }

        wp_die();
    }


    /**
     * Upload multiple files
     */
    public function uploadFiles()
    {
        try{
            $_REQUEST = $this->checkData($_REQUEST);

            /*$dirModel = new \EditInventory\models\DirectoryModel();*/


            $path = EX_FS_IMG;
            $errorFlag = false;
            $caughtError = "";
            $resp = "";
            $userId = get_current_user_id();
            $time = strtotime('now');
            $studentMetaModel = new \center\models\StudentMetaInfo();
            $studentModel = new \center\models\StudentInfo();
            $feesModel = new \center\models\FeesPayment();

            if (!isset($_FILES['fileUploads']) && empty($_FILES['fileUploads']) && $_FILES["fileUploads"]['size']<1) {
                throw new Exception(FILE_MISSING);
            }


            if ($_FILES["fileUploads"]["error"] > 0) {
                $errorFlag = true;
                $caughtError = "File " . $_FILES["fileUploads"]["error"];
            } else {

                if (file_exists($path . '/' . $time.'_'.$_FILES["fileUploads"]["name"])) {
                    $errorFlag = true;
                    $caughtError = 'File already exists. ';
                } else {

                    $name = $time.'_'.basename($_FILES["fileUploads"]["name"]);
                    if (!move_uploaded_file($_FILES["fileUploads"]["tmp_name"], $path . '/' . $name)) {
                        $errorFlag = true;
                        $caughtError = "Upload Failed";
                    }

                    if(!empty($_REQUEST['part'])){
                        /**
                         * For fees payment (Cheque upload)
                         */

                        $course = (!empty($_REQUEST['course'])) ? explode(",",$_REQUEST['course']) : [];
                        $chequeNumMetaKey = ($_REQUEST['part']=="first") ? "chequeNumberFirst" : "chequeNumberSecond";
                        $stdId = $_REQUEST['studentId'];

                        $metadata['meta_value'] = $name;
                        $where = ['stdId'=>$stdId,'meta_key'=>$chequeNumMetaKey];
                        $format = ["%s"];
                        $where_format = ['%d',"%s"];
                        $studentMetaModel->updateMetaInfo($metadata,$where,$format,$where_format);
                        if(!empty($course)){
                            foreach ($course as $c){

                                $part = ($_REQUEST['part']=="first") ? "I" : "II";



                                $feesData['paymentId'] = $name.'-byCheque';
                                $feesData['formNumber'] = $_REQUEST['formNumber'];
                                $feesData['customerEmail'] = $_REQUEST['emailAddress'];
                                $feesData['amount'] = (double)$_REQUEST['amount'];
                                $feesData['currencyCode'] = "USD";
                                $feesData['isSuccessful'] = true;
                                $feesData['part'] = $part;
                                $feesData['stdId'] = $stdId;
                                $feesData['course'] = $c;
                                $feesFormat = ['%s','%s','%s','%d','%s','%s','%s','%d','%s'];

                                if($feesModel->insert($feesData,$feesFormat))
                                    $resp = "File successfully uploaded!";
                                else
                                    $errorFlag = true;
                            }
                        }



                    }else{
                        /**
                         * For Pastor Response Upload
                         */
                        $data['hardCopyFile'] = $name;//$_FILES["fileUploads"]["name"];
                        $stdId = $_REQUEST['studentId'];
                        $token = $_REQUEST['token'];

                        $data['uploadedBy'] = $userId;
                        $metadata['fileName'] = $name;//"hardCopyFile";

                        $where = ['id'=>$stdId];
                        $where_format = ['%d'];
                        $format = ["%s"];
                        if($studentModel->updateStudentInfo($metadata,$where,$format,$where_format))
                            $resp = "File successfully uploaded!";
                        else
                            $errorFlag = true;
                    }

                }

            }
            if (!$errorFlag) {
                wp_send_json(['response' => true, 'message' => $resp]);
            } else {
                wp_send_json(['response' => false, 'message' => $caughtError]);
            }

        }catch (Exception $e){
            wp_send_json(['response' => false, 'message' => $e->getMessage()]);
        }

        //Not required here but just keep it for now.
        wp_die();
    }

    /**
     * Upload handbook which we are later using in
     */
    public function uploadHandBook(){
        try{
            $_REQUEST = $this->checkData($_REQUEST);
            if(empty($_REQUEST['handBookURL']))
                throw new Exception("Handbook not uploaded");

            if(!get_option("handBookURL")){
                add_option("handBookURL",$_REQUEST['handBookURL']);
                add_option("fileName",$_REQUEST['fileName']);
            }else{
                update_option("handBookURL",$_REQUEST['handBookURL']);
                update_option("fileName",$_REQUEST['fileName']);
            }

            wp_send_json(['response' => true, 'message' => "Handbook uploaded!"]);
        }catch (Exception $e){
            wp_send_json(['response' => false, 'message' => $e->getMessage()]);
        }
    }

    public function askAnotherPortrait(){
        try{
            $_REQUEST = $this->checkData($_REQUEST);
            $id = $_REQUEST['id'];
            $email = $_REQUEST['email'];
            $appNumber = $_REQUEST['formNumber'];
            $subject = $_REQUEST['subject'];
            $token = urlencode(base64_encode($id.'-'.$email.'-'.$appNumber.'-'.$subject));

            $stMeta = new \center\models\StudentMetaInfo();
            $studentModel = new StudentInfo();

            $sWhere = [];
            $sWhere[] = ['id',$id,'='];
            $sWhere[] = ['email',$email,'='];
            $studentRecord = $studentModel->getSingleStudentMeta($sWhere);
            $studentResult = $studentRecord['result'][0];
            $meta = [];
            $meta['stdId'] = $id;
            $meta['meta_key'] = 'portraitToken';
            $meta['meta_value'] = $token;

            if(!empty($studentRecord['total']) && empty($studentRecord['result'][0]['portraitToken'])){
                $metaFormat = ['%d','%s','%s'];
                $stMeta->insert($meta,$metaFormat);
            }else{
                unset($meta['stdId']);
                unset($meta['meta_key']);
                $uwhere = ['stdId'=>$id,'meta_key'=>'portraitToken'];
                $uWhereFormat = ['%d'];
                $metaFormat = ['%s'];
                $stMeta->updateMetaInfo($meta,$uwhere,$metaFormat,$uWhereFormat);
            }
            $studentResult['link'] = add_query_arg(array('token'=>$token),get_permalink(815));

            if(!NotificationsHandler::askForPortrait( $studentResult['link'],$email,$studentResult))
                throw new Exception(EMAIL_ERROR);
            wp_send_json(['response' => true, 'message' => "An email has been sent to the student to upload a portrait for the submitted application!"]);

        }catch (Exception $e){
            wp_send_json(['response' => false, 'message' => $e->getMessage()]);
        }
    }

    public function testAttachment(){
        NotificationsHandler::pastorResponse("Bhakti Swami",'3762681841','milankyada@gmail.com');
    }


}
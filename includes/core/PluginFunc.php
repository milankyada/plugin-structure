<?php


class PluginFunc
{

    protected static function checkData($param)
    {
        $request = is_array($param) ? [] : "";
        if (is_array($param) && count($param) > 0) {
            foreach ($param as $k => $item) {
                $request[$k] = esc_html(esc_attr($item));
            }
        } else {
            $request = esc_html(esc_attr($param));
        }
        return $request;
    }

    public static function formNumber(){
        return abs(crc32(uniqid(microtime(),true)));
    }

    public static function checkTokenStatus($token = ''){
        try{
            if(empty($token))
                throw new Exception(NO_TOKEN);


            $_REQUEST = self::checkData($_REQUEST);
            $tokenModel = new \center\models\Token();
            $studentInfo = new \center\models\StudentInfoSNC();
            $studentMeta = new \center\models\StudentMetaInfoSNC();

            $token = $_REQUEST['token'];

            $where[] = ['token',$token,'='];
            $result = $tokenModel->getTokenByField($where);
            $message = NO_RECORD;
            if($result['total']>0){

                $message = $result['result'][0]['token'];
                $studentWhere[] = ['formToken',$message,'='];

                $record = $studentInfo->getStudentInfoByField($studentWhere);

                if($record['total']>0){
                    $response = $record['result'][0];
                    $stInfo = $record['result'][0];
                    $allInfoWhere[] = ['stdId',$stInfo['id'],'='];

                    $allInfo = $studentMeta->getMetaInfoByField($allInfoWhere);
                    $allInfo = $allInfo['result'];

                    $metaKeys = array_column($allInfo,'meta_key');
                    $metaValues = array_column($allInfo,'meta_value');
                    $resultant = array_combine($metaKeys,$metaValues);
                    $response = array_merge($response,$resultant);

                    $message = base64_encode(json_encode($response));
                }
            }



            return ['response'=>true,'message'=>$message];
        }catch (Exception $e){
            return ['response'=>false,'message'=>$e->getMessage()];
        }
    }

    public static function studentInfoForBackend($token = ''){
        try{
            if(empty($token))
                throw new Exception(NO_TOKEN);


            $_REQUEST = self::checkData($_REQUEST);
            $tokenModel = new \center\models\Token();
            $studentInfo = new \center\models\StudentInfo();
            $studentMeta = new \center\models\StudentMetaInfo();

//            $token = $token;

            $where[] = ['token',$token,'='];
            $result = $tokenModel->getTokenByField($where);
            $message = NO_RECORD;
            if($result['total']>0){

                $message = $result['result'][0]['token'];
                $studentWhere[] = ['formToken',$message,'='];

                $record = $studentInfo->getStudentInfoByField($studentWhere);

                if($record['total']>0){
                    $response = $record['result'][0];
                    $stInfo = $record['result'][0];
                    $allInfoWhere[] = ['stdId',$stInfo['id'],'='];

                    $allInfo = $studentMeta->getMetaInfoByField($allInfoWhere);
                    $allInfo = $allInfo['result'];

                    $metaKeys = array_column($allInfo,'meta_key');
                    $metaValues = array_column($allInfo,'meta_value');
                    $resultant = array_combine($metaKeys,$metaValues);
                    $response = array_merge($response,$resultant);

                    $message = base64_encode(json_encode($response));
                }
            }

            return ['response'=>true,'message'=>$message];
        }catch (Exception $e){
            return ['response'=>false,'message'=>$e->getMessage()];
        }
    }

    public static function checkTokenStatusForPastorForm($token = ''){
        try{
            if(empty($token))
                throw new Exception(NO_TOKEN);


            $_REQUEST = self::checkData($_REQUEST);
            $tokenModel = new \center\models\Token();
            $studentInfo = new \center\models\StudentInfo();
            $studentMeta = new \center\models\StudentMetaInfo();
            $pasterModel = new \center\models\PastorInfo();

            // if came through ajax
            if(!empty($_REQUEST['typeOfContent']))
                $token = ($_REQUEST['token']);
            else
                $token = urlencode($_REQUEST['token']);
            $where = [];
            $where[] = ['token',$token,'='];
            $result = $tokenModel->getTokenByField($where);
            $message = NO_RECORD;
            /*echo '<pre>';
            print_r($_REQUEST);
            print_r($where);
            echo '</pre>';*/
            if($result['total']>0){

                $message = $result['result'][0]['token'];
                $studentWhere[] = ['formToken',$message,'='];


                $record = $studentInfo->getStudentInfoByField($studentWhere);


                if($record['total']>0){
                    $response = $record['result'][0];
                    $stInfo = $record['result'][0];
                    $allInfoWhere = [];
                    $pastorWhere = [];
                    $allInfoWhere[] = ['stdId',$stInfo['id'],'='];
                    $pastorWhere[] = ['token',$message,'='];
                    $pastorWhere[] = ['stdId',$stInfo['id'],'='];
                    $pastorRecord = $pasterModel->getPastorInfoByField($pastorWhere);


                    $allInfo = $studentMeta->getMetaInfoByField($allInfoWhere);
                    $allInfo = $allInfo['result'];

                    $metaKeys = array_column($allInfo,'meta_key');
                    $metaValues = array_column($allInfo,'meta_value');
                    $resultant = array_combine($metaKeys,$metaValues);
                    $response = array_merge($response,$resultant);
                    $response['pastorId'] = @$pastorRecord['result'][0]['id'];

                    $message = base64_encode(json_encode($response));
                }
            }



            return ['response'=>true,'message'=>$message];
        }catch (Exception $e){
            return ['response'=>false,'message'=>$e->getMessage()];
        }
    }

    /***
     * @param $payment
     * Send payment receipt once payment is made
     */
    public static function paymentMade($payment){
        try{
            /*print_r($payment);
            exit;*/
            $stdModel = new \center\models\StudentInfo();
            if(empty($payment['txn_id']))
                throw new Exception("Payment failed");

            if(empty($payment['product_name']))
                throw new Exception("Cannot find product name. Check the stripe because customer is already charged");


            if(!empty($payment['pathFrom'])){
                if($payment['pathFrom']=="second_payment"){
                    $formNumber = $payment['formNumber'];
                    $stdId = $payment['stdId'];
                    $paymentPart = "II";
                    $terms = explode(",",$payment['desiredTerms']);
                }
            }else{
                $information = explode('-',$payment['product_name']);
                $formNumber = $information[0];
                $stdId = $information[1];
                $paymentPart = $information[2];
                $terms = explode(",",$information[3]);
            }


            if(empty($terms))
                throw new Exception("No terms has been selected. Need to refund if card is charged!");


            $paymentModel = new \center\models\FeesPayment();
            $receivedAmount = $payment['price'];
            $termAmount = 0;
            if(is_array($terms)){
                foreach ($terms as $t){
                    $termAmount += get_field('price',$t);
                }

                /**
                 * When the full payment is made
                 */
                if(floor($receivedAmount) == floor($termAmount)){
                    $noOfTerms = count($terms);
                    $tempTermArr = [];
                    $tempSecondTermArr = [];
                    $noOfIteration = $noOfTerms*2;
                    $counter = 0;
                    for($i=0; $i<$noOfTerms; $i++){
                        foreach ($terms as $t){
                            if(!in_array($t,$tempTermArr)){
                                $tempTermArr[] = $t;
                                $part = "I";
                            }else if(!in_array($t,$tempSecondTermArr)){
                                $tempSecondTermArr[] = $t;
                                $part = "II";
                            }
                            /*if(count($tempTermArr)==$noOfTerms && count($tempSecondTermArr)==$noOfTerms){
                                continue;
                            }*/
                            $data['paymentId'] = $payment['txn_id'];
                            $data['formNumber'] = $formNumber;
                            $data['stdId'] = $stdId;
                            $data['course'] = $t;
                            $data['amount'] = $payment['price'];
                            $data['currencyCode'] = $payment['currency_code'];
                            $data['customerEmail'] = $payment['customer_email'];
                            $data['isSuccessful'] = true;
                            $data['part'] = strtoupper($part);
                            $data['orderNumber'] = $payment['order_id'];


                            $format = ['%s','%s','%d','%s','%d','%s','%s','%s','%s','%d'];


                            if($counter<$noOfIteration){
                               $paymentModel->insert($data,$format);
                               /* echo '<pre>';
                                echo 'iteration number '.$counter.' from '.$noOfIteration.PHP_EOL;
                                echo "First HALF 'I' :".count($tempTermArr).PHP_EOL;
                                echo "SECOND HALF 'II' :".count($tempSecondTermArr).PHP_EOL;
                                print_r($data);
//                            print_r($tempSecondTermArr);
                                echo '</pre>';*/
                                $counter++;
                            }

                        }
                    }
                   /* echo '<pre>';
                    print_r($terms);
                    print_r($tempTermArr);
                    print_r($tempSecondTermArr);
                    echo '</pre>';
                    wp_die();*/


                }else{
                    /**
                     * for half payment
                     */
                    foreach ($terms as $t){

                        $data['paymentId'] = $payment['txn_id'];
                        $data['formNumber'] = $formNumber;
                        $data['stdId'] = $stdId;
                        $data['course'] = $t;
                        $data['amount'] = $payment['price'];
                        $data['currencyCode'] = $payment['currency_code'];
                        $data['customerEmail'] = $payment['customer_email'];
                        $data['isSuccessful'] = true;
                        $data['part'] = strtoupper($paymentPart);
                        $data['orderNumber'] = $payment['order_id'];

                        $format = ['%s','%s','%d','%s','%d','%s','%s','%s','%s','%d'];

                        $paymentModel->insert($data,$format);
                    }
                }

            }


            $data['product_description'] = $payment['product_description'];
            $data['customer_email'] = $payment['customer_email'];

            if(NotificationsHandler::sendEmailReceipt($data))
                echo '';

        }catch (Exception $e){
            echo $e->getMessage();
        }

    }

    public static function isPaid($formNumber,$paymentPart){
        $studentModel = new \center\models\StudentInfo();
        $paymentModel = new \center\models\FeesPayment();
        $pWhere[] = ['formNumber',$formNumber,'='];
        $pWhere[] = ['part',strtoupper($paymentPart),'='];
        $Where[] = ['formNumber',$formNumber,'='];
        $studentRecord = $studentModel->getSingleStudentMeta($Where);
        if($studentRecord['total']>0){
            $paymentRecord = $paymentModel->getPaymentsByField($pWhere);
            if($paymentRecord['total']<1){
                return true;
            }
        }
        return false;
    }

    public static function hasResponded($data){
        if(empty($data))
            return false;

        $studentModel = new \center\models\StudentInfo();
        $pWhere[] = ['formNumber',$data['formNumber'],'='];
        $pWhere[] = ['id',$data['stdId'],'='];

        $studentRecord = $studentModel->getSingleStudentMeta($pWhere);
//        print_r($studentRecord );
        if($studentRecord['total']>0){
            $student = $studentRecord['result'][0];

            if(!empty($student['parentConsent']) && $student['parentConsent']=='yes')
                return true;
        }

        return false;
    }

    public function innoStripeEmptyArray(){
        $options = array();
        $options['stripe_testmode'] = '';
        $options['stripe_test_secret_key'] = '';
        $options['stripe_test_publishable_key'] = '';
        $options['stripe_secret_key'] = '';
        $options['stripe_publishable_key'] = '';
        $options['stripe_currency_code'] = '';
        $options['return_url'] = '';
        $options['enable_debug'] = '';
        return $options;
    }

    function innoStripeCheckoutOption(){
        $options = get_option('wp_stripe_checkout_options');
        if(!is_array($options)){
            $options = $this->innoStripeEmptyArray();
        }
        return $options;
    }

    public static function chargeFees($courses = []){

        try{
            if(empty($courses))
                throw new Exception("No course(s) selected! Select at least on course and try again");

//            print_r($courses);wp_die();
            $classes = implode(",",$courses);
            $stdModel = new \center\models\StudentInfo();
            $where = [];
            $where[] = ['isApproved','1','='];
//            $where[] = ['isApproved','0','='];
            $students = $stdModel->getSingleStudentMeta($where);
            $paymentModel = new \center\models\FeesPayment();
            $stripeInfoModel = new \center\models\StripeInfo();
            $errorMessage = [];
            if($students['total']>0){
                $studentList = $students['result'];
                foreach ($studentList as $key=>$value){

                    $desiredTerms = explode(",",$value['desiredTerms']);
                    if(!empty($desiredTerms)){
                        $whereCourse = [];
                        $paymentEntries = (count($desiredTerms) == 1) ? 1 : count($desiredTerms)*2;
                        $whereCourse[] = ['course',"(".$classes.")",'in'];
                        $whereCourse[] = ['stdId',$value['id'],'='];
                        /*$whereCourse[] = ['part',"I",'='];
                        $whereCourse[] = ['part',"II",'='];*/
                        if(!empty($value['chequeNumberFirst']))
                            $whereCourse[] = ['paymentId',$value['chequeNumberFirst'],'<>'];
                        if(!empty($value['chequeNumberSecond']))
                            $whereCourse[] = ['paymentId',$value['chequeNumberSecond'],'<>'];
//                        $grp[] = ['group by','part'];
                        $feesPaid = $paymentModel->getPaymentsByField($whereCourse);
                        /*echo '<pre>';
                        print_r($paymentEntries);
                        print_r($feesPaid);
                        echo '</pre>';
                        exit;*/
                        $totalFees = 0;
                        $title = [];
                        foreach ($desiredTerms as $dt){
                            $totalFees += (double)get_field('price',$dt);
                            $title[] = get_the_title($dt);
                        }
                        $amount = (($totalFees/2)*100);
                        if($feesPaid['total']<$paymentEntries && $feesPaid['total']>0){

                            $stripe_options = (new PluginFunc)->innoStripeCheckoutOption();

                            $stripeWhere = [];
//                            $stripeWhere[] = ['stdId',$value['id'],'='];
                            $stripeWhere[] = ['email',$feesPaid["result"][0]['customerEmail'],'='];
                            $stripeWhere[] = ['isActive',true,'='];
                            $stripeInfoResults = $stripeInfoModel->getStripeInfoByField($stripeWhere);
                            /*echo '<pre>';
                            print_r($value);
                            print_r($stripeWhere);
                            print_r($stripeInfoResults);
                            echo '</pre>';
                            exit;*/
                            if($stripeInfoResults['total'] == 1){
                                $customerID = $stripeInfoResults['result'][0]['customerId'];
                                if(!empty($customerID)){
                                    $secret_key = $stripe_options['stripe_secret_key'];
                                    if (WP_STRIPE_CHECKOUT_TESTMODE) {
                                        $secret_key = $stripe_options['stripe_test_secret_key'];
                                    }
                                    $method = "POST";
                                    $api = "charges";
                                    $request['currency'] = "usd";
                                    $request['amount'] = $amount;
                                    $request['description'] = "Another Half Payment For ".implode(", ",$title);
                                    $request['capture'] = 'true';
                                    $request['customer'] = $customerID;

                                    $response = wp_safe_remote_post(
                                        'https://api.stripe.com/v1/' . $api, array(
                                            'method' => $method,
                                            'headers' => array(
                                                'Authorization' => 'Basic ' . base64_encode($secret_key . ':'),
                                                'Stripe-Version' => '2018-07-27'
                                            ),
                                            'body' => $request,
                                            'timeout' => 70,
                                            'user-agent' => 'wpstripecheckout'
                                        )
                                    );
                                    if(!empty($response['body'])){

                                        $parsed_response = json_decode($response['body']);
                                        if (!empty($parsed_response->error)) {
                                            $error_msg = (!empty($parsed_response->error->code)) ? $parsed_response->error->code : 'stripe_error: ' . $parsed_response->error->message;
//                                            throw new Exception($error_msg);
                                            $errorMessage[$value['stdId']][] = $value['firstName'].' '.$value['lastName'].$parsed_response->error->message;
                                        }else{
                                            $parsed_response = json_decode($response['body'],true);
                                            /*echo '<pre>';
                                            print_r($parsed_response);
                                            echo '</pre>';exit;*/
                                            $parsed_response['txn_id'] = $parsed_response['id'];
                                            $parsed_response['customer_email'] = $value['email'];
                                            $parsed_response['currency_code'] = $parsed_response['currency'];
                                            $parsed_response['pathFrom'] = "second_payment";
                                            $parsed_response['stdId'] = $value['id'];
                                            $parsed_response['formNumber'] = $value['formNumber'];
                                            $parsed_response['desiredTerms'] = $value['desiredTerms'];
                                            $parsed_response['product_name'] = $parsed_response['description'];
                                            $parsed_response['product_description'] = $parsed_response['description'];
                                            $parsed_response['price'] = (double)$parsed_response['amount']/100;
                                            self::paymentMade($parsed_response);
                                        }

                                    }else{
//                                        throw new Exception("Payment Failed");
                                        $errorMessage[$value['stdId']][] = $value['firstName'].' '.$value['lastName']."payment failed";
                                    }
                                }else{
                                    $errorMessage[$value['stdId']][] = $value['firstName'].' '.$value['lastName']." doesn't have stripe account. So this users fee is not deducted";
                                }

                            }

                        }
                    }
                }
            }

//            return $response;
            return ['response'=>true,'message'=>$errorMessage];
        }catch (Exception $e){
            return ['response'=>false,'message'=>$e->getMessage()];
        }

    }

    public static function isEmailExist($email){
        $stModel = new \center\models\StudentInfo();
        if(!empty($email)){
            $where[] = ['email',$email,'='];
            $stnts = $stModel->getStudentInfoByField($where);
            if($stnts['total']>0)
                return false;
        }


        return true;
    }

}
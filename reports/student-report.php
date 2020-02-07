<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once ('../../../../wp-load.php');

//echo EX_PLUGIN_URL;exit;
$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$terms = get_posts(['post_type'=>'terms','posts_per_page'=>-1,'post_status'=>'publish','orderby'=>'date','order'=>'ASC']);
if(!empty($terms)){

    $spreadsheet->getProperties()->setCreator('Maranatha Bible School')
        ->setLastModifiedBy('Maranatha Bible School')
        ->setTitle('Office 2007 XLSX Test Document')
        ->setSubject('Office 2007 XLSX Test Document')
        ->setDescription('School student registration records.')
        ->setKeywords('office 2007 openxml php')
        ->setCategory('Student Registration Records.');


    $terms = json_decode(json_encode($terms),true);
    $termIds = array_column($terms,'ID');
    $termNames = array_column($terms,'post_title');
    $completeTerm = array_combine($termIds,$termNames);


    $jj = 0;
    $aj = 0;
    $activeSheetIndex = 0;
    $spreadsheet->setActiveSheetIndex($activeSheetIndex);
    foreach ($termIds as $tk=>$id){

        $termId = $id;
        $aj = $jj;

        // if first sheet
        /*if($jj==0)
            $spreadsheet->getActiveSheet()->setTitle($completeTerm[$termId]);*/

        if($jj!=0){

            $activeSheetIndex++;
            $spreadsheet->createSheet($activeSheetIndex);

        }
        $spreadsheet->setActiveSheetIndex($activeSheetIndex);
        $spreadsheet->getActiveSheet()->setTitle($completeTerm[$termId]);
        $jj++;
//        echo 'test';
        /*if($aj==0)
            continue;*/



        $termModel = new \center\models\SelectedCourses();
        $where = [];
        $where[] = ['termId',$termId,'='];
        $group = [];
        $group[] = ['group by','stdId'];
        $result = $termModel->getCoursesByField($where,[],$group);

        /*echo $termId.'<br>';
        echo '<pre>';
        print_r($result);
        echo '<pre>';*/

        $temp = $result['result'];
        $users = array_column($temp,'stdId');
        if(!empty($users)){
            /*echo $termId.'<br>';
            echo '<pre>';
            print_r($where);
            print_r($result);
            echo '<pre>';*/
            $userIds = implode(",",$users);
            $where = [];
            $where[] = ['stdId',"(".$userIds.")","IN"];
//            $courseSelection = $termModel->getCoursesByField($where);
            $meta_query = [];
            $meta_query[] = ['key'=>'term','value'=>$termId];
            $args = ['post_type'=>'classes','post_status'=>'publish','posts_per_page'=>-1,'meta_query'=>$meta_query];
            $courses = get_posts($args);

            /*echo '<pre>';
            print_r($courses);
            echo '</pre>';*/
            if(!empty($courses)){
                $courses = json_decode(json_encode($courses),true);
                $courseIds = array_column($courses,'ID');
                $courseTitle = array_column($courses,'post_title');
                $completeCourseArray = array_combine($courseIds,$courseTitle);

                $timeSlotObj = [];
                foreach ($courseIds as $c=>$id){

                    $timeSlotObj[$id] = @get_the_terms($id,'class-time')[0];
                }


                $timeSlotObj = json_decode(json_encode($timeSlotObj),true);
                $timeSlotObjId = array_column($timeSlotObj,'term_id');
                $timeSlotObjTime = array_column($timeSlotObj,'name');
                $timeSlot = array_combine($timeSlotObjId,$timeSlotObjTime);
                $userModel = new \center\models\StudentInfo();


                $col = 1;
                $pos = 1;
                $alphas = range('A', 'Z');

                /**
                 * set course title
                 */

                $spreadsheet->getActiveSheet()
                    ->setCellValue("A1", "Student Name");
                foreach ($completeCourseArray as $k=>$v){

                    $title = $v.' - '.$timeSlotObj[$k]['name'];

                    $spreadsheet->getActiveSheet()
                        ->setCellValue($alphas[$pos].$col, $title);
                    $pos++;


//                    echo $termId.' - '.$title.'<br>';
                    /*echo '<pre>';
                    print_r($result);
                    echo '<pre>';*/
                }

                /*$spreadsheet->getActiveSheet()->setTitle('TtsetSimple');
                $spreadsheet->setActiveSheetIndex(0);*/



                /**
                 * start inserting data from second row.
                 */

                foreach ($users as $u){
                    $uWhere = [];
                    $uWhere[] = ['id',$u,'='];

                    $user = $userModel->getSingleStudentMeta($uWhere);
                    if($user['total']>0){

                        $col++; // second row
                        $pos = 0;

                        $user = $user['result'][0];
                        /*echo '<pre>';
                        print_r($user);
                        echo '<pre>';*/
                        $studentName = $user['firstName'].', '.$user['lastName'];
                        $spreadsheet->getActiveSheet()
                            ->setCellValue($alphas[$pos].$col, $studentName);

                        $pos++;
                        foreach ($completeCourseArray as $k=>$v){
                            $where = [];
                            $where[] = ['termId',$termId,'='];
                            $where[] = ['stdId',$u,'='];
                            $where[] = ['classId',$k,'='];
                            $selectedChoice = $termModel->getCoursesByField($where,['choice']);

                            $choice = ($selectedChoice['total']>0) ? $selectedChoice['result'][0]['choice'] : "";
                            $spreadsheet->getActiveSheet()
                                ->setCellValue($alphas[$pos].$col, $choice);
                            $pos++;
                        }
                    }



                }

//                $jj++;
            }
        }




    }

}
//exit;
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Students selected classes.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;
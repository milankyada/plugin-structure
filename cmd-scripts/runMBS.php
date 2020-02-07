<?php

require_once ("script-header.php");

class runMBS
{

    public static function softDeleteSelectedCourse(){
        $courseModel = new \center\models\SelectedCourses();
        $studentModel = new \center\models\StudentInfo();
        $students = $studentModel->getStudentAllInfo();

        if(!empty($students['total']) && $students['total']>0){
            $result = $students['result'];
            $stdIds = array_column($result,'id');
            $userIds = implode(",",$stdIds);
            $where = [];
            $where[] = ['stdId',"(".$userIds.")","NOT IN"];
            $courses = $courseModel->getCoursesByField($where);
            $coursesResult = $courses['result'];
            $deleteStdIDs = array_unique(array_column($coursesResult,'stdId'));
            foreach ($deleteStdIDs as $d){
                $dWhere = [];
                $dWhere = ['stdId'=>$d];
//                $dWhere[] = ['stdId',$d,'='];
                $data['isDeleted'] = true;
                $format = ['%s'];
                $where_format = ['%d'];
                $res[] = $courseModel->updateCourse($data,$dWhere,$format,$where_format);
            }

            /*print_r($deleteStdIDs);
            print_r($stdIds);*/
        }
    }

}
if((!empty($argv[1]) && $argv[1]=="--deleteSelectedCourse")){
    runMBS::softDeleteSelectedCourse();
}
<?php


namespace center\models;


class StudentInfo extends CommonModel
{
    private $tableName;

    function __construct() {
        parent::__construct();
        $this->tableName = $this->wpdb->prefix.'studentInfo';
    }

    function insert($data, $format){
        return parent::insertRecord($this->tableName,$data, $format);
    }

    function getAllStudentsInfo( $order = []){
        return parent::getRecords($this->tableName, $order);
    }

    function getStudentInfoByField( $where,$fields = [], $order = [] ){
        return parent::getRecord($this->tableName, $where, $fields, $order);
    }

    function getStudentAllInfo( $fields = [], $order = [] ){
        return parent::getInfoWithMeta($this->tableName,$fields,$order);
    }

    function getSingleStudentMeta( $where,$fields = [], $order = [] ){
        return parent::getSingleInfoMeta($this->tableName, $where, $fields, $order);
    }

    function updateStudentInfo($data, $where, $format = null, $where_format = null){
        $response = parent::updateRecord($this->tableName, $data, $where, $format, $where_format);
        return $response;
    }

    function delete($where, $format = null){
        $response = parent::deleteRecord($this->tableName, $where, $format);
        return $response;
    }

}
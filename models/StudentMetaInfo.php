<?php


namespace center\models;


class StudentMetaInfo extends CommonModel
{
    private $tableName;

    function __construct() {
        parent::__construct();
        $this->tableName = $this->wpdb->prefix.'studentMeta';
    }

    function insert($data, $format){
        return parent::insertRecord($this->tableName,$data, $format);
    }

    function getAllMetaInfo( $order = []){
        return parent::getRecords($this->tableName, $order);
    }

    function getMetaInfoByField( $where,$fields = [], $order = [] ){
        return parent::getRecord($this->tableName, $where, $fields, $order);
    }

    function updateMetaInfo($data, $where, $format = null, $where_format = null){
        $response = parent::updateRecord($this->tableName, $data, $where, $format, $where_format);
        return $response;
    }

    function delete($where, $format = null){
        $response = parent::deleteRecord($this->tableName, $where, $format);
        return $response;
    }

}
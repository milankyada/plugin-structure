<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019-01-22
 * Time: 13:41
 */

namespace center\models;

class CommonModel
{
    public $wpdb;
    function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    public function insertRecord($table,$data, $format){
        try{
            if(!empty($data) && !empty($format)){
                $this->wpdb->insert($table,$data,$format);
                $insertedId = $this->wpdb->insert_id;
//            echo $this->wpdb->last_query.'<br>';
                return ['id' => $insertedId];
            }else{
                return [];
            }
        }
        catch (\Exception $e){
            return [];
        }
    }

    public function getRecord($table,$where,$fields = [],$order = []){
        $lastPart = [];
        if(!empty($order)){
            foreach ($order as $k=>$v){
                $lastPart[] = strtoupper($v[0]).' '.$v[1];
            }
        }

        if($table == $this->wpdb->prefix.'studentInfo'){
            $where[] = ['isDeleted','0','='];
        }

        if(!empty($where) && count($where)>0){

//            $where[] = ['isAvail','1','='];
            $sql = [];
            foreach ($where as $k=>$v){
                if(strtolower($v[2]) != "in" && strtolower($v[2]) != "not in"){
                    if(is_numeric($v[1]))
                        $sql[] = $v[0].' '. $v[2].' '.$v[1];
                    else
                        $sql[] = $v[0].' '. $v[2].' "'.$v[1].'"';
                }else{
                    $sql[] = $v[0].' '. $v[2].' '.$v[1].' ';
                }

            }

            $whereClause = implode(" and ", $sql );
            $field = (count($fields) && !empty($fields)) ? implode(',',$fields) : "*";
            $result = $this->wpdb->get_results("SELECT ".$field." FROM $table WHERE $whereClause ".implode(" ",$lastPart),ARRAY_A);
//            echo $this->wpdb->last_query.'<br>';
            $records['total'] = count($result) > 0 ? count($result) : 0;
            $records['result'] = $result;

            return $records;
        }else{
            return [];
        }
    }

    public function updateRecord($table, $data, $where, $format = null, $where_format = null){
        try{
            if(!empty($data) && !empty($where)){
                $this->wpdb->update( $table, $data, $where, $format = null, $where_format = null );
                $insertedId = $this->wpdb->insert_id;
//                echo $this->wpdb->last_query;
                return ['id' => $insertedId];
            }else{
                return [];
            }
        }
        catch (\Exception $e){
            return [];
        }
    }

    public function deleteRecord($table, $where, $where_format = null){
        try{
            if(!empty($where)){
                $this->wpdb->delete( $table, $where, $where_format = null );

                return ['resp' => $this->wpdb->last_query];
            }else{
                return [];
            }
        }
        catch (\Exception $e){
            return [];
        }
    }

    public function getRecords($table, $order = []){
        try{
            $lastPart = [];
            if(!empty($order)){
                foreach ($order as $k=>$v){
                    $lastPart[] = strtoupper($v[0]).' '.$v[1];
                }
            }

            $result = $this->wpdb->get_results("SELECT * FROM $table ".implode(" ",$lastPart),ARRAY_A);
            $records['total'] = count($result) > 0 ? count($result) : 0;
            $records['result'] = $result;

            return $records;
        }catch (\Exception $e){
            return [];
        }

    }

    public function getInfoWithMeta($table,$fields = [],$order = []){
        $lastPart = [];
        if(!empty($order)){
            foreach ($order as $k=>$v){
                $lastPart[] = strtoupper($v[0]).' '.$v[1];
            }
        }
        $field = (count($fields) && !empty($fields)) ? implode(',',$fields) : "*";
//            $result = $this->wpdb->get_results("SELECT ".$field." FROM $table WHERE $whereClause ".implode(' ',$lastPart),ARRAY_A);
        if($table == $this->wpdb->prefix.'studentInfo'){
            $result = $this->wpdb->get_results("SELECT ".$field." FROM $table  WHERE isDeleted = 0 ".implode(" ",$lastPart),ARRAY_A);
        }else{

            $result = $this->wpdb->get_results("SELECT ".$field." FROM $table ".implode(" ",$lastPart),ARRAY_A);
        }


        $records['total'] = count($result) > 0 ? count($result) : 0;
        $records['result'] = $result;
        if($records['total']>0){
            $metaTableInfo = \tables::getMetaTable($table);

            foreach ($records['result'] as $s=>$v){

                $allInfoWhere = [];
                $allInfoWhere[] = [$metaTableInfo[1],$v['id'],'='];

                $allInfo = $this->getRecord($metaTableInfo[0],$allInfoWhere);
                $allInfo = $allInfo['result'];

                $metaKeys = array_column($allInfo,'meta_key');
                $metaValues = array_column($allInfo,'meta_value');
                $resultant = array_combine($metaKeys,$metaValues);

                $records['result'][$s] = array_merge($records['result'][$s],$resultant);
            }

        }

        return $records;
    }

    public function getSingleInfoMeta($table, $where, $fields = [], $order= []){
        $lastPart = [];
        if(!empty($order)){
            foreach ($order as $k=>$v){
                $lastPart[] = strtoupper($v[0]).' '.$v[1];
            }
        }
        if($table == $this->wpdb->prefix.'studentInfo'){
            $where[] = ['isDeleted','0','='];
        }
        if(!empty($where) && count($where)>0){

//            $where[] = ['isAvail','1','='];
//            ['fileId',"(".$fileId.")",'IN'],
            $sql = [];
            foreach ($where as $k=>$v){
                if(strtolower($v[2]) != "in" ){
                    if(is_numeric($v[1]))
                        $sql[] = $v[0].' '. $v[2].' '.$v[1];
                    else
                        $sql[] = $v[0].' '. $v[2].' "'.$v[1].'"';
                }else{
                    $sql[] = $v[0].' '. $v[2].' '.$v[1].' ';
                }

            }

            $whereClause = implode(" and ", $sql );
            $field = (count($fields) && !empty($fields)) ? implode(',',$fields) : "*";

            $result = $this->wpdb->get_results("SELECT ".$field." FROM $table WHERE $whereClause ".implode(" ",$lastPart),ARRAY_A);
//                echo $this->wpdb->last_query;
            $records['total'] = count($result) > 0 ? count($result) : 0;
            $records['result'] = $result;
            if($records['total']>0){
                $metaTableInfo = \tables::getMetaTable($table);

                foreach ($records['result'] as $s=>$v){

                    $allInfoWhere = [];
                    $allInfoWhere[] = [$metaTableInfo[1],$v['id'],'='];

                    $allInfo = $this->getRecord($metaTableInfo[0],$allInfoWhere);
                    $allInfo = $allInfo['result'];

                    $metaKeys = array_column($allInfo,'meta_key');
                    $metaValues = array_column($allInfo,'meta_value');
                    $resultant = array_combine($metaKeys,$metaValues);

                    $records['result'][$s] = array_merge($records['result'][$s],$resultant);
                }

            }
            return $records;
        }else{
            return [];
        }
    }


}
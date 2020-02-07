<?php

require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
class tables
{
    public static $tables = [];
    public static $metaTables = [];

    public function __construct()
    {
        global $wpdb;
        $this->createTable();
        $this->setMetaTables();
    }
    public static function getTables(){
        global $wpdb;
        self::$tables[] = "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."sample_table` (
                              `id` BIGINT NOT NULL AUTO_INCREMENT, 
                              `firstName` TEXT NOT NULL, 
                              `lastName` TEXT NOT NULL, 
                              `phone` TEXT NULL, 
                              `email` TEXT NULL, 
                              `isDeleted` BOOLEAN NOT NULL DEFAULT FALSE, 
                              `createdOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
                              `isCompleted` BOOLEAN NOT NULL DEFAULT FALSE, 
                              `formToken` TEXT NULL, 
                              `formNumber` TEXT NULL, 
                              `fileName` TEXT NULL, 
                              `appDate` TIMESTAMP NOT NULL, 
                              `isApproved` BOOLEAN NULL, 
                              PRIMARY KEY (`id`)
                            ) ENGINE = InnoDB;";



        return self::$tables;
    }
    public function createTable(){
        $tables = self::getTables();

        if(!empty($tables) && is_array($tables)){
            foreach ($tables as $table){
                dbDelta( $table );

            }

        }

        return true;
    }

    public function setMetaTables(){
        global $wpdb;
        self::$metaTables = [
            $wpdb->prefix."sample_table" => [$wpdb->prefix."sample_meta",'stid'],
        ];
        return self::$metaTables;
    }

    public static function getMetaTable($baseTable){
        if(empty($baseTable))
            return [];

        return (array_key_exists($baseTable,self::$metaTables) ? self::$metaTables[$baseTable] : []);
    }
}
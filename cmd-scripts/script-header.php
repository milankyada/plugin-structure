
<?php
if( php_sapi_name() !== 'cli' ) {
    die("Meant to be run from command line");
}

function find_wordpress_base_path() {
    $dir = dirname(__FILE__);
    do {
        //it is possible to check for other files here
        if( file_exists($dir."/wp-config.php") ) {
            return $dir;
        }
    } while( $dir = realpath("$dir/..") );
    return null;
}

define( 'BASE_PATH', find_wordpress_base_path()."/" );
define('WP_USE_THEMES', false);
global $wp, $wp_query, $wp_the_query, $wp_rewrite, $wp_did_header, $wpdb;
require(BASE_PATH . 'wp-load.php');

require_once ('../models/CommonModel.php');
require_once ('../models/FeesPayment.php');
require_once ('../models/FileMeta.php');
require_once ('../models/FileModel.php');
require_once ('../models/NotificationModel.php');
require_once ('../models/PastorInfo.php');
require_once ('../models/PastorMetaInfo.php');
require_once ('../models/SelectedCourses.php');
require_once ('../models/StripeInfo.php');
require_once ('../models/StudentInfo.php');
require_once ('../models/StudentMetaInfo.php');
require_once ('../models/StudentInfoSNC.php');
require_once ('../models/StudentMetaInfoSNC.php');
require_once ('../models/TermSize.php');
require_once ('../models/Token.php');


$message = "";
if((!empty($argv[1]) && $argv[1]=="--help") || count($argv)==1){
    $message .= "##########################################".PHP_EOL;
    $message .= "#        MARANATHA BIBLE SCHOOL          #".PHP_EOL;
    $message .= "#        CLI by Milan                    #".PHP_EOL;
    $message .= "##########################################".PHP_EOL;
    $message .= PHP_EOL;
    $message .= "Choose from Option Below".PHP_EOL;
    $message .= "__________________________________________".PHP_EOL;
    $message .= PHP_EOL;
    $message .= "1. If student record is deleted and want to delete selected courses too then use as `php runMBS --deleteSelectedCourse`".PHP_EOL;
    echo $message.PHP_EOL;
}


<?php
/**
 * Plugin Name: Custom Forms For Students and Pastor
 * Author: Milankumar Kyada
 * Description: Plugin skeleton created by Milan to boost up plugin development process.
 * Version: 1.0
 */
namespace pluginstructure;


define("EX_PLUGIN_DIR",__DIR__);
define("EX_FS",EX_PLUGIN_DIR.'/Files/');
define("EX_FS_IMG",EX_PLUGIN_DIR.'/Portrait/');
define("EX_PLUGIN_URL",plugin_dir_url(__FILE__));
define("JS_SYS_URL",EX_PLUGIN_URL.'public/js/system');
ob_start();

final class Init {

	public static function Instance(){
		static $inst = null;
		if ($inst === null) {
			$inst = new Init();
		}
		return $inst;
	}

	private function __construct() {
		self::includeFiles();
		self::handleHooks();
		self::createFeesPaymentPage();
		self::createPaymentResponsePage();
		self::createClassSelectionPage();
		self::createProcessPage();
		self::createStudentPortraitUploadPage();
		(new \AjaxCall());
        (new \tables());
        (new \FrontEndShortcodes());
        global $admin;
        $admin = (get_option("registrarEmail")) ? get_option("registrarEmail") : "milan@innovative.ink";
	}
	private function __clone() {}
	private function __wakeup() {}
	private function __sleep() {}

    /**
     * include all necessary files here
     */
	private function includeFiles(){
	    require_once ("db/tables.php");
	    require_once ("includes/FormFields.php");
	    require_once ("includes/PluginConstants.php");
	    require_once ("includes/notifications/NotificationsHandler.php");
	    require_once ('includes/shortcodes/FrontEndShortcodes.php');
	    require_once ('includes/core/PluginFunc.php');

        require_once ("exception/CustomExceptions.php");
        require_once ("libraries/vendor/autoload.php");
		require_once ('models/CommonModel.php');

        require_once ('models/StudentInfo.php');
		require_once ('models/StudentMetaInfo.php');


		require_once ("Routes.php");

		require_once ("includes/Modal.php");
		require_once ("includes/AjaxCall.php");

    }

    /**
     * All the hooks required to initialize plugin
     */
	private function handleHooks(){
		add_action("admin_menu", array($this,"makePages"));
		add_action( 'admin_enqueue_scripts', array($this,"includeSkins"));
		add_action( 'wp_enqueue_scripts', array($this,"includeFrontEndSkins"));
        add_action( 'template_include', array($this,'payment_redirect'));
        add_action('after_setup_theme', array($this,'cc_wpse_278096_disable_admin_bar'));


        add_filter( 'page_template', array($this,'sample_template_redirect') );

//        add_action( 'template_redirect', array($this,'protect_from_public') );
        add_action('admin_bar_menu', array($this,'Inno_Dashboard'), 100);

	}


    public function protect_from_public(){
        global $post;

	    if(is_page(['courses','welcome'])){

            if(!is_user_logged_in()){
                wp_redirect(get_permalink(4));
                exit();
//                return "You need to <a href='".get_permalink(432)."'>login</a> first";

            }
        }

    }

    /**
     * Plugin makeup box ;)
     */
	public function includeSkins(){
		self::css();
		self::js();
        //wp_enqueue_media();
	}

	public function includeFrontEndSkins(){
        self::frontEndCSS();
        self::frontEndJS();

    }

    private static function frontEndCSS(){
	    self::css();
    }

    private static function frontEndJS(){
        self::js();
    }

	private static function css(){
//		wp_enqueue_style( 'inno-forms-style-css', EX_PLUGIN_URL."public/css/semantic.css");
		wp_enqueue_style( 'plugin-css', EX_PLUGIN_URL."public/css/styles.css");
		wp_enqueue_style( 'datatable-css', "https://cdn.datatables.net/v/se/dt-1.10.18/datatables.min.css");
        wp_register_style( 'jquery-ui', 'https://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css' );

    }

	private static function js(){
//		wp_enqueue_script( 'semantic-js', EX_PLUGIN_URL."public/js/semantic.js",'','','true');
        wp_register_script( 'main-js', EX_PLUGIN_URL."public/js/main/main.js",'','','true');
        wp_register_script("file-upload", JS_SYS_URL.'/fileUpload.js');
        wp_enqueue_script("datatable-js", "https://cdn.datatables.net/v/se/dt-1.10.18/datatables.min.js");
        wp_enqueue_script( 'jquery-ui-datepicker' );


	}


    //TODO: DON'T FORGET TO LOOK OVER Routes.php (IT'S VERY IMPORTANT)
	public function makePages(){
        $user = wp_get_current_user();
        $userRoles =  $user->roles;
        if(in_array("administrator",$userRoles)){
            add_menu_page(__("SP Dashboard"), __("SP Dashboard"), "read", "stu-past",array($this,"loadDashboard"));
            add_submenu_page("stu-past",__("Applications"), __("Applications"), "read", "sp-past",array($this,"loadSPApplication"));
            add_submenu_page("stu-past",__("Actions & Notifications"), __("Actions & Notifications"), "read", "sp-notifications",array($this,"loadSPNotifications"));
        }


	}

    /**
     * Hide admin bar for all the user roles except administrator.
     */
    function cc_wpse_278096_disable_admin_bar() {
        if (current_user_can('administrator') ) {
            // user can view admin bar
            show_admin_bar(true); // this line isn't essentially needed by default...
        } else {
            // hide admin bar
            show_admin_bar(false);
        }
    }


	public function loadDashboard(){
		Routes::get("dashboard");
	}


    /**
     * @param $template
     * @return string
     * redirect page before it renders
     */
    function sample_template_redirect( $template ) {

        $plugindir = EX_PLUGIN_DIR;//dirname( __FILE__ );

        if ( is_page( 'sample-page-template' )) {

            $template = $plugindir . '/templates/sample-page-template.php';
        }

        return $template;

    }


    /**
     * create page template.
     * Need to create page template inside templates folder.
     */
    private static function sampleTemplatePage(){
        $post_id = -1;

        // Setup custom vars

        $slug = 'sample-page-template';
        $title = 'Sample Page Template';

        // Check if page exists, if not create it
        if ( null == get_page_by_title( $title )) {

            $uploader_page = array(
                'comment_status'        => 'closed',
                'ping_status'           => 'closed',
                'post_name'             => $slug,
                'post_title'            => $title,
                'post_status'           => 'publish',
                'post_type'             => 'page'
            );

            $post_id = wp_insert_post( $uploader_page );

            add_post_meta( $post_id, '_wp_page_template', 'sample-page-template.php' );
            $isThere = metadata_exists('page', $post_id, '_plugin_source');
            if(!$isThere){
                add_post_meta( $post_id, '_plugin_source', 'sample-page-template.php' );
            }else{
                update_post_meta( $post_id, '_plugin_source', 'sample-page-template.php' );
            }

            if ( !$post_id ) {

                wp_die( 'Error creating template page' );

            }

        } // end check if
    }


    /**
     * @param $admin_bar
     * create shortcut link in admin bar
     * TODO: Don't forget to change slug inside "admin_url"
     */

    public function Inno_Dashboard($admin_bar){
        $admin_bar->add_menu( array(
            'id'    => 'sp-app-past',
            'title' => 'Inno Dashboard',
            'href'  => admin_url('admin.php?page=sp-past'),
            'meta'  => array(
                'title' => 'Inno Dashboard',
            ),
        ));
        $admin_bar->add_menu( array(
            'id'    => 'wp-sub-link',
            'parent'=> 'sp-app-past',
            'title' => 'Actions & Notifications',
            'href'  => admin_url('admin.php?page=sp-notifications'),
            'meta'  => array(
                'title' => __('Actions & Notifications'),
            ),
        ));
    }


}
Init::Instance();
ob_clean();
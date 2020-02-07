<?php


namespace center;


class Routes {

	public static $routes = [
		'dashboard'  => 'views/dashboard.php',
		'spApplication' => 'views/applications.php',
		'spNotification' => 'views/notifications.php'
	];

	public static function get($name){

		$routes = self::$routes;

		if(empty($name) || !array_key_exists($name,$routes))
			return;

		try{
			if(array_key_exists($name,$routes)){
                wp_enqueue_script('main-js');
				include_once ('views/wrapper/header.php');
				require_once ($routes[$name]);
				include_once ('views/wrapper/footer.php');
			}

		}catch (\Exception $e){

		}

	}
}
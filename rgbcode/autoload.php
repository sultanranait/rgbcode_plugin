<?php


class MyAutoloaderClass
{
	// protected $prefix = 'RGBCode';
    public static function libsLoader($className)
    {
    	if(strpos($className, 'RGBCode') !== false){
	    	if (strpos($className, 'Lib') !== false) {
	    		$class = str_replace('\\', '/', $className);
	    		$class_array = explode('/', $class);
			 	$location =  WP_PLUGIN_DIR .'/rgbcode/lib/'.$class_array[2] . '.php';
			 	if (is_file($location)) {
		        	require_once($location);
		    	} 
			}
		}
	}
}

spl_autoload_register('MyAutoloaderClass::libsLoader');
?>
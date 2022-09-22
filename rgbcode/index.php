<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
session_start();


/**
 * The plugin bootstrap file
 *
 * @link              
 * @since             1.0.0
 * @package           RGB Code
 *
 * @wordpress-plugin
 * Plugin Name:       RGB Code
 * Plugin URI:        
 * Description:       
 * Version:           
 * Author:            
 * Author URI:        
 */

if ( version_compare( PHP_VERSION, '5.3.7', '<' ) ) {
    add_action( is_network_admin() ? 'network_admin_notices' : 'admin_notices', create_function( '', 'echo \'<div class="updated"><h3>RGB Code</h3><p>To install the plugin - <strong>PHP 5.3.7</strong> or higher is required.</p></div>\';' ) );
} else {
	include_once __DIR__ . '/autoload.php';

	function rgbcode_activation(){
		call_user_func( array( '\RGBCode\Lib\Installer', 'setup' ) );
	}

	function rgbcode_deactivation(){
		call_user_func( array( '\RGBCode\Lib\Installer', 'deactivation' ) );
	}

	register_activation_hook(__FILE__,'rgbcode_activation');
	register_deactivation_hook(__FILE__,'rgbcode_deactivation');
	
    $rgbcode = new \RGBCode\Lib\Plugin;
    $rgbcode->run();
}
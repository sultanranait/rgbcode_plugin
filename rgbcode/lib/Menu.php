<?php
namespace RGBCode\Lib;

class Menu{

	public function _renderMainPage() {
        include_once( WP_PLUGIN_DIR.'/rgbcode/admin/views/main.php' );
    }
}
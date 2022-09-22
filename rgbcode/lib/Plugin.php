<?php
namespace RGBCode\Lib;
use RGBCode\Lib\Menu as adminMenu;

class Plugin{
    
    public function run(){
            
        add_action('init', array($this, 'init_rgbcode'));
        add_action('wp_head', array($this,'myplugin_ajaxurl'));
        add_action('admin_menu', array($this, 'add_menu'));

        add_action( 'wp_ajax_render_rgbcode_users_table_rows', array($this,'render_rgbcode_users_table_rows') );
        add_action( 'wp_ajax_nopriv_render_rgbcode_users_table_rows', array($this,'render_rgbcode_users_table_rows') );

        add_shortcode( 'rgbcode_users_table_view' , array($this,'rgbcode_shortcode_callback_function') );
        add_action( 'wp_enqueue_scripts', array($this,'my_plugin_register_scripts') );
    }

    function myplugin_ajaxurl() {

       echo '<script type="text/javascript">
               var ajaxurl = "' . admin_url('admin-ajax.php') . '";
             </script>';
    }

    function init_rgbcode(){
        
    }

    function my_plugin_register_scripts(){
        wp_register_script('bootstrap','//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js',array('jquery'),'3.3.7',true);
        wp_register_style('bootstrap','//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css', array(), '3.3.7');
        wp_register_style('font-awesome',plugins_url( 'assets/font-awesome-4.7.0/css/font-awesome.min.css', dirname(__FILE__) ), array(), '4.7.0');
        wp_register_script( 'custom-js', plugins_url( 'assets/js/custom.js', dirname(__FILE__) ),array( 'jquery' ));
    }

    function rgbcode_shortcode_callback_function() {

        wp_enqueue_script('bootstrap');
        wp_enqueue_style('bootstrap');
        wp_enqueue_style('font-awesome');
        wp_enqueue_script('custom-js');
        
        global $wpdb;
        $rgbcode_users_table = $wpdb->prefix."rgbcode_users_table";

        $rgbcode_users = $wpdb->get_results("SELECT * FROM ".$rgbcode_users_table);

        $html = '<div class="container">
                    <div class="row" style="margin-bottom: 40px;">
                        <div class="col" style="margin-left: 68%;">
                            <label for="roles">Filter By Role:</label>
                            <select name="roles" id="roles">
                              <option value=""></option>
                              <option value="subscriber">Subscriber</option>
                              <option value="author">Author</option>
                              <option value="editor">Editor</option>
                              <option value="administrator">Administrator</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <table class="table table-striped">
                              <thead>
                                <tr>
                                  <th scope="col">Username <i class="fa fa-sort orderBy" id="asc" aria-hidden="true"></i>
                                  <th scope="col">Email</th>
                                  <th scope="col">Role</th>
                                </tr>
                              </thead>
                              <tbody id="user-table-body">
                                '.$this->get_rgbcode_users().'
                              </tbody>
                            </table>
                        </div>
                    </div>
                </div>';

        return $html;

    }
    
    function get_rgbcode_users($order=null, $role=null){

        global $wpdb;
        $rgbcode_users_table = $wpdb->prefix."rgbcode_users";
        $order_by = '';
        if(isset($order)) { $order_by = " ORDER BY username ".$order; }
        $where = '';
        if(isset($role) && !empty($role)) { $where = " WHERE role='".$role."'"; }

        $rgbcode_users = $wpdb->get_results("SELECT * FROM ".$rgbcode_users_table.$where.$order_by);
        
        foreach($rgbcode_users as $key => $rgbcode_user) {
            $rows .= "<tr>
              <td>".$rgbcode_user->username."</td>
              <td>".$rgbcode_user->email."</td>
              <td>".$rgbcode_user->role."</td>
            </tr>";
        }

        return $rows;
    }

    function render_rgbcode_users_table_rows(){

        $order = 'asc';
        if(isset($_POST['order']) && !empty($_POST['order'])){
            $order = $_POST['order'];
        }

        $role = '';
        if(isset($_POST['role']) && !empty($_POST['role'])){
            $role = $_POST['role'];
        }

        $rows = $this->get_rgbcode_users($order,$role);

        echo $rows;
        die;
    }

    function add_menu(){

        $main_page = add_menu_page('RGB Code','RGB Code','manage_options','rgbcode',array( new adminMenu(), '_renderMainPage' ),'',23);
    }
}
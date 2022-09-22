<?php
namespace RGBCode\Lib;

class Installer{

	public static function setup(){
		global $wpdb;
        $rgbcode_users_table = $wpdb->prefix . 'rgbcode_users';

        $rgbcode_users_table_sql = "CREATE TABLE " . $rgbcode_users_table . " (
            id int(9) NOT NULL AUTO_INCREMENT,
            username varchar(255),
            email varchar(255),
            role varchar(255),
            UNIQUE KEY id (id)
            );";
        
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            
            dbDelta($rgbcode_users_table_sql);

            // Import users from csv file
            if (($open = fopen(WP_PLUGIN_DIR ."/rgbcode/assets/data/users.csv", "r")) !== FALSE) 
            {
                while (($data = fgetcsv($open, 1000, ",")) !== FALSE) 
                {
                    $users[] = $data;
                }
                fclose($open);
            }
            unset($users[0]);
            foreach ($users as $key => $user) {
            
                $wpdb->insert( 
                    $rgbcode_users_table,
                    array(
                        'username' => $user[0],
                        'email'    => $user[1],
                        'role'     => $user[2]
                    )
                );
            }

            // add admin users in rgbcode users table
            $args = array(
                'role'    => 'administrator',
                'orderby' => 'user_nicename',
                'order'   => 'ASC'
            );
            $admins = get_users( $args );
            foreach ($admins as $key => $admin) {
            
                $wpdb->insert( 
                    $rgbcode_users_table,
                    array(
                        'username' => $admin->user_login,
                        'email'    => $admin->user_email,
                        'role'     => $admin->roles[0]
                    )
                );
            }
	}

	public static function deactivation(){
		global $wpdb;
        $rgbcode_users_table = $wpdb->prefix . 'rgbcode_users';

        $wpdb->query("DROP TABLE IF EXISTS $rgbcode_users_table");

	}
}
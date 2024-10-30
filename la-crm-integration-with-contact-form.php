<?php
/*
Plugin Name: LA CRM Integration with Contact Form
Plugin URI: https://www.yudiz.com/
description: Send Contact Form 7 data to Less Annoying CRM
Version: 2.1
Author: Yudiz Solutions Ltd.
Author URI: https://www.yudiz.com/
*/
?>
<?php
define( 'LICF', __FILE__ );
define( 'LICF_DIR', plugin_dir_path( __FILE__ ) );
define( 'LICF_URL', plugin_dir_url( LICF_DIR ) . basename( dirname( __FILE__ ) ) . '/' );
define( 'LICF_BASENAME', plugin_basename( LICF ) );
require_once(LICF_DIR.'init/licf-functions.php');

//Callback in plugin activation
if(!function_exists('licf_activate_plugin')){
    function licf_activate_plugin() { 
        licf_chk_contactfrm7();
    }
    register_activation_hook( __FILE__, 'licf_activate_plugin' );
}

//Callback in plugin activation and check for contact form 7
if(!function_exists('licf_chk_contactfrm7')){
    function licf_chk_contactfrm7() {
        $plugin = plugin_basename( __FILE__ );
        if ( ! class_exists('WPCF7_ContactForm') ) {
            add_action( 'admin_notices', 'licf_contactfrm7_misses' );
            deactivate_plugins( $plugin );
            if ( isset( $_GET[ 'activate' ] ) ) {
            // Do not sanitize it because we are destroying the variables from URL
            unset( $_GET[ 'activate' ] );
            }
        }
    }
}
add_action( 'admin_init', 'licf_chk_contactfrm7' );

//Callback for notice
if(!function_exists('licf_contactfrm7_misses')){
    function licf_contactfrm7_misses(){
        ?>
        <div class="notice notice-error is-dismissible"> 
            <p><strong>Please Install and activate the <a href="https://wordpress.org/plugins/contact-form-7/" target="_blank">contact form 7</a> plugin to activate Contact Form Data CRM plugin.</strong></p>
        </div>
        <?php
    }
}
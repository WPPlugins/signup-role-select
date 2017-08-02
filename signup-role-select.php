<?php

/**
 * Plugin Name: Sign up Role Select
 * Plugin URI:  http://h71.ir
 * Description: Allow users to select specific roles while registering
 * Version:     1.0.0
 * Author:      H71
 * Author URI:  http://H71.ir
 * License:     GPLv3
 * License URI: http://www.gnu.org/licenses/gpl.html
 * Text Domain: signup-role-select
 * Domain Path: /languages
 */

// Die if file called directly
if ( ! defined('WPINC') ) {
	die;
}

// Include settings dependencies in admin only
if ( is_admin() ) {
	require_once 'admin/class-srs-settings-api.php';
	require_once 'admin/class-srs-settings.php';
	new SRS_Settings;
}

// Function for getting plugin options
function srs_get_option( $option, $section, $default = '' ) {
    if ( empty( $option ) )
        return;
    $options = get_option( $section );
    if ( isset( $options[$option] ) ) {
        return $options[$option];
    }
    return $default;
}

require_once 'class-srs-main.php';
new SRS_Main;

function srs_i18n() {

    load_plugin_textdomain( 'signup-role-select', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
    
}

add_action( 'plugins_loaded', 'srs_i18n' );
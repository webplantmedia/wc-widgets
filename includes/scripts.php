<?php
/**
 * This file loads the CSS and JS necessary for your shortcodes display
 * @package wc Shortcodes Plugin
 * @since 1.0
 * @author AJ Clarke : http://wpexplorer.com
 * @copyright Copyright (c) 2012, AJ Clarke
 * @link http://wpexplorer.com
 * @License: GNU General Public License version 2.0
 * @License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
if( !function_exists ('wc_widgets_scripts') ) :
	function wc_widgets_scripts() {
		$ver = WC_WIDGETS_VERSION;

		wp_enqueue_style( 'wc-widgets-style', plugin_dir_url( __FILE__ ) . 'css/style.css', array( ), $ver );

		wp_register_script( 'pinterest', '//assets.pinterest.com/js/pinit.js', array(), false, true);
	}
	add_action('wp_enqueue_scripts', 'wc_widgets_scripts');
endif;

function wc_widgets_enqueue_admin_scripts() {
	$screen = get_current_screen();

	if ( 'widgets' == $screen->id ) {
		wp_register_style( 'wc-widgets-style', plugin_dir_url( __FILE__ ) . 'css/admin.css', array(), WC_WIDGETS_VERSION, 'all' );
		wp_enqueue_style( 'wc-widgets-style' );

		wp_enqueue_media();
		wp_register_script( 'wc-widgets-admin-js', plugin_dir_url( __FILE__ ) . 'js/admin.js', array ( 'jquery' ), WC_WIDGETS_VERSION, true );
		wp_enqueue_script( 'wc-widgets-admin-js' );
	}
}
add_action('admin_enqueue_scripts', 'wc_widgets_enqueue_admin_scripts' );

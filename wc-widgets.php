<?php
/*
Plugin Name: WordPress Canvas Widgets
Plugin URI: http://wordpresscanvas.com/features/widgets/wordpress-canvas-widgets/
Description: Add much needed widgets to your blog. Pinterest Widget, About Me Widget, Image Widget.
Author: Chris Baldelomar
Author URI: http://webplantmedia.com/
Version: 1.1
License: GPLv2 or later
*/

define( 'WC_WIDGETS_VERSION', '1.1' );
define( 'WC_WIDGETS_PREFIX', 'wc_widgets_' );
define( '_WC_WIDGETS_PREFIX', '_wc_widgets_' );

global $wc_widgets_options;

// require_once( dirname(__FILE__) . '/includes/functions.php' ); // Adds basic filters and actions
require_once( dirname(__FILE__) . '/includes/scripts.php' ); // Adds plugin JS and CSS
require_once( dirname(__FILE__) . '/includes/widgets.php' ); // include any widgets

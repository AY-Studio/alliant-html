<?php
/**
 * AY AIP Base functions and definitions.
 */

define( 'AY_AIP_BASE_VERSION', '0.1.0' );
define( 'AY_AIP_BASE_DIR', get_template_directory() );
define( 'AY_AIP_BASE_URI', get_template_directory_uri() );

require AY_AIP_BASE_DIR . '/inc/helpers.php';
require AY_AIP_BASE_DIR . '/inc/setup.php';
require AY_AIP_BASE_DIR . '/inc/enqueue.php';
require AY_AIP_BASE_DIR . '/inc/customizer.php';
require AY_AIP_BASE_DIR . '/inc/acf-options.php';
require AY_AIP_BASE_DIR . '/inc/blocks.php';
require AY_AIP_BASE_DIR . '/inc/demo-import.php';

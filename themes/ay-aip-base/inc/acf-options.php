<?php
/**
 * ACF options page + JSON paths.
 */

add_filter( 'acf/settings/save_json', function () {
    return AY_AIP_BASE_DIR . '/acf-json';
} );

add_filter( 'acf/settings/load_json', function ( $paths ) {
    $paths[] = AY_AIP_BASE_DIR . '/acf-json';
    return $paths;
} );

add_action( 'acf/init', function () {
    if ( function_exists( 'acf_add_options_page' ) ) {
        acf_add_options_page( [
            'page_title' => __( 'Theme Settings', 'ay-aip-base' ),
            'menu_title' => __( 'Theme Settings', 'ay-aip-base' ),
            'menu_slug'  => 'ay-theme-settings',
            'capability' => 'edit_theme_options',
            'redirect'   => false,
        ] );
    }
} );

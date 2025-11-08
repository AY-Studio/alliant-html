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

foreach ( [
    'theme_heading_font_choice',
    'theme_body_font_choice',
    'theme_heading_h1_font_choice',
    'theme_heading_h2_font_choice',
    'theme_heading_h3_font_choice',
    'theme_heading_h4_font_choice',
    'theme_heading_h5_font_choice',
    'theme_heading_h6_font_choice',
    'theme_nav_font_choice',
] as $font_field_name ) {
    add_filter( "acf/load_field/name={$font_field_name}", 'ay_aip_base_populate_font_choices' );
}
function ay_aip_base_populate_font_choices( $field ) {
    $choices = ay_aip_base_get_font_choices_dropdown();
    if ( ! empty( $choices ) ) {
        $field['choices'] = $choices;
    }
    $field['ui'] = 1;
    $field['ajax'] = 0;
    return $field;
}

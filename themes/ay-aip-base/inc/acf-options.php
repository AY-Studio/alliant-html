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

add_filter( 'acf/load_field/name=theme_heading_font_choice', 'ay_aip_base_populate_font_choices' );
add_filter( 'acf/load_field/name=theme_body_font_choice', 'ay_aip_base_populate_font_choices' );
function ay_aip_base_populate_font_choices( $field ) {
    $choices = ay_aip_base_get_font_choices_dropdown();
    if ( ! empty( $choices ) ) {
        $field['choices'] = $choices;
    }
    $field['ui'] = 1;
    $field['ajax'] = 0;
    return $field;
}

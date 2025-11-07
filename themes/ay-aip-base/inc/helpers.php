<?php
/**
 * Theme helper functions.
 */

function ay_aip_base_get_google_fonts() {
    return [
        'inter' => [
            'label'  => 'Inter',
            'stack'  => '\'Inter\', -apple-system, BlinkMacSystemFont, \'Segoe UI\', sans-serif',
            'request'=> 'Inter:wght@400;500;600;700',
        ],
        'mulish' => [
            'label'  => 'Mulish',
            'stack'  => '\'Mulish\', -apple-system, BlinkMacSystemFont, \'Segoe UI\', sans-serif',
            'request'=> 'Mulish:wght@400;500;600;700',
        ],
        'source-sans' => [
            'label'  => 'Source Sans 3',
            'stack'  => '\'Source Sans 3\', -apple-system, BlinkMacSystemFont, \'Segoe UI\', sans-serif',
            'request'=> 'Source+Sans+3:wght@400;600;700',
        ],
        'space-grotesk' => [
            'label'  => 'Space Grotesk',
            'stack'  => '\'Space Grotesk\', -apple-system, BlinkMacSystemFont, \'Segoe UI\', sans-serif',
            'request'=> 'Space+Grotesk:wght@400;500;600;700',
        ],
        'manrope' => [
            'label'  => 'Manrope',
            'stack'  => '\'Manrope\', -apple-system, BlinkMacSystemFont, \'Segoe UI\', sans-serif',
            'request'=> 'Manrope:wght@400;500;600;700',
        ],
    ];
}

function ay_aip_base_get_font_slug( $mod_key, $option_key ) {
    $option_value = function_exists( 'get_field' ) ? get_field( $option_key, 'option' ) : '';
    if ( $option_value ) {
        return ay_aip_base_sanitize_font_choice( $option_value );
    }
    return get_theme_mod( $mod_key, 'mulish' );
}

function ay_aip_base_get_font_choice( $slug, $fallback = 'inter' ) {
    $fonts = ay_aip_base_get_google_fonts();
    if ( isset( $fonts[ $slug ] ) ) {
        return $fonts[ $slug ];
    }
    return $fonts[ $fallback ];
}

function ay_aip_base_sanitize_font_choice( $value ) {
    $fonts = ay_aip_base_get_google_fonts();
    return isset( $fonts[ $value ] ) ? $value : 'inter';
}

function ay_aip_base_get_google_fonts_url() {
    $fonts        = ay_aip_base_get_google_fonts();
    $heading_slug = ay_aip_base_get_font_slug( 'ay_aip_base_heading_font', 'theme_heading_font_choice' );
    $body_slug    = ay_aip_base_get_font_slug( 'ay_aip_base_body_font', 'theme_body_font_choice' );
    $selected     = array_unique( [ $heading_slug, $body_slug ] );

    $families = [];
    foreach ( $selected as $slug ) {
        if ( isset( $fonts[ $slug ] ) ) {
            $families[] = 'family=' . str_replace( ' ', '+', $fonts[ $slug ]['request'] );
        }
    }

    if ( empty( $families ) ) {
        return '';
    }

    return 'https://fonts.googleapis.com/css2?' . implode( '&', $families ) . '&display=swap';
}

function ay_aip_base_get_theme_option_value( $key, $default = '' ) {
    if ( function_exists( 'get_field' ) ) {
        $value = get_field( $key, 'option' );
        if ( $value ) {
            return $value;
        }
    }
    return $default;
}

function ay_aip_base_get_theme_color( $customizer_key, $option_key, $default ) {
    $option_value = ay_aip_base_get_theme_option_value( $option_key );
    if ( $option_value ) {
        return $option_value;
    }
    $mod_value = get_theme_mod( $customizer_key, '' );
    if ( $mod_value ) {
        return $mod_value;
    }
    return $default;
}

function ay_aip_base_get_nav_background_color() {
    return ay_aip_base_get_theme_color( 'ay_aip_base_nav_background', 'theme_nav_background', '#223a69' );
}

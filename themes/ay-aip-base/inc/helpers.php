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
    $heading_slug = get_theme_mod( 'ay_aip_base_heading_font', 'inter' );
    $body_slug    = get_theme_mod( 'ay_aip_base_body_font', 'inter' );
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

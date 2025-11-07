<?php
/**
 * Theme helper functions.
 */

function ay_aip_base_get_google_fonts() {
    static $fonts = null;
    if ( null !== $fonts ) {
        return $fonts;
    }
    $fonts = ay_aip_base_fetch_google_fonts_metadata();
    if ( empty( $fonts ) ) {
        $fonts = ay_aip_base_fallback_fonts();
    }
    return $fonts;
}

function ay_aip_base_fetch_google_fonts_metadata() {
    $cached = get_transient( 'ay_aip_base_google_fonts' );
    if ( false !== $cached ) {
        return $cached;
    }

    $api_key = ay_aip_base_get_google_fonts_api_key();
    if ( empty( $api_key ) ) {
        return ay_aip_base_fallback_fonts();
    }

    $endpoint = add_query_arg(
        [
            'key'  => $api_key,
            'sort' => 'popularity',
        ],
        'https://www.googleapis.com/webfonts/v1/webfonts'
    );

    $response = wp_remote_get( $endpoint, [ 'timeout' => 15 ] );
    if ( is_wp_error( $response ) ) {
        return ay_aip_base_fallback_fonts();
    }

    $body = wp_remote_retrieve_body( $response );
    if ( empty( $body ) ) {
        return ay_aip_base_fallback_fonts();
    }

    $data = json_decode( $body, true );
    if ( empty( $data['items'] ) ) {
        return ay_aip_base_fallback_fonts();
    }

    $fonts = [];
    foreach ( $data['items'] as $item ) {
        if ( empty( $item['family'] ) ) {
            continue;
        }
        $family   = $item['family'];
        $slug     = sanitize_title( $family );
        $category = isset( $item['category'] ) ? $item['category'] : 'sans-serif';
        $stack    = sprintf( "'%s', %s", $family, ay_aip_base_font_category_stack( $category ) );
        $fonts[ $slug ] = [
            'label'    => $family,
            'stack'    => $stack,
            'request'  => rawurlencode( $family ) . ':ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700',
            'variants' => isset( $item['variants'] ) ? (array) $item['variants'] : [],
            'subsets'  => isset( $item['subsets'] ) ? (array) $item['subsets'] : [],
        ];
    }

    if ( empty( $fonts ) ) {
        $fonts = ay_aip_base_fallback_fonts();
    }

    set_transient( 'ay_aip_base_google_fonts', $fonts, WEEK_IN_SECONDS );
    return $fonts;
}

function ay_aip_base_fallback_fonts() {
    return [
        'inter' => [ 'label' => 'Inter', 'stack' => "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif", 'request' => 'Inter:wght@400;500;600;700' ],
        'mulish' => [ 'label' => 'Mulish', 'stack' => "'Mulish', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif", 'request' => 'Mulish:wght@400;500;600;700' ],
        'source-sans' => [ 'label' => 'Source Sans 3', 'stack' => "'Source Sans 3', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif", 'request' => 'Source+Sans+3:wght@400;600;700' ],
        'space-grotesk' => [ 'label' => 'Space Grotesk', 'stack' => "'Space Grotesk', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif", 'request' => 'Space+Grotesk:wght@400;500;600;700' ],
        'manrope' => [ 'label' => 'Manrope', 'stack' => "'Manrope', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif", 'request' => 'Manrope:wght@400;500;600;700' ],
    ];
}

function ay_aip_base_font_category_stack( $category ) {
    switch ( $category ) {
        case 'serif':
            return 'Georgia, Cambria, "Times New Roman", Times, serif';
        case 'display':
        case 'handwriting':
            return '"Helvetica Neue", Helvetica, Arial, sans-serif';
        case 'monospace':
            return '"Courier New", Courier, monospace';
        default:
            return '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif';
    }
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
            $families[] = 'family=' . $fonts[ $slug ]['request'];
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

function ay_aip_base_get_font_choices_dropdown() {
    $fonts = ay_aip_base_get_google_fonts();
    $choices = [];
    foreach ( $fonts as $slug => $font ) {
        $choices[ $slug ] = $font['label'];
    }
    natcasesort( $choices );
    return $choices;
}

function ay_aip_base_get_google_fonts_api_key() {
    if ( defined( 'AY_GOOGLE_FONTS_API_KEY' ) && AY_GOOGLE_FONTS_API_KEY ) {
        return AY_GOOGLE_FONTS_API_KEY;
    }
    $option_value = ay_aip_base_get_theme_option_value( 'theme_google_fonts_api_key' );
    if ( $option_value ) {
        return $option_value;
    }
    return 'AIzaSyAUaND6Jsu_ABtWFHeZ4rh4u8kZO3VbBVc';
}

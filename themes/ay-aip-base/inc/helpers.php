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

function ay_aip_base_get_font_slug( $mod_key, $option_key, $default = 'mulish', $fallback_option_key = '' ) {
    $option_value = ay_aip_base_get_theme_option_value( $option_key );
    if ( $option_value ) {
        return ay_aip_base_sanitize_font_choice( $option_value, $default );
    }

    if ( $fallback_option_key ) {
        $fallback_value = ay_aip_base_get_theme_option_value( $fallback_option_key );
        if ( $fallback_value ) {
            return ay_aip_base_sanitize_font_choice( $fallback_value, $default );
        }
    }

    $mod_value = get_theme_mod( $mod_key, $default );
    return ay_aip_base_sanitize_font_choice( $mod_value, $default );
}

function ay_aip_base_get_font_choice( $slug, $fallback = 'inter' ) {
    $fonts = ay_aip_base_get_google_fonts();
    if ( isset( $fonts[ $slug ] ) ) {
        return $fonts[ $slug ];
    }
    return $fonts[ $fallback ];
}

function ay_aip_base_sanitize_font_choice( $value, $fallback = 'mulish' ) {
    $fonts = ay_aip_base_get_google_fonts();
    if ( isset( $fonts[ $value ] ) ) {
        return $value;
    }

    return isset( $fonts[ $fallback ] ) ? $fallback : 'mulish';
}

function ay_aip_base_get_theme_asset_url( $relative_path ) {
    $relative_path = ltrim( (string) $relative_path, '/' );
    return trailingslashit( get_template_directory_uri() ) . $relative_path;
}

function ay_aip_base_get_icon_markup( $item, $keys = [] ) {
    $defaults = [
        'svg'   => 'icon_svg',
        'image' => 'icon_image',
        'class' => 'icon_class',
    ];
    $keys = wp_parse_args( $keys, $defaults );

    if ( ! empty( $item[ $keys['svg'] ] ) ) {
        return wp_kses_post( $item[ $keys['svg'] ] );
    }

    if ( ! empty( $item[ $keys['image'] ]['url'] ) ) {
        $image = $item[ $keys['image'] ];
        $alt   = isset( $image['alt'] ) ? $image['alt'] : ( $item['title'] ?? '' );
        return sprintf( '<img src="%s" alt="%s">', esc_url( $image['url'] ), esc_attr( $alt ) );
    }

    if ( ! empty( $item[ $keys['class'] ] ) ) {
        return sprintf( '<i class="%s" aria-hidden="true"></i>', esc_attr( $item[ $keys['class'] ] ) );
    }

    return '';
}

function ay_aip_base_get_block_field( $key, $default = null ) {
    if ( function_exists( 'get_sub_field' ) ) {
        $value = get_sub_field( $key );
        if ( null !== $value ) {
            return $value;
        }
    }
    if ( function_exists( 'get_field' ) ) {
        $value = get_field( $key );
        if ( null !== $value ) {
            return $value;
        }
    }
    return $default;
}

function ay_aip_base_get_typography_size_value( $mod_key, $option_key, $default ) {
    $option_value = ay_aip_base_get_theme_option_value( $option_key );
    if ( $option_value ) {
        return sanitize_text_field( $option_value );
    }
    $mod_value = get_theme_mod( $mod_key, '' );
    if ( $mod_value ) {
        return sanitize_text_field( $mod_value );
    }
    return $default;
}

function ay_aip_base_get_typography_settings() {
    $config = [
        'body' => [
            'option_font'      => 'theme_body_font_choice',
            'mod_font'         => 'ay_aip_base_body_font',
            'default_font'     => 'mulish',
            'option_size'      => 'theme_body_font_size',
            'mod_size'         => 'ay_aip_base_body_font_size',
            'default_size'     => '1rem',
            'fallback_option'  => '',
        ],
        'h1' => [
            'option_font'      => 'theme_heading_h1_font_choice',
            'mod_font'         => 'ay_aip_base_heading_font',
            'default_font'     => 'mulish',
            'option_size'      => 'theme_heading_h1_font_size',
            'mod_size'         => 'ay_aip_base_heading_h1_font_size',
            'default_size'     => 'calc(1.375rem + 1.5vw)',
            'fallback_option'  => 'theme_heading_font_choice',
        ],
        'h2' => [
            'option_font'      => 'theme_heading_h2_font_choice',
            'mod_font'         => 'ay_aip_base_heading_font',
            'default_font'     => 'mulish',
            'option_size'      => 'theme_heading_h2_font_size',
            'mod_size'         => 'ay_aip_base_heading_h2_font_size',
            'default_size'     => 'calc(1.325rem + 0.9vw)',
            'fallback_option'  => 'theme_heading_font_choice',
        ],
        'h3' => [
            'option_font'      => 'theme_heading_h3_font_choice',
            'mod_font'         => 'ay_aip_base_heading_font',
            'default_font'     => 'mulish',
            'option_size'      => 'theme_heading_h3_font_size',
            'mod_size'         => 'ay_aip_base_heading_h3_font_size',
            'default_size'     => 'calc(1.3rem + 0.6vw)',
            'fallback_option'  => 'theme_heading_font_choice',
        ],
        'h4' => [
            'option_font'      => 'theme_heading_h4_font_choice',
            'mod_font'         => 'ay_aip_base_heading_font',
            'default_font'     => 'mulish',
            'option_size'      => 'theme_heading_h4_font_size',
            'mod_size'         => 'ay_aip_base_heading_h4_font_size',
            'default_size'     => 'calc(1.275rem + 0.3vw)',
            'fallback_option'  => 'theme_heading_font_choice',
        ],
        'h5' => [
            'option_font'      => 'theme_heading_h5_font_choice',
            'mod_font'         => 'ay_aip_base_heading_font',
            'default_font'     => 'mulish',
            'option_size'      => 'theme_heading_h5_font_size',
            'mod_size'         => 'ay_aip_base_heading_h5_font_size',
            'default_size'     => '1.25rem',
            'fallback_option'  => 'theme_heading_font_choice',
        ],
        'h6' => [
            'option_font'      => 'theme_heading_h6_font_choice',
            'mod_font'         => 'ay_aip_base_heading_font',
            'default_font'     => 'mulish',
            'option_size'      => 'theme_heading_h6_font_size',
            'mod_size'         => 'ay_aip_base_heading_h6_font_size',
            'default_size'     => '1rem',
            'fallback_option'  => 'theme_heading_font_choice',
        ],
        'nav' => [
            'option_font'      => 'theme_nav_font_choice',
            'mod_font'         => 'ay_aip_base_nav_font',
            'default_font'     => 'mulish',
            'option_size'      => 'theme_nav_font_size',
            'mod_size'         => 'ay_aip_base_nav_font_size',
            'default_size'     => '1rem',
            'fallback_option'  => 'theme_heading_font_choice',
        ],
    ];

    $settings = [];
    foreach ( $config as $key => $args ) {
        $slug = ay_aip_base_get_font_slug(
            $args['mod_font'],
            $args['option_font'],
            $args['default_font'],
            $args['fallback_option']
        );
        $settings[ $key ] = [
            'slug' => $slug,
            'font' => ay_aip_base_get_font_choice( $slug, $args['default_font'] ),
            'size' => ay_aip_base_get_typography_size_value( $args['mod_size'], $args['option_size'], $args['default_size'] ),
        ];
    }

    return $settings;
}

function ay_aip_base_render_page_sections( $page_id = 0 ) {
    if ( ! function_exists( 'have_rows' ) ) {
        return false;
    }

    if ( ! $page_id ) {
        $page_id = get_the_ID();
    }

    $layout_templates = [
        'hero'              => 'hero',
        'hero_section'      => 'hero-section',
        'card_grid'         => 'card-grid',
        'stats'             => 'stats',
        'cta_banner'        => 'cta-banner',
        'contact_form'      => 'contact-form',
        'team_grid'         => 'team-grid',
        'about_team'        => 'about-team',
        'text_content'      => 'text-content',
        'icon_features'     => 'icon-features',
        'values'            => 'values',
        'value_cards'       => 'value-cards',
        'media_content'     => 'media-content',
        'testimonial'       => 'testimonial',
        'pricing'           => 'pricing-table',
        'video'             => 'video',
        'accordion'         => 'accordion',
        'gallery'           => 'gallery-grid',
        'logo_grid'         => 'logo-grid',
        'locations'         => 'locations',
        'careers'           => 'careers',
        'product_offerings' => 'product-offerings',
    ];

    if ( ! have_rows( 'page_sections', $page_id ) ) {
        return false;
    }

    while ( have_rows( 'page_sections', $page_id ) ) {
        the_row();
        $layout = get_row_layout();
        if ( empty( $layout_templates[ $layout ] ) ) {
            continue;
        }
        get_template_part( 'template-parts/blocks/' . $layout_templates[ $layout ] );
    }

    return true;
}

function ay_aip_base_get_google_fonts_url() {
    $fonts      = ay_aip_base_get_google_fonts();
    $typography = ay_aip_base_get_typography_settings();
    $selected   = [];

    foreach ( $typography as $setting ) {
        if ( ! empty( $setting['slug'] ) ) {
            $selected[] = $setting['slug'];
        }
    }

    $selected = array_unique( $selected );
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

<?php
/**
 * Customizer settings for design tokens.
 */

add_action( 'customize_register', 'ay_aip_base_customize_register' );
function ay_aip_base_customize_register( $wp_customize ) {
    $wp_customize->add_section( 'ay_aip_base_design', [
        'title'    => __( 'Design Settings', 'ay-aip-base' ),
        'priority' => 30,
    ] );

    $font_choices = [];
    foreach ( ay_aip_base_get_google_fonts() as $slug => $data ) {
        $font_choices[ $slug ] = $data['label'];
    }

    $settings = [
        'heading_font'   => [
            'default'     => 'inter',
            'label'       => __( 'Heading Font', 'ay-aip-base' ),
            'description' => __( 'Select a Google Font for headings. Loaded automatically on the front end.', 'ay-aip-base' ),
            'type'        => 'select',
            'choices'     => $font_choices,
        ],
        'body_font'      => [
            'default'     => 'inter',
            'label'       => __( 'Body Font', 'ay-aip-base' ),
            'description' => __( 'Choose the font used for paragraphs, navigation, and UI text.', 'ay-aip-base' ),
            'type'        => 'select',
            'choices'     => $font_choices,
        ],
        'primary_color'  => [ 'default' => '#1f3a63', 'label' => __( 'Primary Color', 'ay-aip-base' ) ],
        'accent_color'   => [ 'default' => '#4a7dff', 'label' => __( 'Accent Color', 'ay-aip-base' ) ],
        'heading_color'  => [ 'default' => '#111827', 'label' => __( 'Heading Color', 'ay-aip-base' ) ],
        'body_color'     => [ 'default' => '#4b5563', 'label' => __( 'Body Text Color', 'ay-aip-base' ) ],
        'background_color' => [ 'default' => '#ffffff', 'label' => __( 'Background Color', 'ay-aip-base' ) ],
    ];

    foreach ( $settings as $key => $args ) {
        $setting_id = "ay_aip_base_{$key}";
        if ( 'heading_font' === $key || 'body_font' === $key ) {
            $sanitize = 'ay_aip_base_sanitize_font_choice';
        } elseif ( false !== strpos( $key, 'color' ) ) {
            $sanitize = 'sanitize_hex_color';
        } else {
            $sanitize = 'sanitize_text_field';
        }
        $wp_customize->add_setting( $setting_id, [
            'default'           => $args['default'],
            'sanitize_callback' => $sanitize,
        ] );

        $control_args = [
            'section' => 'ay_aip_base_design',
            'label'   => $args['label'],
            'description' => isset( $args['description'] ) ? $args['description'] : '',
        ];

        if ( isset( $args['type'] ) && 'select' === $args['type'] ) {
            $control_args['type']    = 'select';
            $control_args['choices'] = $args['choices'];
            $wp_customize->add_control( $setting_id, $control_args );
            continue;
        }

        if ( false !== strpos( $key, 'color' ) ) {
            $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting_id, $control_args ) );
        } else {
            $wp_customize->add_control( $setting_id, $control_args );
        }
    }
}

add_action( 'wp_head', 'ay_aip_base_print_theme_tokens', 20 );
function ay_aip_base_print_theme_tokens() {
    $heading_font = ay_aip_base_get_font_choice( get_theme_mod( 'ay_aip_base_heading_font', 'inter' ) );
    $body_font    = ay_aip_base_get_font_choice( get_theme_mod( 'ay_aip_base_body_font', 'inter' ) );

    $tokens = [
        '--ay-font-heading'   => $heading_font['stack'],
        '--ay-font-body'      => $body_font['stack'],
        '--ay-color-primary'  => get_theme_mod( 'ay_aip_base_primary_color', '#1f3a63' ),
        '--ay-color-accent'   => get_theme_mod( 'ay_aip_base_accent_color', '#4a7dff' ),
        '--ay-color-heading'  => get_theme_mod( 'ay_aip_base_heading_color', '#111827' ),
        '--ay-color-body'     => get_theme_mod( 'ay_aip_base_body_color', '#4b5563' ),
        '--ay-color-background' => get_theme_mod( 'ay_aip_base_background_color', '#ffffff' ),
    ];
    echo "<style id='ay-aip-base-design-tokens'>:root";
    echo '{';
    foreach ( $tokens as $var => $value ) {
        echo esc_html( $var ) . ':' . $value . ';'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
    echo "}</style>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

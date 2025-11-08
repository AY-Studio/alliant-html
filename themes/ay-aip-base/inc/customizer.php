<?php
/**
 * Customizer settings for design tokens.
 */

add_action( 'customize_register', 'ay_aip_base_customize_register' );
function ay_aip_base_customize_register( $wp_customize ) {
    if ( ! class_exists( 'AY_AIP_Base_Font_Control' ) ) {
        class AY_AIP_Base_Font_Control extends WP_Customize_Control {
            public $type = 'ay-font-select';

            public function render_content() {
                $choices = ay_aip_base_get_font_choices_dropdown();
                if ( empty( $choices ) ) {
                    echo '<p>' . esc_html__( 'Unable to load fonts list. Please try again later.', 'ay-aip-base' ) . '</p>';
                    return;
                }

                if ( ! empty( $this->label ) ) {
                    echo '<span class="customize-control-title">' . esc_html( $this->label ) . '</span>';
                }
                if ( ! empty( $this->description ) ) {
                    echo '<span class="description customize-control-description">' . esc_html( $this->description ) . '</span>';
                }

                printf( '<select class="ay-font-select-control" %s>', $this->get_link() );
                foreach ( $choices as $value => $label ) {
                    printf( '<option value="%1$s" %2$s>%3$s</option>', esc_attr( $value ), selected( $this->value(), $value, false ), esc_html( $label ) );
                }
                echo '</select>';
            }
        }
    }

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
            'default'     => 'mulish',
            'label'       => __( 'Heading Font', 'ay-aip-base' ),
            'description' => __( 'Search and select any Google Font for headings.', 'ay-aip-base' ),
            'type'        => 'font',
        ],
        'body_font'      => [
            'default'     => 'mulish',
            'label'       => __( 'Body Font', 'ay-aip-base' ),
            'description' => __( 'Search and select any Google Font for body text.', 'ay-aip-base' ),
            'type'        => 'font',
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

        if ( isset( $args['type'] ) && 'font' === $args['type'] ) {
            $wp_customize->add_control( new AY_AIP_Base_Font_Control( $wp_customize, $setting_id, $control_args ) );
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
    $typography     = ay_aip_base_get_typography_settings();
    $nav_background = ay_aip_base_get_nav_background_color();

    $tokens = [
        '--ay-color-primary'    => get_theme_mod( 'ay_aip_base_primary_color', '#1f3a63' ),
        '--ay-color-accent'     => get_theme_mod( 'ay_aip_base_accent_color', '#4a7dff' ),
        '--ay-color-heading'    => get_theme_mod( 'ay_aip_base_heading_color', '#111827' ),
        '--ay-color-body'       => get_theme_mod( 'ay_aip_base_body_color', '#4b5563' ),
        '--ay-color-background' => get_theme_mod( 'ay_aip_base_background_color', '#ffffff' ),
        '--ay-nav-background'   => $nav_background,
    ];

    foreach ( $typography as $key => $data ) {
        $tokens[ '--ay-font-' . $key ]       = $data['font']['stack'];
        $tokens[ '--ay-font-' . $key . '-size' ] = $data['size'];
    }

    if ( isset( $typography['h1'] ) ) {
        $tokens['--ay-font-heading']        = $typography['h1']['font']['stack'];
        $tokens['--bs-heading-font-family'] = $typography['h1']['font']['stack'];
    }
    if ( isset( $typography['body'] ) ) {
        $tokens['--ay-font-body']         = $typography['body']['font']['stack'];
        $tokens['--bs-font-sans-serif']   = $typography['body']['font']['stack'];
        $tokens['--bs-body-font-family']  = $typography['body']['font']['stack'];
    }
    echo "<style id='ay-aip-base-design-tokens'>:root";
    echo '{';
    foreach ( $tokens as $var => $value ) {
        echo esc_html( $var ) . ':' . $value . ';'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
    echo "}</style>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    $selector_map = [
        'body' => 'body, button, input, optgroup, select, textarea, .tooltip, .popover',
        'nav'  => '.navbar .navbar-nav .nav-link',
        'h1'   => 'h1, .h1',
        'h2'   => 'h2, .h2',
        'h3'   => 'h3, .h3',
        'h4'   => 'h4, .h4',
        'h5'   => 'h5, .h5',
        'h6'   => 'h6, .h6',
    ];
    $gform_selectors = implode(
        ',',
        [
            '.gform_wrapper.gravity-theme .ginput_container input[type=text]',
            '.gform_wrapper.gravity-theme .ginput_container input[type=email]',
            '.gform_wrapper.gravity-theme .ginput_container input[type=tel]',
            '.gform_wrapper.gravity-theme .ginput_container textarea',
        ]
    );

    $font_rules = [];
    foreach ( $selector_map as $key => $selectors ) {
        if ( ! isset( $typography[ $key ] ) ) {
            continue;
        }
        $font_rules[] = $selectors . '{font-family:var(--ay-font-' . $key . ') !important;font-size:var(--ay-font-' . $key . '-size) !important;}';
    }
    $font_rules[] = $gform_selectors . '{font-family:var(--ay-font-body) !important;font-size:var(--ay-font-body-size) !important;}';

    echo "<style id='ay-aip-base-font-overrides'>" . implode( '', $font_rules ) . '</style>';
    echo "<style id='ay-aip-base-nav-style'>.navbar{background-color:var(--ay-nav-background);}html.is-animating{background-color:var(--ay-nav-background);}</style>";
}

add_action( 'customize_controls_enqueue_scripts', 'ay_aip_base_customize_assets' );
function ay_aip_base_customize_assets() {
    wp_enqueue_style( 'selectWoo' );
    wp_enqueue_script( 'selectWoo' );
    wp_enqueue_style( 'ay-aip-base-customizer', AY_AIP_BASE_URI . '/assets/css/customizer.css', [], AY_AIP_BASE_VERSION );
    wp_enqueue_script( 'ay-aip-base-customizer', AY_AIP_BASE_URI . '/assets/js/customizer.js', [ 'jquery', 'customize-controls', 'selectWoo' ], AY_AIP_BASE_VERSION, true );
}

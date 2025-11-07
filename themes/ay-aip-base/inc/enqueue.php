<?php
/**
 * Enqueue scripts and styles.
 */

add_action( 'wp_enqueue_scripts', 'ay_aip_base_enqueue_assets' );
function ay_aip_base_enqueue_assets() {
    $version = AY_AIP_BASE_VERSION;

    $fonts_url = ay_aip_base_get_google_fonts_url();
    if ( $fonts_url ) {
        wp_enqueue_style( 'ay-aip-base-fonts', $fonts_url, [], null );
    }

    wp_enqueue_style( 'ay-aip-base-aos', 'https://cdn.jsdelivr.net/npm/aos@3.0.0-beta.6/dist/aos.min.css', [], '3.0.0-beta.6' );
    wp_enqueue_style(
        'ay-aip-base-fontawesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
        [],
        '6.5.1'
    );

    wp_enqueue_style(
        'ay-aip-base-style',
        get_stylesheet_uri(),
        [ 'ay-aip-base-fonts', 'ay-aip-base-fontawesome' ],
        $version
    );

    wp_enqueue_script( 'ay-aip-base-swup', 'https://cdn.jsdelivr.net/npm/swup@4.8.2/dist/swup.min.js', [], '4.8.2', true );
    wp_enqueue_script( 'ay-aip-base-aos', 'https://cdn.jsdelivr.net/npm/aos@3.0.0-beta.6/dist/aos.min.js', [], '3.0.0-beta.6', true );
    wp_enqueue_script( 'ay-aip-base-main', AY_AIP_BASE_URI . '/assets/js/main.js', [ 'ay-aip-base-swup', 'ay-aip-base-aos' ], $version, true );
}

add_action( 'enqueue_block_editor_assets', 'ay_aip_base_enqueue_editor_assets' );
function ay_aip_base_enqueue_editor_assets() {
    $fonts_url = ay_aip_base_get_google_fonts_url();
    if ( $fonts_url ) {
        wp_enqueue_style( 'ay-aip-base-editor-fonts', $fonts_url, [], null );
    }
    wp_enqueue_style( 'ay-aip-base-editor-fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css', [], '6.5.1' );
    wp_enqueue_style( 'ay-aip-base-editor', AY_AIP_BASE_URI . '/assets/css/editor.css', [], AY_AIP_BASE_VERSION );
}

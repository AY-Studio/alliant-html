<?php
/**
 * Theme setup.
 */

add_action( 'after_setup_theme', 'ay_aip_base_setup' );
function ay_aip_base_setup() {
    load_theme_textdomain( 'ay-aip-base', AY_AIP_BASE_DIR . '/languages' );

    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'editor-styles' );
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'custom-logo', [
        'height'      => 64,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ] );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );

    register_nav_menus( [
        'primary' => __( 'Primary Menu', 'ay-aip-base' ),
        'footer'  => __( 'Footer Menu', 'ay-aip-base' ),
    ] );

    add_editor_style( 'assets/css/editor.css' );
}

add_action( 'init', 'ay_aip_base_register_image_sizes' );
function ay_aip_base_register_image_sizes() {
    add_image_size( 'ay_aip_base_hero', 2000, 1200, true );
    add_image_size( 'ay_aip_base_card', 800, 600, true );
    add_image_size( 'ay_aip_base_square', 600, 600, true );
}

add_filter( 'nav_menu_css_class', 'ay_aip_base_nav_item_classes', 10, 4 );
function ay_aip_base_nav_item_classes( $classes, $item, $args, $depth ) {
    if ( isset( $args->theme_location ) && 'primary' === $args->theme_location ) {
        $classes[] = 'nav-item';
    }
    if ( isset( $args->theme_location ) && 'footer' === $args->theme_location ) {
        $classes[] = 'footer-nav__item';
    }
    return $classes;
}

add_filter( 'nav_menu_link_attributes', 'ay_aip_base_nav_link_attributes', 10, 3 );
function ay_aip_base_nav_link_attributes( $atts, $item, $args ) {
    if ( isset( $args->theme_location ) && 'primary' === $args->theme_location ) {
        $atts['class'] = isset( $atts['class'] ) ? $atts['class'] . ' nav-link' : 'nav-link';
    }
    if ( isset( $args->theme_location ) && 'footer' === $args->theme_location ) {
        $atts['class'] = isset( $atts['class'] ) ? $atts['class'] . ' footer-nav__link' : 'footer-nav__link';
    }
    return $atts;
}

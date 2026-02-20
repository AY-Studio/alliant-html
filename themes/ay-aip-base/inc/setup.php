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

add_filter( 'post_type_link', 'ay_aip_base_news_permalink', 10, 2 );
function ay_aip_base_news_permalink( $permalink, $post ) {
    if ( 'post' === $post->post_type ) {
        $permalink = home_url( user_trailingslashit( 'news/' . $post->post_name ) );
    }
    return $permalink;
}

add_action( 'init', 'ay_aip_base_register_news_rewrite' );
function ay_aip_base_register_news_rewrite() {
    add_rewrite_rule( '^news/([^/]+)/?$', 'index.php?name=$matches[1]', 'top' );
}

add_action( 'after_switch_theme', 'ay_aip_base_flush_rewrite' );
function ay_aip_base_flush_rewrite() {
    ay_aip_base_register_news_rewrite();
    flush_rewrite_rules();
}

add_action( 'wp_head', 'ay_aip_base_inline_backgrounds', 30 );
function ay_aip_base_inline_backgrounds() {
    $hero_bg = esc_url( get_template_directory_uri() . '/img/alliant-mission-hero.jpg' );
    echo '<style class="ay-hero-inline">.hero-section{background-image:url(' . $hero_bg . ');}';
    echo '.admin-bar .navbar{top:32px;}@media (max-width:782px){.admin-bar .navbar{top:46px;}}';
    echo '</style>';
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

add_filter( 'use_block_editor_for_post_type', 'ay_aip_base_disable_gutenberg', 10, 2 );
function ay_aip_base_disable_gutenberg( $use_block_editor, $post_type ) {
    return false;
}

add_filter( 'use_widgets_block_editor', '__return_false' );

add_filter( 'upload_mimes', 'ay_aip_base_allow_svg_uploads' );
function ay_aip_base_allow_svg_uploads( $mimes ) {
    $mimes['svg']  = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    return $mimes;
}

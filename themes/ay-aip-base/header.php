<?php
/**
 * Header template.
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <div class="navbar-brand">
            <?php
            $header_logo = ay_aip_base_get_header_logo( get_template_directory_uri() . '/img/alliant-logo-header.svg' );
            if ( $header_logo ) :
                ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="navbar-brand__image">
                    <img src="<?php echo esc_url( $header_logo['url'] ); ?>" alt="<?php echo esc_attr( $header_logo['alt'] ?? get_bloginfo( 'name' ) ); ?>" width="235" height="50">
                </a>
            <?php elseif ( has_custom_logo() ) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="navbar-brand__image">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/img/alliant-logo-header.svg' ); ?>" alt="<?php esc_attr_e( 'Site logo', 'ay-aip-base' ); ?>" width="235" height="50">
                </a>
            <?php endif; ?>
        </div>
        <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle navigation', 'ay-aip-base' ); ?>">
            <span class="navbar-toggler-icon" aria-hidden="true">
                <span class="toggler-line"></span>
                <span class="toggler-line"></span>
                <span class="toggler-line"></span>
            </span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <?php
            if ( has_nav_menu( 'primary' ) ) {
                wp_nav_menu( [
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => 'navbar-nav ms-auto',
                    'fallback_cb'    => '__return_empty_string',
                ] );
            } else {
                echo '<ul class="navbar-nav ms-auto"><li class="nav-item"><a class="nav-link" href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">' . esc_html__( 'Assign a menu', 'ay-aip-base' ) . '</a></li></ul>';
            }
            ?>
            <div class="mobile-social-links">
                <a href="#" class="mobile-social-link" aria-label="LinkedIn">
                    <span class="fab fa-linkedin" aria-hidden="true"></span>
                </a>
                <a href="#" class="mobile-social-link" aria-label="Facebook">
                    <span class="fab fa-facebook" aria-hidden="true"></span>
                </a>
                <a href="#" class="mobile-social-link" aria-label="Twitter">
                    <span class="fab fa-twitter" aria-hidden="true"></span>
                </a>
            </div>
        </div>
    </div>
</nav>
<main id="swup" class="transition-fade site-main">

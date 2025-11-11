<?php
/**
 * Footer template.
 */
?>
</main>
<footer class="footer">
    <div class="diagonal-lines diagonal-lines--footer" aria-hidden="true"></div>
    <div class="container">
        <div class="footer-logo">
            <?php
            $footer_logo = ay_aip_base_get_footer_logo( get_template_directory_uri() . '/img/alliant-logo-footer.svg' );
            if ( $footer_logo ) :
                ?>
                <img src="<?php echo esc_url( $footer_logo['url'] ); ?>" alt="<?php echo esc_attr( $footer_logo['alt'] ?? get_bloginfo( 'name' ) ); ?>" width="220" height="52">
            <?php elseif ( has_custom_logo() ) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <img src="<?php echo esc_url( get_template_directory_uri() . '/img/alliant-logo-footer.svg' ); ?>" alt="<?php esc_attr_e( 'Site logo', 'ay-aip-base' ); ?>" width="220" height="52">
            <?php endif; ?>
        </div>
        <?php
        if ( has_nav_menu( 'footer' ) ) {
            wp_nav_menu( [
                'theme_location' => 'footer',
                'container'      => false,
                'menu_class'     => 'footer-nav',
                'items_wrap'     => '<ul class="footer-nav">%3$s</ul>',
                'fallback_cb'    => '__return_empty_string',
            ] );
        } else {
            echo '<ul class="footer-nav"><li class="footer-nav__item"><a class="footer-nav__link" href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">' . esc_html__( 'Assign footer menu', 'ay-aip-base' ) . '</a></li></ul>';
        }
        ?>
        <p class="footer-copy">&copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'ay-aip-base' ); ?></p>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>

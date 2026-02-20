<?php
/**
 * Blog index / Insights page.
 */

global $wp_query;

// Get the posts page ID
$posts_page_id = get_option( 'page_for_posts' );

// Get hero fields
$heading           = get_field( 'news_hero_heading', $posts_page_id );
$subheading        = get_field( 'news_hero_subheading', $posts_page_id );
$background_image  = get_field( 'news_hero_background_image', $posts_page_id );
$overlay_color     = get_field( 'news_hero_overlay_color', $posts_page_id );
$overlay_opacity   = get_field( 'news_hero_overlay_opacity', $posts_page_id );
$text_color        = get_field( 'news_hero_text_color', $posts_page_id );

// Set defaults
if ( ! $heading ) {
    $heading = __( 'News & Insights', 'ay-aip-base' );
}
if ( ! $subheading ) {
    $subheading = __( 'Discover the latest news, announcements, insights, and updates from the AIP Capital and Alliant AirFinance team.', 'ay-aip-base' );
}
if ( ! $overlay_color ) {
    $overlay_color = '#223a69';
}
if ( ! $overlay_opacity ) {
    $overlay_opacity = 30;
}
if ( ! $text_color ) {
    $text_color = '#ffffff';
}

// Prepare background image
$bg_image = null;
if ( $background_image && is_array( $background_image ) && ! empty( $background_image['url'] ) ) {
    $bg_image = $background_image;
} else {
    $bg_image = [
        'url' => get_template_directory_uri() . '/img/alliant-mission-hero.jpg',
        'alt' => $heading,
    ];
}

// Convert overlay color to rgba
if ( function_exists( 'ay_aip_base_hex_to_rgba' ) ) {
    $overlay_rgba = ay_aip_base_hex_to_rgba( $overlay_color, $overlay_opacity / 100 );
} else {
    $overlay_rgba = 'rgba(34, 58, 105, 0.3)';
}

$text_attr = $text_color ? ' style="color:' . esc_attr( $text_color ) . ';"' : '';

get_header();
?>
<section class="hero-section" style="background-image: none;">
    <?php if ( $bg_image && ! empty( $bg_image['url'] ) ) : ?>
        <div class="hero-background">
            <img src="<?php echo esc_url( $bg_image['url'] ); ?>" alt="<?php echo esc_attr( $bg_image['alt'] ?: $heading ); ?>">
        </div>
    <?php endif; ?>
    <div class="hero-overlay" style="background: <?php echo esc_attr( $overlay_rgba ); ?>;"></div>
    <div class="container" style="position: relative; z-index: 2;">
        <div class="row">
            <div class="col-12 text-center">
                <h1<?php echo $text_attr; ?>><?php echo esc_html( $heading ); ?></h1>
                <p class="lead"<?php echo $text_attr; ?> style="max-width: 800px; margin: 0 auto;"><?php echo esc_html( $subheading ); ?></p>
            </div>
        </div>
    </div>
</section>

<section class="section section-light py-6">
    <div class="container">
        <div class="row g-4">
            <?php if ( have_posts() ) :
                $delay = 100;
                while ( have_posts() ) :
                    the_post();
                    $thumb_url = '';
                    if ( has_post_thumbnail() ) {
                        $image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'ay_aip_base_card' );
                        if ( $image ) {
                            $thumb_url = $image[0];
                        }
                    }
                    if ( ! $thumb_url ) {
                        $thumb_url = esc_url( get_template_directory_uri() . '/img/alliant-mission-hero.jpg' );
                    }
                    $date_display = strtoupper( get_the_date( 'F j, Y' ) );
                    ?>
                    <div class="col-12 col-md-6 col-lg-4" data-aos="fade" data-aos-delay="<?php echo esc_attr( $delay ); ?>">
                        <a href="<?php the_permalink(); ?>" class="card border-0 news-card h-100 text-decoration-none">
                            <img src="<?php echo esc_url( $thumb_url ); ?>" class="card-img-top" alt="<?php the_title_attribute(); ?>">
                            <div class="card-body">
                                <p class="news-date text-muted mb-2"><?php echo esc_html( $date_display ); ?></p>
                                <h5 class="card-title mb-3"><?php the_title(); ?></h5>
                                <span class="read-more"><?php esc_html_e( 'READ MORE →', 'ay-aip-base' ); ?></span>
                            </div>
                        </a>
                    </div>
                    <?php
                    $delay += 100;
                    if ( $delay > 300 ) {
                        $delay = 100;
                    }
                endwhile;
            else :
                ?>
                <div class="col-12">
                    <p class="text-center text-muted"><?php esc_html_e( 'No insights available yet. Add a post to get started.', 'ay-aip-base' ); ?></p>
                </div>
            <?php endif; ?>
        </div>

        <?php
        if ( $wp_query->max_num_pages > 1 ) :
            $pagination_links = paginate_links( [
                'mid_size'  => 2,
                'prev_text' => __( 'Previous', 'ay-aip-base' ),
                'next_text' => __( 'Next', 'ay-aip-base' ),
                'type'      => 'array',
            ] );
            if ( $pagination_links ) :
                ?>
            <div class="row mt-5">
                <div class="col-12" data-aos="fade" data-aos-delay="400">
                    <nav aria-label="<?php esc_attr_e( 'News pagination', 'ay-aip-base' ); ?>">
                        <ul class="pagination justify-content-center">
                            <?php
                            foreach ( $pagination_links as $link ) {
                                $link_classes = 'page-item';
                                $is_current   = false !== strpos( $link, 'current' );
                                $is_prev      = false !== strpos( $link, 'prev' );
                                $is_next      = false !== strpos( $link, 'next' );
                                $is_dots      = false !== strpos( $link, 'dots' );
                                $has_href     = false !== strpos( $link, 'href' );

                                if ( $is_current ) {
                                    $link_classes .= ' active';
                                }
                                if ( $is_dots || ( ( $is_prev || $is_next ) && ! $has_href ) ) {
                                    $link_classes .= ' disabled';
                                }

                                $link = str_replace( 'page-numbers', 'page-numbers page-link', $link );
                                echo '<li class="' . esc_attr( $link_classes ) . '">' . $link . '</li>'; // phpcs:ignore WordPress.Security.EscapeOutput
                            }
                            ?>
                        </ul>
                    </nav>
                </div>
            </div>
            <?php
            endif;
        endif;
        ?>
    </div>
</section>
<?php
get_footer();

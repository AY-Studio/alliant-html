<?php
/**
 * Blog index / Insights page.
 */

global $wp_query;

get_header();
?>
<section class="hero-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1><?php esc_html_e( 'News & Insights', 'ay-aip-base' ); ?></h1>
                <p class="lead"><?php esc_html_e( 'Discover the latest news, announcements, insights, and updates from the AIP Capital and Alliant AirFinance team.', 'ay-aip-base' ); ?></p>
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
                        $thumb_url = esc_url( get_template_directory_uri() . '/img/passenger-air-vehicle-parked-on-the-airport-apron-2024-10-18-09-02-37-utc-scaled.jpg' );
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

<?php
/**
 * Blog index / Insights page.
 */

global $wp_query;

get_header();
?>
<section class="hero-section hero-section--short">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1><?php esc_html_e( 'Insights', 'ay-aip-base' ); ?></h1>
                <p class="lead">Stay informed with the latest announcements, financing wins, and market commentary.</p>
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
                    ?>
                    <div class="col-12 col-md-6 col-lg-4" data-aos="fade" data-aos-delay="<?php echo esc_attr( $delay ); ?>">
                        <article class="card border-0 news-card h-100">
                            <a class="text-decoration-none" href="<?php the_permalink(); ?>">
                                <img src="<?php echo esc_url( $thumb_url ); ?>" class="card-img-top" alt="<?php the_title_attribute(); ?>">
                                <div class="card-body">
                                    <p class="news-date text-muted mb-2"><?php echo esc_html( get_the_date( 'F j, Y' ) ); ?></p>
                                    <h5 class="card-title mb-3"><?php the_title(); ?></h5>
                                    <p class="card-text"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 26 ) ); ?></p>
                                    <span class="read-more"><?php esc_html_e( 'READ MORE →', 'ay-aip-base' ); ?></span>
                                </div>
                            </a>
                        </article>
                    </div>
                    <?php
                    $delay += 100;
                endwhile;
            else :
                ?>
                <div class="col-12">
                    <p class="text-center text-muted"><?php esc_html_e( 'No insights available yet. Add a post to get started.', 'ay-aip-base' ); ?></p>
                </div>
            <?php endif; ?>
        </div>

        <?php if ( $wp_query->max_num_pages > 1 ) : ?>
            <div class="row mt-5">
                <div class="col-12" data-aos="fade" data-aos-delay="400">
                    <?php
                    the_posts_pagination( [
                        'mid_size'  => 2,
                        'prev_text' => __( 'Previous', 'ay-aip-base' ),
                        'next_text' => __( 'Next', 'ay-aip-base' ),
                    ] );
                    ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php
get_footer();

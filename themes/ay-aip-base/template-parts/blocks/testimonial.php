<?php
$background = ay_aip_base_get_block_field( 'testimonial_background', 'white' );
$quote      = ay_aip_base_get_block_field( 'testimonial_quote' );
$name       = ay_aip_base_get_block_field( 'testimonial_author_name' );
$title      = ay_aip_base_get_block_field( 'testimonial_author_title' );
$image      = ay_aip_base_get_block_field( 'testimonial_author_image' );

$section_class = 'block-testimonial';
if ( 'light' === $background ) {
    $section_class .= ' bg-light';
}
?>
<section class="<?php echo esc_attr( $section_class ); ?>">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">
                <div class="testimonial-card" data-aos="fade">
                    <div class="testimonial-quote-icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M6 17h3l2-4V7H5v6h3zm8 0h3l2-4V7h-6v6h3z"/>
                        </svg>
                    </div>
                    <?php if ( $quote ) : ?>
                        <blockquote class="testimonial-quote">
                            &ldquo;<?php echo esc_html( $quote ); ?>&rdquo;
                        </blockquote>
                    <?php endif; ?>
                    <div class="testimonial-author">
                        <?php if ( $image ) : ?>
                            <div class="author-image">
                                <img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ?: $name ); ?>">
                            </div>
                        <?php endif; ?>
                        <div class="author-info">
                            <?php if ( $name ) : ?>
                                <div class="author-name"><?php echo esc_html( $name ); ?></div>
                            <?php endif; ?>
                            <?php if ( $title ) : ?>
                                <div class="author-title"><?php echo esc_html( $title ); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

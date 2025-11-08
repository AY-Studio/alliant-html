<?php
$background = ay_aip_base_get_block_field( 'video_background', 'white' );
$title      = ay_aip_base_get_block_field( 'video_title' );
$url        = ay_aip_base_get_block_field( 'video_url' );
$caption    = ay_aip_base_get_block_field( 'video_caption' );

$section_class = 'block-video';
if ( 'light' === $background ) {
    $section_class .= ' bg-light';
}
if ( $url ) {
    $url = esc_url( $url );
}
?>
<section class="<?php echo esc_attr( $section_class ); ?>">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <?php if ( $title ) : ?>
                    <h2 class="block-title text-center mb-5" data-aos="fade"><?php echo esc_html( $title ); ?></h2>
                <?php endif; ?>
                <?php if ( $url ) : ?>
                    <div class="video-wrapper" data-aos="fade" data-aos-delay="100">
                        <div class="video-container">
                            <iframe src="<?php echo $url; ?>" title="Video" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                        <?php if ( $caption ) : ?>
                            <div class="video-caption">
                                <p><?php echo esc_html( $caption ); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

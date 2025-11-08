<?php
$background = ay_aip_base_get_block_field( 'text_content_background', 'white' );
$title      = ay_aip_base_get_block_field( 'text_content_title' );
$body       = ay_aip_base_get_block_field( 'text_content_body' );

$section_class = 'block-text';
if ( 'light' === $background ) {
    $section_class .= ' bg-light';
}
?>
<section class="<?php echo esc_attr( $section_class ); ?>">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">
                <div class="block-text-content" data-aos="fade">
                    <?php if ( $title ) : ?>
                        <h2 class="block-text-title"><?php echo esc_html( $title ); ?></h2>
                    <?php endif; ?>
                    <?php if ( $body ) : ?>
                        <div class="block-text-description"><?php echo wp_kses_post( $body ); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

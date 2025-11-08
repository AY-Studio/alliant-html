<?php
$background   = ay_aip_base_get_block_field( 'media_content_background', 'white' );
$alignment    = ay_aip_base_get_block_field( 'media_content_alignment', 'left' );
$title        = ay_aip_base_get_block_field( 'media_content_title' );
$body         = ay_aip_base_get_block_field( 'media_content_body' );
$button_label = ay_aip_base_get_block_field( 'media_content_button_label' );
$button_url   = ay_aip_base_get_block_field( 'media_content_button_url' );
$image        = ay_aip_base_get_block_field( 'media_content_image' );
$image_alt    = ay_aip_base_get_block_field( 'media_content_image_alt' );

if ( ! $image ) {
    $image = [
        'url' => ay_aip_base_get_theme_asset_url( 'img/passenger-air-vehicle-parked-on-the-airport-apron-2024-10-18-09-02-37-utc-scaled.jpg' ),
        'alt' => $image_alt ?: $title,
    ];
}

$section_class = 'block-image-content ' . ( 'right' === $alignment ? 'image-right' : 'image-left' );
if ( 'light' === $background ) {
    $section_class .= ' bg-light';
}

$content_col_classes = 'col-12 col-lg-6';
$image_col_classes   = 'col-12 col-lg-6';
if ( 'right' === $alignment ) {
    $content_col_classes .= ' order-2 order-lg-1';
    $image_col_classes   .= ' order-1 order-lg-2';
}
?>
<section class="<?php echo esc_attr( $section_class ); ?>">
    <div class="container">
        <div class="row align-items-center g-4 g-lg-5">
            <div class="<?php echo esc_attr( $image_col_classes ); ?>">
                <div class="block-image" data-aos="fade">
                    <img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image_alt ?: ( $image['alt'] ?? $title ) ); ?>" class="img-fluid rounded">
                </div>
            </div>
            <div class="<?php echo esc_attr( $content_col_classes ); ?>">
                <div class="block-content" data-aos="fade" data-aos-delay="100">
                    <?php if ( $title ) : ?>
                        <h2 class="block-title"><?php echo esc_html( $title ); ?></h2>
                    <?php endif; ?>
                    <?php if ( $body ) : ?>
                        <?php echo wp_kses_post( $body ); ?>
                    <?php endif; ?>
                    <?php if ( $button_label && $button_url ) : ?>
                        <a href="<?php echo esc_url( $button_url ); ?>" class="btn btn-primary mt-3"><?php echo esc_html( $button_label ); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

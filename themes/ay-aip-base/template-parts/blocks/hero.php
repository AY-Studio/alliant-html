<?php
$eyebrow   = ay_aip_base_get_block_field( 'hero_eyebrow' );
$title     = ay_aip_base_get_block_field( 'hero_heading' );
$subhead   = ay_aip_base_get_block_field( 'hero_subheading' );
$buttons   = ay_aip_base_get_block_field( 'hero_buttons' );
$bg_image  = ay_aip_base_get_block_field( 'hero_background_image' );
$overlay   = ay_aip_base_get_block_field( 'hero_overlay_color' );
$variant   = ay_aip_base_get_block_field( 'hero_variant', 'large' );
$layout    = ay_aip_base_get_block_field( 'hero_layout_style', 'default' );

if ( ! $bg_image ) {
    $bg_image = [
        'url' => get_template_directory_uri() . '/img/passenger-air-vehicle-parked-on-the-airport-apron-2024-10-18-09-02-37-utc-scaled.jpg',
        'alt' => $title ?: __( 'Hero background', 'ay-aip-base' ),
    ];
}

$bg_markup = '';
if ( $bg_image ) {
    $bg_markup = sprintf( '<img src="%s" alt="%s">', esc_url( $bg_image['url'] ), esc_attr( $bg_image['alt'] ?: $title ) );
}
$section_classes = 'block-hero';
if ( 'small' === $variant ) {
    $section_classes = 'block-hero-short';
} elseif ( in_array( $layout, [ 'compact', 'block-hero-compact' ], true ) ) {
    $section_classes .= ' block-hero-compact';
}
$show_buttons = ( 'small' !== $variant ) && $buttons;
?>
<section class="<?php echo esc_attr( $section_classes ); ?>">
    <div class="hero-background">
        <?php echo $bg_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </div>
    <div class="hero-overlay" style="<?php echo $overlay ? 'background:' . esc_attr( $overlay ) . ';' : ''; ?>"></div>
    <div class="hero-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10 col-xl-8 text-center">
                    <?php if ( $title ) : ?>
                        <h1 class="hero-title" data-aos="fade"><?php echo esc_html( $title ); ?></h1>
                    <?php endif; ?>
                    <?php if ( $subhead ) : ?>
                        <p class="hero-description" data-aos="fade" data-aos-delay="100"><?php echo wp_kses_post( $subhead ); ?></p>
                    <?php endif; ?>
                    <?php if ( $show_buttons ) : ?>
                        <div class="hero-actions" data-aos="fade" data-aos-delay="200">
                            <?php foreach ( $buttons as $button ) :
                                $label = $button['label'] ?? __( 'Learn More', 'ay-aip-base' );
                                $url   = $button['url'] ?? '#';
                                $btn_class = 'btn btn-lg ' . ( ( $button['style'] ?? 'primary' ) === 'secondary' ? 'btn-outline-light' : 'btn-light' );
                                ?>
                                <a href="<?php echo esc_url( $url ); ?>" class="<?php echo esc_attr( $btn_class ); ?>"><?php echo esc_html( $label ); ?></a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

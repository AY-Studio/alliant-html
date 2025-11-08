<?php
$background = ay_aip_base_get_block_field( 'icon_features_background', 'white' );
$title      = ay_aip_base_get_block_field( 'icon_features_title' );
$subtitle   = ay_aip_base_get_block_field( 'icon_features_subtitle' );
$items      = ay_aip_base_get_block_field( 'icon_features_items', [] );

$section_class = 'block-icon-features';
if ( 'light' === $background ) {
    $section_class .= ' bg-light';
}
?>
<section class="<?php echo esc_attr( $section_class ); ?>">
    <div class="container">
        <?php if ( $title || $subtitle ) : ?>
            <div class="text-center mb-5" data-aos="fade">
                <?php if ( $title ) : ?>
                    <h2 class="block-title"><?php echo esc_html( $title ); ?></h2>
                <?php endif; ?>
                <?php if ( $subtitle ) : ?>
                    <p class="block-subtitle"><?php echo esc_html( $subtitle ); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ( $items ) : ?>
            <div class="icon-features-grid">
                <?php
                $delay = 100;
                foreach ( $items as $item ) :
                    $icon_markup = ay_aip_base_get_icon_markup( $item );
                    ?>
                    <div class="icon-feature-item" data-aos="fade" data-aos-delay="<?php echo esc_attr( $delay ); ?>">
                        <?php if ( $icon_markup ) : ?>
                            <div class="feature-icon"><?php echo $icon_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
                        <?php endif; ?>
                        <?php if ( ! empty( $item['title'] ) ) : ?>
                            <h3 class="feature-title"><?php echo esc_html( $item['title'] ); ?></h3>
                        <?php endif; ?>
                        <?php if ( ! empty( $item['description'] ) ) : ?>
                            <p class="feature-description"><?php echo esc_html( $item['description'] ); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php
                    $delay += 100;
                    if ( $delay > 400 ) {
                        $delay = 100;
                    }
                endforeach;
                ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php
$title    = ay_aip_base_get_block_field( 'values_title' );
$subtitle = ay_aip_base_get_block_field( 'values_subtitle' );
$items    = ay_aip_base_get_block_field( 'values_items', [] );
?>
<section class="block-values">
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
            <div class="values-grid">
                <?php
                $delay = 100;
                foreach ( $items as $item ) :
                    $icon_markup = ay_aip_base_get_icon_markup(
                        $item,
                        [
                            'svg'   => 'value_icon_svg',
                            'image' => 'value_icon_image',
                            'class' => 'value_icon_class',
                        ]
                    );
                    ?>
                    <div class="value-card" data-aos="fade" data-aos-delay="<?php echo esc_attr( $delay ); ?>">
                        <?php if ( ! empty( $item['number'] ) ) : ?>
                            <div class="value-number"><?php echo esc_html( $item['number'] ); ?></div>
                        <?php endif; ?>
                        <?php if ( $icon_markup ) : ?>
                            <div class="value-icon"><?php echo $icon_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
                        <?php endif; ?>
                        <?php if ( ! empty( $item['title'] ) ) : ?>
                            <h3 class="value-title"><?php echo esc_html( $item['title'] ); ?></h3>
                        <?php endif; ?>
                        <?php if ( ! empty( $item['description'] ) ) : ?>
                            <p class="value-description"><?php echo esc_html( $item['description'] ); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php
                    $delay += 100;
                    if ( $delay > 300 ) {
                        $delay = 100;
                    }
                endforeach;
                ?>
            </div>
        <?php endif; ?>
    </div>
</section>

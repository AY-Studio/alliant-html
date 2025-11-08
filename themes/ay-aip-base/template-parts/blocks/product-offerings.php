<?php
$theme    = ay_aip_base_get_block_field( 'product_offerings_theme', 'grey' );
$title    = ay_aip_base_get_block_field( 'product_offerings_title' );
$subtitle = ay_aip_base_get_block_field( 'product_offerings_subtitle' );
$items    = ay_aip_base_get_block_field( 'product_offerings_items', [] );

$section_class = 'section group-grey';
if ( 'navy' === $theme ) {
    $section_class = 'section group-navy';
}
?>
<section class="<?php echo esc_attr( $section_class ); ?>">
    <div class="diagonal-lines"></div>
    <div class="container">
        <?php if ( $title || $subtitle ) : ?>
            <div class="row">
                <div class="col-12" data-aos="fade">
                    <?php if ( $title ) : ?>
                        <h2 class="section-title"><?php echo esc_html( $title ); ?></h2>
                    <?php endif; ?>
                    <?php if ( $subtitle ) : ?>
                        <p class="section-subtitle"><?php echo wp_kses_post( $subtitle ); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ( $items ) : ?>
            <div class="row g-4">
                <?php
                $delay_cycle = [ 100, 200, 300 ];
                $index       = 0;
                foreach ( $items as $item ) :
                    $delay = $delay_cycle[ $index % count( $delay_cycle ) ];
                    $icon_markup = '';
                    if ( ! empty( $item['icon_image']['url'] ) ) {
                        $icon_markup = sprintf(
                            '<img src="%s" alt="%s">',
                            esc_url( $item['icon_image']['url'] ),
                            esc_attr( $item['icon_image']['alt'] ?? ( $item['title'] ?? '' ) )
                        );
                    } elseif ( ! empty( $item['icon_image_url'] ) ) {
                        $icon_markup = sprintf(
                            '<img src="%s" alt="%s">',
                            esc_url( $item['icon_image_url'] ),
                            esc_attr( $item['title'] ?? '' )
                        );
                    } else {
                        $icon_markup = ay_aip_base_get_icon_markup(
                            $item,
                            [
                                'svg'   => 'icon_svg',
                                'image' => 'icon_image',
                                'class' => 'icon_class',
                            ]
                        );
                    }
                    ?>
                    <div class="col-12 col-sm-6 col-lg-4" data-aos="fade" data-aos-delay="<?php echo esc_attr( $delay ); ?>">
                        <div class="card border-0 text-center h-100">
                            <div class="card-body">
                                <?php if ( $icon_markup ) : ?>
                                    <div class="icon-wrapper mx-auto mb-3"><?php echo $icon_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
                                <?php endif; ?>
                                <?php if ( ! empty( $item['title'] ) ) : ?>
                                    <h4 class="card-title text-uppercase"><?php echo esc_html( $item['title'] ); ?></h4>
                                <?php endif; ?>
                                <?php if ( ! empty( $item['description'] ) ) : ?>
                                    <p class="card-description"><?php echo esc_html( $item['description'] ); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php
                    $index++;
                endforeach;
                ?>
            </div>
        <?php endif; ?>
    </div>
</section>

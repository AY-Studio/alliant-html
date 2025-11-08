<?php
$background = ay_aip_base_get_block_field( 'value_cards_background', 'white' );
$title      = ay_aip_base_get_block_field( 'value_cards_title' );
$subtitle   = ay_aip_base_get_block_field( 'value_cards_subtitle' );
$cards      = ay_aip_base_get_block_field( 'value_cards_cards', [] );
$cta_label  = ay_aip_base_get_block_field( 'value_cards_button_label' );
$cta_url    = ay_aip_base_get_block_field( 'value_cards_button_url' );

$section_class = 'section section-white';
if ( 'navy' === $background ) {
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

        <?php if ( $cards ) : ?>
            <div class="row g-4 mb-4">
                <?php
                $delay = 100;
                foreach ( $cards as $card ) :
                    $image = $card['image'] ?? null;
                    if ( ! $image && ! empty( $card['image_url'] ) ) {
                        $image = [
                            'url' => $card['image_url'],
                            'alt' => $card['title'] ?? '',
                        ];
                    }
                    if ( ! $image ) {
                        $image = [
                            'url' => ay_aip_base_get_theme_asset_url( 'img/passenger-air-vehicle-parked-on-the-airport-apron-2024-10-18-09-02-37-utc-scaled.jpg' ),
                            'alt' => $card['title'] ?? '',
                        ];
                    }
                    ?>
                    <div class="col-12 col-lg-4" data-aos="fade" data-aos-delay="<?php echo esc_attr( $delay ); ?>">
                        <div class="card border-0 value-card h-100">
                            <?php if ( $image ) : ?>
                                <img src="<?php echo esc_url( $image['url'] ); ?>" class="card-img-top" alt="<?php echo esc_attr( $image['alt'] ?? ( $card['title'] ?? '' ) ); ?>">
                            <?php endif; ?>
                            <div class="card-body card-overlay text-center">
                                <?php if ( ! empty( $card['title'] ) ) : ?>
                                    <h3 class="card-title"><?php echo esc_html( $card['title'] ); ?></h3>
                                <?php endif; ?>
                                <?php if ( ! empty( $card['description'] ) ) : ?>
                                    <p class="card-text"><?php echo esc_html( $card['description'] ); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
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

        <?php if ( $cta_label && $cta_url ) : ?>
            <div class="row pt-4">
                <div class="col-12 text-center" data-aos="fade" data-aos-delay="400">
                    <a href="<?php echo esc_url( $cta_url ); ?>" class="btn btn-outline-primary"><?php echo esc_html( $cta_label ); ?> →</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

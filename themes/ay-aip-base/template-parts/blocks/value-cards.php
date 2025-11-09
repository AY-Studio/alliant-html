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
                    $image_field = $card['image'] ?? null;
                    ?>
                    <div class="col-12 col-lg-4" data-aos="fade" data-aos-delay="<?php echo esc_attr( $delay ); ?>">
                        <div class="card border-0 value-card h-100">
                            <?php
                            $image_html = '';
                            if ( $image_field ) {
                                $image_id = is_array( $image_field ) && isset( $image_field['ID'] ) ? $image_field['ID'] : $image_field;
                                $image_id = absint( $image_id );
                                if ( $image_id ) {
                                    $image_html = wp_get_attachment_image(
                                        $image_id,
                                        'ay_aip_base_card',
                                        false,
                                        [
                                            'class' => 'card-img-top',
                                            'alt'   => esc_attr( $card['title'] ?? get_post_meta( $image_id, '_wp_attachment_image_alt', true ) ),
                                        ]
                                    );
                                }
                            }
                            if ( ! $image_html ) {
                                $placeholder = ay_aip_base_get_theme_asset_url( 'img/value-card-1.jpg' );
                                $alt         = isset( $card['title'] ) ? esc_attr( $card['title'] ) : esc_attr__( 'Value card image', 'ay-aip-base' );
                                $image_html  = '<img src="' . esc_url( $placeholder ) . '" class="card-img-top" alt="' . $alt . '">';
                            }
                            echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            ?>
                            <div class="card-body card-overlay text-center">
                                <?php if ( ! empty( $card['title'] ) ) : ?>
                                    <h3 class="card-title pb-3"><?php echo esc_html( $card['title'] ); ?></h3>
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

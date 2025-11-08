<?php
$background = ay_aip_base_get_block_field( 'pricing_background', 'white' );
$title      = ay_aip_base_get_block_field( 'pricing_title' );
$subtitle   = ay_aip_base_get_block_field( 'pricing_subtitle' );
$cards      = ay_aip_base_get_block_field( 'pricing_cards', [] );

$section_class = 'block-pricing';
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

        <?php if ( $cards ) : ?>
            <div class="pricing-grid">
                <?php
                $delay = 100;
                foreach ( $cards as $card ) :
                    $card_classes = 'pricing-card';
                    if ( ! empty( $card['featured'] ) ) {
                        $card_classes .= ' featured';
                    }
                    ?>
                    <div class="<?php echo esc_attr( $card_classes ); ?>" data-aos="fade" data-aos-delay="<?php echo esc_attr( $delay ); ?>">
                        <?php if ( ! empty( $card['featured'] ) && ! empty( $card['badge_text'] ) ) : ?>
                            <div class="pricing-badge"><?php echo esc_html( $card['badge_text'] ); ?></div>
                        <?php endif; ?>
                        <div class="pricing-header">
                            <?php if ( ! empty( $card['title'] ) ) : ?>
                                <h3 class="pricing-title"><?php echo esc_html( $card['title'] ); ?></h3>
                            <?php endif; ?>
                            <?php if ( ! empty( $card['subtitle'] ) ) : ?>
                                <div class="pricing-subtitle"><?php echo esc_html( $card['subtitle'] ); ?></div>
                            <?php endif; ?>
                        </div>
                        <?php if ( ! empty( $card['features'] ) ) : ?>
                            <div class="pricing-features">
                                <ul>
                                    <?php foreach ( $card['features'] as $feature ) : ?>
                                        <?php if ( ! empty( $feature['text'] ) ) : ?>
                                            <li><?php echo esc_html( $feature['text'] ); ?></li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <?php if ( ! empty( $card['button_label'] ) && ! empty( $card['button_url'] ) ) : ?>
                            <div class="pricing-cta">
                                <?php
                                $button_style = 'btn-outline-primary';
                                if ( 'primary' === ( $card['button_style'] ?? 'outline' ) ) {
                                    $button_style = 'btn-primary';
                                }
                                ?>
                                <a href="<?php echo esc_url( $card['button_url'] ); ?>" class="btn <?php echo esc_attr( $button_style ); ?>"><?php echo esc_html( $card['button_label'] ); ?></a>
                            </div>
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

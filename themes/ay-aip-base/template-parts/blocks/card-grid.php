<?php
$cards           = ay_aip_base_get_block_field( 'card_items', [] );
$background_mod  = ay_aip_base_get_block_field( 'card_background' ) === 'light' ? ' bg-light' : '';
$section_title   = ay_aip_base_get_block_field( 'card_section_title' );
$section_sub     = ay_aip_base_get_block_field( 'card_section_subtitle' );
?>
<section class="block-card-group<?php echo esc_attr( $background_mod ); ?>">
    <div class="container">
        <?php if ( $section_title || $section_sub ) : ?>
            <div class="text-center mb-5" data-aos="fade">
                <?php if ( $section_title ) : ?>
                    <h2 class="block-title"><?php echo esc_html( $section_title ); ?></h2>
                <?php endif; ?>
                <?php if ( $section_sub ) : ?>
                    <p class="block-subtitle"><?php echo esc_html( $section_sub ); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <div class="card-group-grid">
            <?php if ( $cards ) :
                $delay = 100;
                foreach ( $cards as $card ) : ?>
                    <div class="card-group-item" data-aos="fade" data-aos-delay="<?php echo esc_attr( $delay ); ?>">
                        <?php if ( ! empty( $card['icon'] ) ) : ?>
                            <div class="card-group-icon">
                                <i class="<?php echo esc_attr( $card['icon'] ); ?>" aria-hidden="true"></i>
                            </div>
                        <?php elseif ( ! empty( $card['image'] ) ) : ?>
                            <div class="card-group-icon">
                                <img src="<?php echo esc_url( $card['image']['url'] ); ?>" alt="<?php echo esc_attr( $card['image']['alt'] ); ?>">
                            </div>
                        <?php endif; ?>
                        <?php if ( ! empty( $card['heading'] ) ) : ?>
                            <h3 class="card-group-title"><?php echo esc_html( $card['heading'] ); ?></h3>
                        <?php endif; ?>
                        <?php if ( ! empty( $card['body'] ) ) : ?>
                            <p class="card-group-description"><?php echo esc_html( $card['body'] ); ?></p>
                        <?php endif; ?>
                        <?php if ( ! empty( $card['cta_label'] ) && ! empty( $card['cta_url'] ) ) : ?>
                            <a href="<?php echo esc_url( $card['cta_url'] ); ?>" class="card-group-link"><?php echo esc_html( $card['cta_label'] ); ?> →</a>
                        <?php endif; ?>
                    </div>
                <?php
                    $delay += 100;
                endforeach;
            endif; ?>
        </div>
    </div>
</section>

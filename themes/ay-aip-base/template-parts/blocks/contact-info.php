<?php
$title    = ay_aip_base_get_block_field( 'contact_info_title' );
$subtitle = ay_aip_base_get_block_field( 'contact_info_subtitle' );
cards     = ay_aip_base_get_block_field( 'contact_info_cards', [] );
?>
<section class="section contact-info">
    <div class="container">
        <?php if ( $title || $subtitle ) : ?>
            <div class="section-header text-center mb-5" data-aos="fade">
                <?php if ( $title ) : ?>
                    <h1 class="section-title"><?php echo esc_html( $title ); ?></h1>
                <?php endif; ?>
                <?php if ( $subtitle ) : ?>
                    <p class="lead section-subtitle"><?php echo wp_kses_post( $subtitle ); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ( $cards ) : ?>
            <div class="row g-4">
                <?php
                $delay = 100;
                foreach ( $cards as $card ) :
                    ?>
                    <div class="col-12 col-md-4" data-aos="fade" data-aos-delay="<?php echo esc_attr( $delay ); ?>">
                        <div class="card h-100 border-0 shadow-sm text-center contact-card">
                            <div class="card-body">
                                <?php if ( ! empty( $card['icon_svg'] ) ) : ?>
                                    <div class="contact-icon mb-3"><?php echo wp_kses_post( $card['icon_svg'] ); ?></div>
                                <?php endif; ?>
                                <?php if ( ! empty( $card['title'] ) ) : ?>
                                    <h4 class="card-title mb-2"><?php echo esc_html( $card['title'] ); ?></h4>
                                <?php endif; ?>
                                <?php if ( ! empty( $card['body'] ) ) : ?>
                                    <p class="card-text mb-0"><?php echo wp_kses_post( $card['body'] ); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php
                    $delay += 100;
                endforeach;
                ?>
            </div>
        <?php endif; ?>
    </div>
</section>

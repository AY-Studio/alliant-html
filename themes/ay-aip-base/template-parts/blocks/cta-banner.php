<?php
$icon   = get_field( 'cta_icon' );
$heading  = get_field( 'cta_heading' );
$body     = get_field( 'cta_body' );
$buttons  = get_field( 'cta_buttons' );
?>
<section class="block-cta-callout">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">
                <div class="cta-callout-inner" data-aos="fade">
                    <?php if ( $icon ) : ?>
                        <div class="cta-icon"><?php echo wp_kses_post( $icon ); ?></div>
                    <?php endif; ?>
                    <?php if ( $heading ) : ?>
                        <h2 class="cta-title"><?php echo esc_html( $heading ); ?></h2>
                    <?php endif; ?>
                    <?php if ( $body ) : ?>
                        <p class="cta-description"><?php echo wp_kses_post( $body ); ?></p>
                    <?php endif; ?>
                    <?php if ( $buttons ) : ?>
                        <div class="cta-actions">
                            <?php foreach ( $buttons as $button ) :
                                $style = ( $button['style'] ?? 'primary' ) === 'secondary' ? 'btn btn-outline-light btn-lg' : 'btn btn-light btn-lg';
                                ?>
                                <a class="<?php echo esc_attr( $style ); ?>" href="<?php echo esc_url( $button['url'] ?? '#' ); ?>"><?php echo esc_html( $button['label'] ?? __( 'Learn More', 'ay-aip-base' ) ); ?></a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

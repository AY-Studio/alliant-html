<?php
$heading = ay_aip_base_get_block_field( 'hero_section_heading' );
$lead    = ay_aip_base_get_block_field( 'hero_section_lead' );
$cta_link = ay_aip_base_get_block_field( 'cta_link' );
?>
<section class="hero-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php if ( $heading ) : ?>
                    <h1><?php echo esc_html( $heading ); ?></h1>
                <?php endif; ?>
                <?php if ( $lead ) : ?>
                    <p class="lead"><?php echo esc_html( $lead ); ?></p>
                <?php endif; ?>
                <?php
                if ( is_array( $cta_link ) && ! empty( $cta_link['url'] ) ) :
                    $cta_label  = ! empty( $cta_link['title'] ) ? $cta_link['title'] : __( 'Learn More', 'ay-aip-base' );
                    $cta_target = ! empty( $cta_link['target'] ) ? $cta_link['target'] : '_self';
                    $rel_attr   = '_blank' === $cta_target ? 'noopener noreferrer' : '';
                    ?>
                    <a href="<?php echo esc_url( $cta_link['url'] ); ?>"
                        class="btn btn-outline-light"
                        target="<?php echo esc_attr( $cta_target ); ?>"
                        <?php echo $rel_attr ? 'rel="' . esc_attr( $rel_attr ) . '"' : ''; ?>>
                        <?php echo esc_html( $cta_label ); ?> →
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php
$heading = ay_aip_base_get_block_field( 'hero_section_heading' );
$lead    = ay_aip_base_get_block_field( 'hero_section_lead' );
$button_label = ay_aip_base_get_block_field( 'hero_section_button_label' );
$button_url   = ay_aip_base_get_block_field( 'hero_section_button_url' );
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
                <?php if ( $button_label && $button_url ) : ?>
                    <a href="<?php echo esc_url( $button_url ); ?>" class="btn btn-outline-light"><?php echo esc_html( $button_label ); ?> →</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

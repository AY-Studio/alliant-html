<?php
$title = ay_aip_base_get_block_field( 'contact_form_title' );
$body  = ay_aip_base_get_block_field( 'contact_form_body' );
$form_id = ay_aip_base_get_block_field( 'contact_form_id' );
?>
<section class="section section-light py-6">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="contact-intro text-center mb-5" data-aos="fade">
                    <?php if ( $title ) : ?>
                        <h2 class="section-title"><?php echo esc_html( $title ); ?></h2>
                    <?php endif; ?>
                    <?php if ( $body ) : ?>
                        <p><?php echo wp_kses_post( $body ); ?></p>
                    <?php endif; ?>
                </div>
                <div class="contact-form-wrapper" data-aos="fade" data-aos-delay="100">
                    <?php
                    if ( $form_id && function_exists( 'gravity_form' ) ) {
                        gravity_form( $form_id, false, false, false, null, true );
                    } else {
                        echo '<p class="text-center">' . esc_html__( 'Gravity Form code required to display this form.', 'ay-aip-base' ) . '</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>

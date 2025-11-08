<?php
$background = ay_aip_base_get_block_field( 'locations_background', 'white' );
$title      = ay_aip_base_get_block_field( 'locations_title' );
$subtitle   = ay_aip_base_get_block_field( 'locations_subtitle' );
$locations  = ay_aip_base_get_block_field( 'locations_items', [] );

$section_class = 'block-locations';
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

        <?php if ( $locations ) : ?>
            <div class="locations-grid">
                <?php
                $delay = 100;
                foreach ( $locations as $location ) :
                    $icon_markup = ay_aip_base_get_icon_markup(
                        $location,
                        [
                            'svg'   => 'location_icon_svg',
                            'image' => 'location_icon_image',
                            'class' => 'location_icon_class',
                        ]
                    );
                    ?>
                    <div class="location-card" data-aos="fade" data-aos-delay="<?php echo esc_attr( $delay ); ?>">
                        <?php if ( $icon_markup ) : ?>
                            <div class="location-icon"><?php echo $icon_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
                        <?php endif; ?>
                        <?php if ( ! empty( $location['name'] ) ) : ?>
                            <h3 class="location-name"><?php echo esc_html( $location['name'] ); ?></h3>
                        <?php endif; ?>
                        <?php if ( ! empty( $location['designation'] ) ) : ?>
                            <div class="location-designation"><?php echo esc_html( $location['designation'] ); ?></div>
                        <?php endif; ?>
                        <div class="location-details">
                            <?php if ( ! empty( $location['address'] ) ) : ?>
                                <p class="location-address"><?php echo nl2br( esc_html( $location['address'] ) ); ?></p>
                            <?php endif; ?>
                            <p class="location-contact">
                                <?php if ( ! empty( $location['phone'] ) ) : ?>
                                    <strong><?php esc_html_e( 'Phone:', 'ay-aip-base' ); ?></strong> <?php echo esc_html( $location['phone'] ); ?><br>
                                <?php endif; ?>
                                <?php if ( ! empty( $location['email'] ) ) :
                                    $email = antispambot( $location['email'] );
                                    ?>
                                    <strong><?php esc_html_e( 'Email:', 'ay-aip-base' ); ?></strong> <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
                                <?php endif; ?>
                            </p>
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
    </div>
</section>

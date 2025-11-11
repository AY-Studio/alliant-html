<?php
$title            = ay_aip_base_get_block_field( 'about_team_title' );
$subtitle         = ay_aip_base_get_block_field( 'about_team_subtitle' );
$members          = ay_aip_base_get_block_field( 'about_team_members', [] );
$background       = ay_aip_base_get_block_field( 'about_team_background', 'light' );
$show_pattern     = ay_aip_base_get_block_field( 'about_team_show_pattern', false );
$pattern_position = ay_aip_base_get_block_field( 'about_team_pattern_position', 'top-right' );

// Determine section class based on background
$section_class = 'section section-light';
if ( 'white' === $background ) {
    $section_class = 'section section-white';
} elseif ( 'navy' === $background ) {
    $section_class = 'section group-navy';
}

// Build pattern class if pattern is enabled
$pattern_class = '';
if ( $show_pattern ) {
    $pattern_class = 'diagonal-lines pattern-' . esc_attr( $pattern_position );
}
?>
<section class="<?php echo esc_attr( $section_class ); ?> py-6 position-relative">
    <?php if ( $show_pattern ) : ?>
        <div class="<?php echo esc_attr( $pattern_class ); ?>"></div>
    <?php endif; ?>
    <div class="container" style="position: relative; z-index: 2;">
        <?php if ( $title || $subtitle ) : ?>
            <div class="row justify-content-center">
                <div class="col-12 col-lg-8 text-center mb-4" data-aos="fade">
                    <?php if ( $title ) : ?>
                        <h2 class="section-title"><?php echo esc_html( $title ); ?></h2>
                    <?php endif; ?>
                    <?php if ( $subtitle ) : ?>
                        <p><?php echo wp_kses_post( $subtitle ); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ( $members ) : ?>
            <div class="row justify-content-center g-4">
                <?php
                $delays = [ 100, 200, 300 ];
                $index  = 0;
                foreach ( $members as $member ) :
                    $photo_field = $member['photo'] ?? null;
                    $photo_html  = '';
                    if ( $photo_field ) {
                        $photo_id = null;
                        if ( is_array( $photo_field ) && isset( $photo_field['ID'] ) ) {
                            $photo_id = (int) $photo_field['ID'];
                        } elseif ( is_numeric( $photo_field ) ) {
                            $photo_id = (int) $photo_field;
                        }
                        if ( $photo_id ) {
                            $photo_html = wp_get_attachment_image(
                                $photo_id,
                                'ay_aip_base_card',
                                false,
                                [
                                    'class' => 'card-img-top',
                                    'alt'   => esc_attr( $member['name'] ?? get_post_meta( $photo_id, '_wp_attachment_image_alt', true ) ),
                                ]
                            );
                        } elseif ( is_array( $photo_field ) && ! empty( $photo_field['url'] ) ) {
                            $photo_html = '<img src="' . esc_url( $photo_field['url'] ) . '" class="card-img-top" alt="' . esc_attr( $photo_field['alt'] ?? ( $member['name'] ?? __( 'Team member', 'ay-aip-base' ) ) ) . '">';
                        }
                    }
                    if ( ! $photo_html && ! empty( $member['photo_url'] ) ) {
                        $photo_html = '<img src="' . esc_url( $member['photo_url'] ) . '" class="card-img-top" alt="' . esc_attr( $member['name'] ?? __( 'Team member', 'ay-aip-base' ) ) . '">';
                    }
                    if ( ! $photo_html ) {
                        $fallback   = ay_aip_base_get_theme_asset_url( 'img/person.jpg' );
                        $photo_html = '<img src="' . esc_url( $fallback ) . '" class="card-img-top" alt="' . esc_attr( $member['name'] ?? __( 'Team member', 'ay-aip-base' ) ) . '">';
                    }
                    $delay = $delays[ $index % count( $delays ) ];
                    ?>
                    <div class="col-12 col-sm-6 col-lg-4" data-aos="fade" data-aos-delay="<?php echo esc_attr( $delay ); ?>">
                        <div class="card border-0 team-card h-100 text-center">
                            <?php echo $photo_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            <div class="card-body">
                                <?php if ( ! empty( $member['name'] ) ) : ?>
                                    <h5 class="card-title mb-1"><?php echo esc_html( $member['name'] ); ?></h5>
                                <?php endif; ?>
                                <?php if ( ! empty( $member['title'] ) ) : ?>
                                    <p class="card-text text-muted"><?php echo esc_html( $member['title'] ); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php
                    $index++;
                endforeach;
                ?>
            </div>
        <?php endif; ?>
    </div>
</section>

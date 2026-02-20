<?php
$heading         = ay_aip_base_get_block_field( 'hero_section_heading' );
$lead            = ay_aip_base_get_block_field( 'hero_section_lead' );
$cta_link        = ay_aip_base_get_block_field( 'cta_link' );
$bg_image        = ay_aip_base_get_block_field( 'hero_section_background_image' );
$overlay_color   = ay_aip_base_get_block_field( 'hero_section_overlay_color' );
$overlay_opacity = ay_aip_base_get_block_field( 'hero_section_overlay_opacity' );
$text_color      = ay_aip_base_get_block_field( 'hero_section_text_color' );

if ( ! $text_color ) {
    $text_color = '#ffffff';
}
$text_attr   = $text_color ? ' style="color:' . esc_attr( $text_color ) . ';"' : '';

// Default background image if none is provided
if ( ! $bg_image ) {
    $bg_image = [
        'url' => get_template_directory_uri() . '/img/alliant-mission-hero.jpg',
        'alt' => $heading ?: __( 'Hero background', 'ay-aip-base' ),
    ];
}

// Default overlay color if none is provided
if ( ! $overlay_color ) {
    $overlay_color = 'rgb(34, 58, 105)';
}

// Default opacity if none is provided
if ( ! $overlay_opacity ) {
    $overlay_opacity = 30;
}

// Convert hex color to rgba with opacity
$overlay = ay_aip_base_hex_to_rgba( $overlay_color, $overlay_opacity / 100 );

$cta_styles  = ay_aip_base_get_button_style_tokens(
    [
        'button_style_type'                 => ay_aip_base_get_block_field( 'hero_section_button_style_type' ),
        'button_background_color'           => ay_aip_base_get_block_field( 'hero_section_button_background_color' ),
        'button_border_color'               => ay_aip_base_get_block_field( 'hero_section_button_border_color' ),
        'button_text_color'                 => ay_aip_base_get_block_field( 'hero_section_button_text_color' ),
        'button_hover_background_color'    => ay_aip_base_get_block_field( 'hero_section_button_hover_background_color' ),
        'button_hover_border_color'        => ay_aip_base_get_block_field( 'hero_section_button_hover_border_color' ),
        'button_hover_text_color'          => ay_aip_base_get_block_field( 'hero_section_button_hover_text_color' ),
    ],
    [
        'type'   => 'outline',
        'border' => '#ffffff',
        'text'   => '#ffffff',
    ]
);
?>
<section class="hero-section" style="background-image: none;">
    <?php if ( $bg_image && ! empty( $bg_image['url'] ) ) : ?>
        <div class="hero-background">
            <img src="<?php echo esc_url( $bg_image['url'] ); ?>" alt="<?php echo esc_attr( $bg_image['alt'] ?: $heading ); ?>">
        </div>
    <?php endif; ?>
    <div class="hero-overlay" style="background: <?php echo esc_attr( $overlay ); ?>;"></div>
    <div class="container" style="position: relative; z-index: 2;">
        <div class="row">
            <div class="col-12">
                <?php if ( $heading ) : ?>
                    <h1<?php echo $text_attr; ?>><?php echo esc_html( $heading ); ?></h1>
                <?php endif; ?>
                <?php if ( $lead ) : ?>
                    <p class="lead"<?php echo $text_attr; ?>><?php echo esc_html( $lead ); ?></p>
                <?php endif; ?>
                <?php
                if ( is_array( $cta_link ) && ! empty( $cta_link['url'] ) ) :
                    $cta_label  = ! empty( $cta_link['title'] ) ? $cta_link['title'] : __( 'Learn More', 'ay-aip-base' );
                    $cta_target = ! empty( $cta_link['target'] ) ? $cta_link['target'] : '_self';
                    $rel_attr   = '_blank' === $cta_target ? 'noopener noreferrer' : '';
                    ?>
                    <a href="<?php echo esc_url( $cta_link['url'] ); ?>"
                        class="btn <?php echo 'solid' === $cta_styles['type'] ? 'btn-light' : 'btn-outline-light'; ?>"
                        target="<?php echo esc_attr( $cta_target ); ?>"
                        <?php echo $rel_attr ? 'rel="' . esc_attr( $rel_attr ) . '"' : ''; ?>
                        <?php echo $cta_styles['style']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
                        <?php echo esc_html( $cta_label ); ?> →
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

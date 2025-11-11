<?php
$eyebrow         = ay_aip_base_get_block_field( 'hero_eyebrow' );
$title           = ay_aip_base_get_block_field( 'hero_heading' );
$subhead         = ay_aip_base_get_block_field( 'hero_subheading' );
$buttons         = ay_aip_base_get_block_field( 'hero_buttons' );
$bg_image        = ay_aip_base_get_block_field( 'hero_background_image' );
$overlay_color   = ay_aip_base_get_block_field( 'hero_overlay_color' );
$overlay_opacity = ay_aip_base_get_block_field( 'hero_overlay_opacity' );
$variant         = ay_aip_base_get_block_field( 'hero_variant', 'large' );
$layout          = ay_aip_base_get_block_field( 'hero_layout_style', 'default' );
$text_mode       = ay_aip_base_get_block_field( 'hero_text_mode', 'light' );
$text_color      = ay_aip_base_get_block_field( 'hero_text_color' );

if ( ! $text_color ) {
    $text_color = ( 'light' === $text_mode ) ? '#ffffff' : ay_aip_base_get_heading_color();
}
$text_color_attr = $text_color ? ' style="color:' . esc_attr( $text_color ) . ';"' : '';

// Build overlay style
$overlay_style = '';
if ( $overlay_color ) {
    if ( ! $overlay_opacity ) {
        $overlay_opacity = 30;
    }
    if ( function_exists( 'ay_aip_base_hex_to_rgba' ) ) {
        $overlay_rgba = ay_aip_base_hex_to_rgba( $overlay_color, $overlay_opacity / 100 );
        $overlay_style = 'background:' . esc_attr( $overlay_rgba ) . ';';
    } else {
        $overlay_style = 'background:' . esc_attr( $overlay_color ) . ';';
    }
}

if ( ! $bg_image ) {
    $bg_image = [
        'url' => get_template_directory_uri() . '/img/passenger-air-vehicle-parked-on-the-airport-apron-2024-10-18-09-02-37-utc-scaled.jpg',
        'alt' => $title ?: __( 'Hero background', 'ay-aip-base' ),
    ];
}

$bg_markup = '';
if ( $bg_image ) {
    $bg_markup = sprintf( '<img src="%s" alt="%s">', esc_url( $bg_image['url'] ), esc_attr( $bg_image['alt'] ?: $title ) );
}
$section_classes = 'block-hero';
if ( 'small' === $variant ) {
    $section_classes = 'block-hero-short';
} elseif ( in_array( $layout, [ 'compact', 'block-hero-compact' ], true ) ) {
    $section_classes .= ' block-hero-compact';
}
$show_buttons = ( 'small' !== $variant ) && $buttons;
?>
<section class="<?php echo esc_attr( $section_classes ); ?>">
    <div class="hero-background">
        <?php echo $bg_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </div>
    <div class="hero-overlay" style="<?php echo esc_attr( $overlay_style ); ?>"></div>
    <div class="hero-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10 col-xl-8 text-center">
                    <?php if ( $title ) : ?>
                        <h1 class="hero-title" data-aos="fade"<?php echo $text_color_attr; ?>><?php echo esc_html( $title ); ?></h1>
                    <?php endif; ?>
                    <?php if ( $subhead ) : ?>
                        <p class="hero-description" data-aos="fade" data-aos-delay="100"<?php echo $text_color_attr; ?>><?php echo wp_kses_post( $subhead ); ?></p>
                    <?php endif; ?>
                    <?php if ( $show_buttons ) : ?>
                        <div class="hero-actions" data-aos="fade" data-aos-delay="200">
                            <?php foreach ( (array) $buttons as $index => $button ) :
                                $link   = ( isset( $button['link'] ) && is_array( $button['link'] ) ) ? $button['link'] : [];
                                $label  = $link['title'] ?? ( $button['label'] ?? __( 'Learn More', 'ay-aip-base' ) );
                                $url    = $link['url'] ?? ( $button['url'] ?? '#' );
                                $target = $link['target'] ?? ( $button['target'] ?? '_self' );
                                $rel_attr = ( '_blank' === $target ) ? 'noopener noreferrer' : '';
                                $style_tokens = ay_aip_base_get_button_style_tokens(
                                    [
                                        'button_style_type'       => $button['button_style_type'] ?? '',
                                        'button_background_color' => $button['button_background_color'] ?? '',
                                        'button_border_color'     => $button['button_border_color'] ?? '',
                                        'button_text_color'       => $button['button_text_color'] ?? '',
                                        'style'                   => $button['style'] ?? '',
                                    ],
                                    [
                                        'type'       => 0 === (int) $index ? 'solid' : 'outline',
                                        'background' => 0 === (int) $index ? '#ffffff' : '',
                                        'border'     => '#ffffff',
                                        'text'       => 0 === (int) $index ? '#223a69' : '#ffffff',
                                    ]
                                );
                                $base_class = ( 'solid' === $style_tokens['type'] ) ? 'btn-light' : 'btn-outline-light';
                                $btn_class  = 'btn ' . $base_class;
                                ?>
                                <a href="<?php echo esc_url( $url ); ?>" class="<?php echo esc_attr( $btn_class ); ?>" target="<?php echo esc_attr( $target ); ?>"<?php echo $rel_attr ? ' rel="' . esc_attr( $rel_attr ) . '"' : ''; ?><?php echo $style_tokens['style']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $label ); ?></a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

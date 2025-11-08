<?php
$background = ay_aip_base_get_block_field( 'logo_grid_background', 'white' );
$title      = ay_aip_base_get_block_field( 'logo_grid_title' );
$subtitle   = ay_aip_base_get_block_field( 'logo_grid_subtitle' );
$logos      = ay_aip_base_get_block_field( 'logo_grid_items', [] );

$section_class = 'block-logo-grid';
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

        <?php if ( $logos ) : ?>
            <div class="logo-grid">
                <?php
                $delay = 100;
                foreach ( $logos as $logo ) :
                    $wrapper = '<div class="logo-wrapper">%s</div>';
                    $content = '';
                    if ( ! empty( $logo['logo_svg'] ) ) {
                        $content = wp_kses_post( $logo['logo_svg'] );
                    } elseif ( ! empty( $logo['logo_image']['url'] ) ) {
                        $content = sprintf(
                            '<img src="%s" alt="%s" />',
                            esc_url( $logo['logo_image']['url'] ),
                            esc_attr( $logo['company_name'] ?? '' )
                        );
                    } elseif ( ! empty( $logo['company_name'] ) ) {
                        $content = sprintf( '<span>%s</span>', esc_html( $logo['company_name'] ) );
                    }
                    if ( ! $content ) {
                        continue;
                    }
                    $link_open  = '';
                    $link_close = '';
                    if ( ! empty( $logo['company_url'] ) ) {
                        $link_open  = '<a href="' . esc_url( $logo['company_url'] ) . '" target="_blank" rel="noopener noreferrer">';
                        $link_close = '</a>';
                    }
                    ?>
                    <div class="logo-item" data-aos="fade" data-aos-delay="<?php echo esc_attr( $delay ); ?>">
                        <?php echo $link_open; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        <?php printf( $wrapper, $content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        <?php echo $link_close; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </div>
                    <?php
                    $delay += 50;
                    if ( $delay > 250 ) {
                        $delay = 100;
                    }
                endforeach;
                ?>
            </div>
        <?php endif; ?>
    </div>
</section>

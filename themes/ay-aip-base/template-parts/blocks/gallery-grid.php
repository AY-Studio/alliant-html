<?php
$background = ay_aip_base_get_block_field( 'gallery_background', 'white' );
$title      = ay_aip_base_get_block_field( 'gallery_title' );
$subtitle   = ay_aip_base_get_block_field( 'gallery_subtitle' );
$items      = ay_aip_base_get_block_field( 'gallery_items', [] );

$section_class = 'block-gallery';
if ( 'light' === $background ) {
    $section_class .= ' bg-light';
}
?>
<section class="<?php echo esc_attr( $section_class ); ?>">
    <div class="container">
        <?php if ( $title || $subtitle ) : ?>
            <div class="gallery-header text-center mb-5" data-aos="fade">
                <?php if ( $title ) : ?>
                    <h2 class="block-title"><?php echo esc_html( $title ); ?></h2>
                <?php endif; ?>
                <?php if ( $subtitle ) : ?>
                    <p><?php echo esc_html( $subtitle ); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ( $items ) : ?>
            <div class="gallery-grid">
                <?php
                $delay = 100;
                foreach ( $items as $item ) :
                    $image = $item['image'] ?? null;
                    if ( ! $image ) {
                        $image = [
                            'url' => ay_aip_base_get_theme_asset_url( 'img/passenger-air-vehicle-parked-on-the-airport-apron-2024-10-18-09-02-37-utc-scaled.jpg' ),
                            'alt' => $item['title'] ?? '',
                        ];
                    }
                    $link = $item['link'] ?? $image['url'];
                    ?>
                    <div class="gallery-item" data-aos="fade" data-aos-delay="<?php echo esc_attr( $delay ); ?>">
                        <a href="<?php echo esc_url( $link ); ?>" class="gallery-link">
                            <img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ?? ( $item['title'] ?? '' ) ); ?>" class="img-fluid">
                            <?php if ( ! empty( $item['title'] ) || ! empty( $item['description'] ) ) : ?>
                                <div class="gallery-overlay">
                                    <div class="gallery-caption">
                                        <?php if ( ! empty( $item['title'] ) ) : ?>
                                            <h4><?php echo esc_html( $item['title'] ); ?></h4>
                                        <?php endif; ?>
                                        <?php if ( ! empty( $item['description'] ) ) : ?>
                                            <p><?php echo esc_html( $item['description'] ); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </a>
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

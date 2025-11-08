<?php
$background = ay_aip_base_get_block_field( 'accordion_background', 'white' );
$title      = ay_aip_base_get_block_field( 'accordion_title' );
$items      = ay_aip_base_get_block_field( 'accordion_items', [] );

$section_class = 'block-accordion';
if ( 'light' === $background ) {
    $section_class .= ' bg-light';
}
$accordion_id = uniqid( 'ay-accordion-' );
?>
<section class="<?php echo esc_attr( $section_class ); ?>">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <?php if ( $title ) : ?>
                    <h2 class="block-title text-center mb-5" data-aos="fade"><?php echo esc_html( $title ); ?></h2>
                <?php endif; ?>
                <?php if ( $items ) : ?>
                    <div class="accordion" id="<?php echo esc_attr( $accordion_id ); ?>" data-aos="fade" data-aos-delay="100">
                        <?php
                        $index = 1;
                        foreach ( $items as $item ) :
                            $heading_id = $accordion_id . '-heading-' . $index;
                            $collapse_id = $accordion_id . '-collapse-' . $index;
                            $is_open = ! empty( $item['default_open'] );
                            ?>
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="<?php echo esc_attr( $heading_id ); ?>">
                                    <button class="accordion-button<?php echo $is_open ? '' : ' collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo esc_attr( $collapse_id ); ?>" aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( $collapse_id ); ?>">
                                        <?php echo esc_html( $item['title'] ?? '' ); ?>
                                    </button>
                                </h3>
                                <div id="<?php echo esc_attr( $collapse_id ); ?>" class="accordion-collapse collapse<?php echo $is_open ? ' show' : ''; ?>" aria-labelledby="<?php echo esc_attr( $heading_id ); ?>" data-bs-parent="#<?php echo esc_attr( $accordion_id ); ?>">
                                    <div class="accordion-body">
                                        <?php echo wp_kses_post( $item['content'] ?? '' ); ?>
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
        </div>
    </div>
</section>

<?php
$items = ay_aip_base_get_block_field( 'stats_items', [] );
?>
<section class="block-stats">
    <div class="container">
        <div class="stats-grid" data-aos="fade">
            <?php if ( $items ) :
                $delay = 100;
                foreach ( $items as $item ) : ?>
                    <div class="stat-item" data-aos="fade" data-aos-delay="<?php echo esc_attr( $delay ); ?>">
                        <?php if ( ! empty( $item['value'] ) ) : ?>
                            <div class="stat-number"><?php echo esc_html( $item['value'] ); ?></div>
                        <?php endif; ?>
                        <?php if ( ! empty( $item['heading'] ) ) : ?>
                            <div class="stat-label"><?php echo esc_html( $item['heading'] ); ?></div>
                        <?php endif; ?>
                    </div>
                <?php
                    $delay += 100;
                endforeach;
            endif; ?>
        </div>
    </div>
</section>

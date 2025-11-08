<?php
$background = ay_aip_base_get_block_field( 'careers_background', 'white' );
$title      = ay_aip_base_get_block_field( 'careers_title' );
$subtitle   = ay_aip_base_get_block_field( 'careers_subtitle' );
$jobs       = ay_aip_base_get_block_field( 'careers_jobs', [] );
$cta_title  = ay_aip_base_get_block_field( 'careers_cta_title' );
$cta_body   = ay_aip_base_get_block_field( 'careers_cta_description' );
$cta_label  = ay_aip_base_get_block_field( 'careers_cta_label' );
$cta_url    = ay_aip_base_get_block_field( 'careers_cta_url' );

$section_class = 'block-careers';
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

        <?php if ( $jobs ) : ?>
            <div class="careers-list">
                <?php
                $delay = 100;
                foreach ( $jobs as $job ) : ?>
                    <div class="career-item" data-aos="fade" data-aos-delay="<?php echo esc_attr( $delay ); ?>">
                        <div class="career-header">
                            <div class="career-main">
                                <?php if ( ! empty( $job['title'] ) ) : ?>
                                    <h3 class="career-title"><?php echo esc_html( $job['title'] ); ?></h3>
                                <?php endif; ?>
                                <div class="career-meta">
                                    <?php if ( ! empty( $job['location'] ) ) : ?>
                                        <span class="career-location">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                                <circle cx="12" cy="10" r="3"/>
                                            </svg>
                                            <?php echo esc_html( $job['location'] ); ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if ( ! empty( $job['type'] ) ) : ?>
                                        <span class="career-type">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                                <rect x="2" y="7" width="20" height="14" rx="2"/>
                                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                                            </svg>
                                            <?php echo esc_html( $job['type'] ); ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if ( ! empty( $job['department'] ) ) : ?>
                                        <span class="career-department"><?php echo esc_html( $job['department'] ); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php if ( ! empty( $job['apply_label'] ) && ! empty( $job['apply_url'] ) ) : ?>
                                <div class="career-action">
                                    <a href="<?php echo esc_url( $job['apply_url'] ); ?>" class="btn btn-primary"><?php echo esc_html( $job['apply_label'] ); ?></a>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if ( ! empty( $job['description'] ) ) : ?>
                            <div class="career-description">
                                <p><?php echo esc_html( $job['description'] ); ?></p>
                            </div>
                        <?php endif; ?>
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

        <?php if ( $cta_title || $cta_body || ( $cta_label && $cta_url ) ) : ?>
            <div class="careers-cta" data-aos="fade" data-aos-delay="300">
                <div class="careers-cta-content">
                    <?php if ( $cta_title ) : ?>
                        <h3><?php echo esc_html( $cta_title ); ?></h3>
                    <?php endif; ?>
                    <?php if ( $cta_body ) : ?>
                        <p><?php echo esc_html( $cta_body ); ?></p>
                    <?php endif; ?>
                    <?php if ( $cta_label && $cta_url ) : ?>
                        <a href="<?php echo esc_url( $cta_url ); ?>" class="btn btn-outline-primary btn-lg"><?php echo esc_html( $cta_label ); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

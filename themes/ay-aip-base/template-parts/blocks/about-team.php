<?php
$title    = ay_aip_base_get_block_field( 'about_team_title' );
$subtitle = ay_aip_base_get_block_field( 'about_team_subtitle' );
$members  = ay_aip_base_get_block_field( 'about_team_members', [] );
?>
<section class="section section-light py-6">
    <div class="container">
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
                    $photo = $member['photo'] ?? null;
                    if ( ! $photo && ! empty( $member['photo_url'] ) ) {
                        $photo = [
                            'url' => esc_url( $member['photo_url'] ),
                            'alt' => $member['name'] ?? __( 'Team member', 'ay-aip-base' ),
                        ];
                    }
                    if ( ! $photo ) {
                        $photo = [
                            'url' => ay_aip_base_get_theme_asset_url( 'img/person.jpg' ),
                            'alt' => $member['name'] ?? __( 'Team member', 'ay-aip-base' ),
                        ];
                    }
                    $delay = $delays[ $index % count( $delays ) ];
                    ?>
                    <div class="col-12 col-sm-6 col-lg-4" data-aos="fade" data-aos-delay="<?php echo esc_attr( $delay ); ?>">
                        <div class="card border-0 team-card h-100 text-center">
                            <?php if ( $photo ) : ?>
                                <img src="<?php echo esc_url( $photo['url'] ); ?>" class="card-img-top" alt="<?php echo esc_attr( $photo['alt'] ?? ( $member['name'] ?? __( 'Team member', 'ay-aip-base' ) ) ); ?>">
                            <?php endif; ?>
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

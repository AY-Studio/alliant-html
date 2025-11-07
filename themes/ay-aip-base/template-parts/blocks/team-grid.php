<?php
$items         = get_field( 'team_members' );
$bg_variant    = get_field( 'team_background' ) === 'light' ? ' bg-light' : '';
$section_title = get_field( 'team_section_title' );
$section_sub   = get_field( 'team_section_subtitle' );
?>
<section class="block-team-grid<?php echo esc_attr( $bg_variant ); ?>">
    <div class="container">
        <?php if ( $section_title || $section_sub ) : ?>
            <div class="text-center mb-5" data-aos="fade">
                <?php if ( $section_title ) : ?>
                    <h2 class="block-title"><?php echo esc_html( $section_title ); ?></h2>
                <?php endif; ?>
                <?php if ( $section_sub ) : ?>
                    <p class="block-subtitle"><?php echo esc_html( $section_sub ); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <div class="team-grid">
            <?php if ( $items ) :
                $delay = 100;
                foreach ( $items as $member ) : ?>
                    <div class="team-member-card" data-aos="fade" data-aos-delay="<?php echo esc_attr( $delay ); ?>">
                        <?php if ( ! empty( $member['photo'] ) ) : ?>
                            <div class="team-member-image">
                                <img src="<?php echo esc_url( $member['photo']['sizes']['ay_aip_base_card'] ?? $member['photo']['url'] ); ?>" alt="<?php echo esc_attr( $member['photo']['alt'] ?: $member['name'] ); ?>">
                            </div>
                        <?php endif; ?>
                        <div class="team-member-content">
                            <?php if ( ! empty( $member['name'] ) ) : ?>
                                <h3 class="team-member-name"><?php echo esc_html( $member['name'] ); ?></h3>
                            <?php endif; ?>
                            <?php if ( ! empty( $member['title'] ) ) : ?>
                                <div class="team-member-title"><?php echo esc_html( $member['title'] ); ?></div>
                            <?php endif; ?>
                            <?php if ( ! empty( $member['bio'] ) ) : ?>
                                <p class="team-member-bio"><?php echo esc_html( $member['bio'] ); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php
                    $delay += 100;
                endforeach;
            endif; ?>
        </div>
    </div>
</section>

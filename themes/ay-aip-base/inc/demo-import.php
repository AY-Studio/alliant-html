<?php
/**
 * Starter content importer for AY AIP Base.
 */

add_action( 'admin_menu', 'ay_aip_base_register_import_page' );
function ay_aip_base_register_import_page() {
    add_theme_page(
        __( 'AY Starter Setup', 'ay-aip-base' ),
        __( 'AY Starter Setup', 'ay-aip-base' ),
        'manage_options',
        'ay-aip-base-import',
        'ay_aip_base_render_import_page'
    );
}

function ay_aip_base_render_import_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $status = isset( $_GET['ay-import'] ) ? sanitize_text_field( wp_unslash( $_GET['ay-import'] ) ) : '';
    if ( 'success' === $status ) {
        echo '<div class="notice notice-success"><p>' . esc_html__( 'Starter content imported. Review pages, menus, and posts before going live.', 'ay-aip-base' ) . '</p></div>';
    } elseif ( 'error' === $status ) {
        echo '<div class="notice notice-error"><p>' . esc_html__( 'Starter import failed. Check the debug log for details.', 'ay-aip-base' ) . '</p></div>';
    } elseif ( 'reset-success' === $status ) {
        echo '<div class="notice notice-success"><p>' . esc_html__( 'Starter content removed.', 'ay-aip-base' ) . '</p></div>';
    }
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'AY Starter Setup', 'ay-aip-base' ); ?></h1>
        <p><?php esc_html_e( 'Seed the site with sample pages, posts, and menus that mirror the HTML reference. All content is generic and can be updated after import.', 'ay-aip-base' ); ?></p>
        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin-bottom:2rem;">
            <?php wp_nonce_field( 'ay_aip_base_import_demo' ); ?>
            <input type="hidden" name="action" value="ay_aip_base_import_demo">
            <?php
            $presets        = ay_aip_base_get_demo_presets();
            $current_preset = ay_aip_base_get_selected_preset();
            ?>
            <p>
                <label for="ay_aip_base_preset"><strong><?php esc_html_e( 'Starter Content Preset', 'ay-aip-base' ); ?></strong></label><br>
                <select id="ay_aip_base_preset" name="ay_aip_base_preset">
                    <?php foreach ( $presets as $key => $preset ) : ?>
                        <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $current_preset, $key ); ?>>
                            <?php echo esc_html( $preset['label'] ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if ( isset( $presets[ $current_preset ] ) ) : ?>
                    <span class="description" style="display:block;margin-top:0.5rem;"><?php echo esc_html( $presets[ $current_preset ]['description'] ); ?></span>
                <?php endif; ?>
            </p>
            <p>
                <button type="submit" class="button button-primary" onclick="return confirm('<?php echo esc_js( __( 'This will create demo pages, posts, and menus. Continue?', 'ay-aip-base' ) ); ?>');">
                    <?php esc_html_e( 'Import Starter Content', 'ay-aip-base' ); ?>
                </button>
            </p>
        </form>
        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
            <?php wp_nonce_field( 'ay_aip_base_reset_demo' ); ?>
            <input type="hidden" name="action" value="ay_aip_base_reset_demo">
            <p>
                <button type="submit" class="button" onclick="return confirm('<?php echo esc_js( __( 'This will remove all imported demo content. Continue?', 'ay-aip-base' ) ); ?>');"><?php esc_html_e( 'Remove Starter Content', 'ay-aip-base' ); ?></button>
            </p>
        </form>
    </div>
    <?php
}

function ay_aip_base_get_demo_presets() {
    return [
        'alliant' => [
            'label'       => __( 'Alliant Starter Content', 'ay-aip-base' ),
            'description' => __( 'Exact replica of the Alliant AirFinance experience.', 'ay-aip-base' ),
        ],
        'default' => [
            'label'       => __( 'Default Starter Content', 'ay-aip-base' ),
            'description' => __( 'Brand-agnostic aviation finance starter with placeholder copy.', 'ay-aip-base' ),
        ],
    ];
}

function ay_aip_base_sanitize_preset( $preset ) {
    $preset  = sanitize_key( $preset );
    $presets = ay_aip_base_get_demo_presets();
    return isset( $presets[ $preset ] ) ? $preset : 'alliant';
}

function ay_aip_base_get_selected_preset() {
    $saved = get_option( 'ay_aip_base_selected_preset', 'alliant' );
    return ay_aip_base_sanitize_preset( $saved );
}

function ay_aip_base_set_selected_preset( $preset ) {
    update_option( 'ay_aip_base_selected_preset', ay_aip_base_sanitize_preset( $preset ) );
}

function ay_aip_base_set_current_preset( $preset ) {
    $GLOBALS['ay_aip_base_current_preset'] = ay_aip_base_sanitize_preset( $preset );
}

function ay_aip_base_get_current_preset() {
    if ( isset( $GLOBALS['ay_aip_base_current_preset'] ) ) {
        return $GLOBALS['ay_aip_base_current_preset'];
    }
    return ay_aip_base_get_selected_preset();
}

function ay_aip_base_get_default_replacements() {
    return [
        'Alliant AirFinance’s' => 'Luminary Aviation’s',
        'Alliant AirFinance'   => 'Luminary Aviation',
        'Alliant'              => 'Luminary',
        'ALLIANT'              => 'LUMINARY',
        'AIP Capital'          => 'Aero Partners',
        'AIP'                  => 'Aero',
        'AIP CAPITAL'          => 'AERO PARTNERS',
    ];
}

function ay_aip_base_filter_preset_content( $value ) {
    if ( 'default' !== ay_aip_base_get_current_preset() ) {
        return $value;
    }

    if ( is_array( $value ) ) {
        foreach ( $value as $key => $sub_value ) {
            $value[ $key ] = ay_aip_base_filter_preset_content( $sub_value );
        }
        return $value;
    }

    if ( is_string( $value ) ) {
        $replacements = ay_aip_base_get_default_replacements();
        return str_replace( array_keys( $replacements ), array_values( $replacements ), $value );
    }

    return $value;
}

function ay_aip_base_is_default_preset() {
    return 'default' === ay_aip_base_get_current_preset();
}

function ay_aip_base_get_lorem_text( $paragraphs = 3 ) {
    $paragraphs = max( 1, (int) $paragraphs );
    $chunks     = [];
    for ( $i = 0; $i < $paragraphs; $i++ ) {
        $chunks[] = ay_aip_base_generate_lorem_block( 110 );
    }
    return implode( "\n\n", $chunks );
}

function ay_aip_base_get_lorem_sentences() {
    return [
        'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        'Integer vitae libero eu augue malesuada aliquam quis nec nisi.',
        'Suspendisse potenti curabitur imperdiet purus nec pellentesque fermentum.',
        'Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae.',
        'Mauris ac ex vitae massa volutpat gravida in id est.',
        'Donec fringilla nisl at scelerisque rhoncus quam elit pretium arcu.',
        'Curabitur sodales nisi sit amet libero posuere eget lacinia ipsum luctus.',
        'Praesent fermentum nibh a risus sollicitudin vitae tincidunt sapien dignissim.',
        'Etiam bibendum lectus non consequat gravida.',
        'Nullam tincidunt erat sit amet eros rhoncus non auctor mi tristique.',
    ];
}

function ay_aip_base_generate_lorem_block( $word_target = 120 ) {
    $sentences = ay_aip_base_get_lorem_sentences();
    $paragraph = [];
    $word_count = 0;
    $i = 0;
    while ( $word_count < $word_target ) {
        $sentence    = $sentences[ $i % count( $sentences ) ];
        $paragraph[] = $sentence;
        $word_count += str_word_count( $sentence );
        $i++;
    }
    return implode( ' ', $paragraph );
}

function ay_aip_base_generate_lorem_wp_content( $word_target = 500 ) {
    $paragraphs = ceil( max( 100, $word_target ) / 110 );
    $chunks     = [];
    for ( $i = 0; $i < $paragraphs; $i++ ) {
        $chunks[] = '<!-- wp:paragraph --><p>' . ay_aip_base_generate_lorem_block( 110 ) . '</p><!-- /wp:paragraph -->';
    }
    return implode( '', $chunks );
}

function ay_aip_base_apply_default_section_overrides( $sections ) {
    if ( ! ay_aip_base_is_default_preset() ) {
        return $sections;
    }

    $value_titles   = [ 'Value Title One', 'Value Title Two', 'Value Title Three' ];
    $value_desc     = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed vitae libero sed neque ultrices pretium.';
    $icon_titles    = [ 'Icon Title One', 'Icon Title Two', 'Icon Title Three', 'Icon Title Four', 'Icon Title Five', 'Icon Title Six' ];
    $icon_desc      = 'Praesent vel augue ut neque pharetra porta a eget lorem.';
    $offering_desc  = 'Placeholder copy describing this offering. Lorem ipsum dolor sit amet, consectetur adipiscing elit.';

    foreach ( $sections as &$section ) {
        $layout = $section['acf_fc_layout'] ?? '';
        switch ( $layout ) {
            case 'hero':
                $section['hero_heading']    = ay_aip_base_is_default_preset() ? __( 'Sample Title', 'ay-aip-base' ) : 'Aviation Capital Solutions';
                $section['hero_subheading'] = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla facilisi.';
                if ( empty( $section['hero_text_color'] ) ) {
                    $section['hero_text_color'] = '#ffffff';
                }
                if ( ! empty( $section['hero_buttons'] ) ) {
                    foreach ( $section['hero_buttons'] as $index => &$button ) {
                        $label                  = 0 === $index ? __( 'Get Started', 'ay-aip-base' ) : __( 'View Capabilities', 'ay-aip-base' );
                        $button['link']['title'] = $label;
                        if ( empty( $button['link']['url'] ) ) {
                            $button['link']['url'] = '#';
                        }
                        if ( empty( $button['button_style_type'] ) ) {
                            $button['button_style_type'] = 0 === $index ? 'solid' : 'outline';
                        }
                    }
                    unset( $button );
                }
                break;
            case 'hero_section':
                if ( ay_aip_base_is_default_preset() ) {
                    $current = strtolower( $section['hero_section_heading'] ?? '' );
                    if ( false !== strpos( $current, 'news' ) ) {
                        $section['hero_section_heading'] = __( 'News & Insights', 'ay-aip-base' );
                    } elseif ( false !== strpos( $current, 'contact' ) ) {
                        $section['hero_section_heading'] = __( 'Contact Us', 'ay-aip-base' );
                    } elseif ( false !== strpos( $current, 'about' ) || false !== strpos( $current, 'mission' ) ) {
                        $section['hero_section_heading'] = __( 'About Us', 'ay-aip-base' );
                    } else {
                        $section['hero_section_heading'] = __( 'Sample Title', 'ay-aip-base' );
                    }
                } else {
                    $section['hero_section_heading'] = 'Partner With Our Team';
                }
                $section['hero_section_lead']    = 'Phasellus pulvinar leo nec purus placerat, et efficitur risus scelerisque.';
                if ( isset( $section['cta_link'] ) ) {
                    $section['cta_link']['title'] = __( 'Connect With Us', 'ay-aip-base' );
                    if ( empty( $section['cta_link']['url'] ) ) {
                        $section['cta_link']['url'] = '#';
                    }
                }
                if ( empty( $section['hero_section_text_color'] ) ) {
                    $section['hero_section_text_color'] = '#ffffff';
                }
                if ( empty( $section['hero_section_button_style_type'] ) ) {
                    $section['hero_section_button_style_type'] = 'outline';
                }
                break;
            case 'value_cards':
                $section['value_cards_title']     = __( 'Our Core Values', 'ay-aip-base' );
                $section['value_cards_subtitle']  = 'Fusce tristique sapien vitae quam molestie, in luctus mi mollis.';
                if ( ! empty( $section['value_cards_cards'] ) ) {
                    foreach ( $section['value_cards_cards'] as $idx => &$card ) {
                        $card['title']       = $value_titles[ $idx % count( $value_titles ) ];
                        $card['description'] = $value_desc;
                    }
                    unset( $card );
                }
                break;
            case 'icon_features':
                $section['icon_features_title']    = __( 'Icon Feature Highlights', 'ay-aip-base' );
                $section['icon_features_subtitle'] = 'Sed sed enim aliquet, tincidunt sem sed, fermentum diam.';
                if ( ! empty( $section['icon_features_items'] ) ) {
                    foreach ( $section['icon_features_items'] as $idx => &$item ) {
                        $item['title']       = $icon_titles[ $idx % count( $icon_titles ) ];
                        $item['description'] = $icon_desc;
                        $item['icon_name']   = $item['icon_name'] ?? 'solid:circle';
                    }
                    unset( $item );
                }
                break;
            case 'product_offerings':
                $section['product_offerings_title']    = __( 'Product Offerings', 'ay-aip-base' );
                $section['product_offerings_subtitle'] = 'Integer sagittis libero ut lorem iaculis, sed feugiat turpis tempus.';
                if ( ! empty( $section['product_offerings_items'] ) ) {
                    foreach ( $section['product_offerings_items'] as $idx => &$item ) {
                        $item['title']       = sprintf( 'Offering Title %02d', $idx + 1 );
                        $item['description'] = $offering_desc;
                    }
                    unset( $item );
                }
                break;
            case 'text_content':
                $section['text_content_title'] = __( 'Component Title', 'ay-aip-base' );
                $section['text_content_body']  = '<p>' . ay_aip_base_get_lorem_text( 1 ) . '</p>';
                break;
            case 'contact_form':
                $section['contact_heading'] = __( 'Start a Conversation', 'ay-aip-base' );
                $section['contact_body']    = 'Vivamus convallis leo nec dui placerat, non hendrerit nulla laoreet.';
                break;
            case 'about_team':
                if ( ! empty( $section['about_team_members'] ) ) {
                    foreach ( $section['about_team_members'] as $idx => &$member ) {
                        $member['name']  = sprintf( 'Team Member %02d', $idx + 1 );
                        $member['title'] = __( 'Leadership', 'ay-aip-base' );
                    }
                    unset( $member );
                }
                break;
            case 'card_grid':
                if ( ! empty( $section['card_items'] ) ) {
                    foreach ( $section['card_items'] as $idx => &$item ) {
                        $item['heading'] = sprintf( 'Card Heading %02d', $idx + 1 );
                        $item['body']    = 'Suspendisse in magna arcu. Integer ac sem et turpis porta ullamcorper.';
                    }
                    unset( $item );
                }
                break;
        }
    }
    unset( $section );

    return $sections;
}

function ay_aip_base_prepare_sections_for_save( $sections ) {
    return ay_aip_base_apply_default_section_overrides( ay_aip_base_filter_preset_content( $sections ) );
}

function ay_aip_base_apply_theme_color_defaults( $preset ) {
    $preset = ay_aip_base_sanitize_preset( $preset );
    if ( 'default' === $preset ) {
        $colors = [
            'primary'        => '#333333',
            'accent'         => '#f5a623',
            'nav'            => '#333333',
            'heading'        => '#222222',
            'body'           => '#4b4b4b',
            'card_bg'        => '#ffffff',
            'card_text'      => '#333333',
            'news_card_bg'   => '#ffffff',
            'news_card_text' => '#333333',
        ];
    } else {
        $colors = [
            'primary'        => '#1f3a63',
            'accent'         => '#4a7dff',
            'nav'            => '#223a69',
            'heading'        => '#1f3a63',
            'body'           => '#223a69',
            'card_bg'        => '#223a69',
            'card_text'      => '#ffffff',
            'news_card_bg'   => '#ffffff',
            'news_card_text' => '#223a69',
        ];
    }

    $option_map = [
        'theme_primary_color'        => 'primary',
        'theme_accent_color'         => 'accent',
        'theme_nav_background'       => 'nav',
        'theme_heading_color'        => 'heading',
        'theme_body_color'           => 'body',
        'theme_card_background'      => 'card_bg',
        'theme_card_text'            => 'card_text',
        'theme_news_card_background' => 'news_card_bg',
        'theme_news_card_text'       => 'news_card_text',
    ];

    foreach ( $option_map as $option_key => $color_key ) {
        if ( isset( $colors[ $color_key ] ) ) {
            ay_aip_base_update_theme_option( $option_key, $colors[ $color_key ] );
        }
    }

    $theme_mod_map = [
        'ay_aip_base_primary_color'        => 'primary',
        'ay_aip_base_accent_color'         => 'accent',
        'ay_aip_base_nav_background'       => 'nav',
        'ay_aip_base_heading_color'        => 'heading',
        'ay_aip_base_body_color'           => 'body',
        'ay_aip_base_card_background'      => 'card_bg',
        'ay_aip_base_card_text'            => 'card_text',
        'ay_aip_base_news_card_background' => 'news_card_bg',
        'ay_aip_base_news_card_text'       => 'news_card_text',
    ];

    foreach ( $theme_mod_map as $theme_mod => $color_key ) {
        if ( isset( $colors[ $color_key ] ) ) {
            set_theme_mod( $theme_mod, $colors[ $color_key ] );
        }
    }

    if ( 'default' === $preset ) {
        $logo_url = ay_aip_base_get_theme_asset_url( 'img/logo-sample.jpg' );
        ay_aip_base_update_theme_option( 'theme_header_logo', [
            'ID'  => 0,
            'url' => $logo_url,
            'alt' => __( 'Sample Logo', 'ay-aip-base' ),
        ] );
        ay_aip_base_update_theme_option( 'theme_footer_logo', [
            'ID'  => 0,
            'url' => $logo_url,
            'alt' => __( 'Sample Logo', 'ay-aip-base' ),
        ] );
    }
}

function ay_aip_base_update_theme_option( $key, $value ) {
    if ( function_exists( 'update_field' ) && acf_get_field( $key, 'option' ) ) {
        update_field( $key, $value, 'option' );
    } else {
        update_option( $key, $value );
    }
}

add_action( 'admin_post_ay_aip_base_import_demo', 'ay_aip_base_handle_import' );
function ay_aip_base_handle_import() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'Insufficient permissions.', 'ay-aip-base' ) );
    }
    check_admin_referer( 'ay_aip_base_import_demo' );

    $preset = isset( $_POST['ay_aip_base_preset'] ) ? sanitize_text_field( wp_unslash( $_POST['ay_aip_base_preset'] ) ) : ay_aip_base_get_selected_preset();
    $preset = ay_aip_base_sanitize_preset( $preset );
    ay_aip_base_set_selected_preset( $preset );

    $result = ay_aip_base_run_import( $preset );
    $status = $result ? 'success' : 'error';
    wp_safe_redirect( add_query_arg( 'ay-import', $status, admin_url( 'themes.php?page=ay-aip-base-import' ) ) );
    exit;
}

add_action( 'admin_post_ay_aip_base_reset_demo', 'ay_aip_base_handle_reset' );
function ay_aip_base_handle_reset() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'Insufficient permissions.', 'ay-aip-base' ) );
    }
    check_admin_referer( 'ay_aip_base_reset_demo' );
    ay_aip_base_run_reset();
    wp_safe_redirect( add_query_arg( 'ay-import', 'reset-success', admin_url( 'themes.php?page=ay-aip-base-import' ) ) );
    exit;
}

function ay_aip_base_run_import( $preset = '' ) {
    require_once ABSPATH . 'wp-admin/includes/taxonomy.php';
    require_once ABSPATH . 'wp-admin/includes/post.php';

    if ( empty( $preset ) ) {
        $preset = ay_aip_base_get_selected_preset();
    }
    ay_aip_base_set_current_preset( $preset );

    $home_content    = ay_aip_base_home_content();
    $about_content   = ay_aip_base_about_content();
    $news_content    = ay_aip_base_news_content();
    $contact_content = ay_aip_base_contact_content();
    $blocks_content  = ay_aip_base_blocks_content();
    $terms_content   = ay_aip_base_terms_content();
    $privacy_content = ay_aip_base_privacy_content();

    $home_id    = ay_aip_base_upsert_page( 'Home', 'home', $home_content, 'templates/template-pagebuilder.php' );
    $about_id   = ay_aip_base_upsert_page( 'About', 'about', $about_content, 'templates/template-pagebuilder.php' );
    $news_id    = ay_aip_base_upsert_page( 'News & Insights', 'news', $news_content );
    $contact_id = ay_aip_base_upsert_page( 'Contact', 'contact', $contact_content, 'templates/template-pagebuilder.php' );
    $terms_id   = ay_aip_base_upsert_page( 'Terms & Conditions', 'terms', $terms_content );
    $privacy_id = ay_aip_base_upsert_page( 'Privacy Policy', 'privacy-policy', $privacy_content );
    $blocks_id  = ay_aip_base_upsert_page( 'Blocks', 'blocks', $blocks_content, 'templates/template-pagebuilder.php' );
    if ( $blocks_id ) {
        ay_aip_base_seed_blocks_builder( $blocks_id );
    }
    if ( $home_id ) {
        ay_aip_base_seed_home_builder( $home_id );
    }
    if ( $about_id ) {
        ay_aip_base_seed_about_builder( $about_id );
    }
    if ( $contact_id ) {
        ay_aip_base_seed_contact_builder( $contact_id );
    }

    if ( ! $home_id ) {
        return false;
    }

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $home_id );
    if ( $news_id ) {
        update_option( 'page_for_posts', $news_id );
    }

    ay_aip_base_create_posts();
    ay_aip_base_setup_menus( $home_id, $about_id, $news_id, $contact_id, $terms_id, $privacy_id );
    ay_aip_base_apply_theme_color_defaults( $preset );

    return true;
}

function ay_aip_base_run_reset() {
    $demo_posts = get_posts( [
        'post_type'      => [ 'page', 'post', 'attachment' ],
        'post_status'    => 'any',
        'meta_key'       => '_ay_aip_base_demo',
        'meta_value'     => 1,
        'posts_per_page' => -1,
        'fields'         => 'ids',
    ] );

    foreach ( $demo_posts as $post_id ) {
        wp_delete_post( $post_id, true );
    }

    delete_option( 'page_on_front' );
    delete_option( 'page_for_posts' );
    update_option( 'show_on_front', 'posts' );

    $stored_menus = get_option( 'ay_aip_base_demo_menu' );
    $menu_id = $stored_menus;
    if ( $menu_id ) {
        if ( is_array( $menu_id ) ) {
        foreach ( $menu_id as $menu ) {
            if ( $menu ) {
                wp_delete_nav_menu( $menu );
            }
        }
    } else {
        wp_delete_nav_menu( $menu_id );
    }
    delete_option( 'ay_aip_base_demo_menu' );
    }

    remove_theme_mod( 'nav_menu_locations' );

    delete_option( 'ay_aip_base_demo_media' );
    delete_option( 'ay_aip_base_media_migrated' );
}

function ay_aip_base_upsert_page( $title, $slug, $content, $template = '' ) {
    $page = get_page_by_path( $slug );
    if ( $page ) {
        update_post_meta( $page->ID, '_ay_aip_base_demo', 1 );
        wp_update_post( [ 'ID' => $page->ID, 'post_content' => $content, 'page_template' => $template ] );
        return $page->ID;
    }
    $page_id = wp_insert_post( [
        'post_title'     => $title,
        'post_name'      => $slug,
        'post_status'    => 'publish',
        'post_type'      => 'page',
        'post_content'   => $content,
        'page_template'  => $template,
        'comment_status' => 'closed',
    ] );
    if ( $page_id && ! is_wp_error( $page_id ) ) {
        update_post_meta( $page_id, '_ay_aip_base_demo', 1 );
        return (int) $page_id;
    }
    return 0;
}

function ay_aip_base_create_posts() {
    $samples = ay_aip_base_is_default_preset() ? ay_aip_base_get_default_post_samples() : [
        [
            'title'   => 'Engine Leasing Outlook 2025',
            'content' => '<!-- wp:paragraph --><p>Engine aftermarket demand is driving new leasing structures as operators seek flexible maintenance solutions.</p><!-- /wp:paragraph -->',
        ],
        [
            'title'   => 'Capital Markets Update: Aviation ABS',
            'content' => '<!-- wp:paragraph --><p>Investor appetite for aviation ABS remains high with spreads stabilizing as traffic normalizes.</p><!-- /wp:paragraph -->',
        ],
        [
            'title'   => 'Regional Airline Growth Opportunities',
            'content' => '<!-- wp:paragraph --><p>Regional carriers are expanding route maps and leveraging sale-leasebacks to free working capital.</p><!-- /wp:paragraph -->',
        ],
        [
            'title'   => 'Aircraft ABS Performance Review',
            'content' => '<!-- wp:paragraph --><p>Performance of legacy aircraft ABS pools continues to improve as utilization and lease rates recover.</p><!-- /wp:paragraph -->',
        ],
    ];

    foreach ( $samples as $sample ) {
        $existing = get_page_by_title( $sample['title'], OBJECT, 'post' );
        if ( $existing ) {
            update_post_meta( $existing->ID, '_ay_aip_base_demo', 1 );
            continue;
        }
        $post_id = wp_insert_post( [
            'post_title'   => $sample['title'],
            'post_content' => $sample['content'],
            'post_status'  => 'publish',
            'post_type'    => 'post',
        ] );
        if ( $post_id && ! is_wp_error( $post_id ) ) {
            update_post_meta( $post_id, '_ay_aip_base_demo', 1 );
        }
    }
}

function ay_aip_base_get_default_post_samples() {
    $samples = [];
    for ( $i = 1; $i <= 4; $i++ ) {
        $samples[] = [
            'title'   => sprintf( 'Article Title %02d', $i ),
            'content' => ay_aip_base_generate_lorem_wp_content( 520 ),
        ];
    }
    return $samples;
}

function ay_aip_base_setup_menus( $home_id, $about_id, $news_id, $contact_id, $terms_id, $privacy_id ) {
    $menu = wp_get_nav_menu_object( 'Primary Menu' );
    if ( ! $menu ) {
        $menu_id = wp_create_nav_menu( 'Primary Menu' );
    } else {
        $menu_id = $menu->term_id;
    }

    if ( $menu_id && is_nav_menu( $menu_id ) ) {
        $existing_items = wp_get_nav_menu_items( $menu_id );
        if ( $existing_items ) {
            foreach ( $existing_items as $menu_item ) {
                wp_delete_post( $menu_item->ID, true );
            }
        }

        $items = array_filter( [
            $home_id ? [ 'id' => $home_id, 'title' => get_the_title( $home_id ) ] : null,
            $about_id ? [ 'id' => $about_id, 'title' => get_the_title( $about_id ) ] : null,
            $news_id ? [ 'id' => $news_id, 'title' => __( 'Insights', 'ay-aip-base' ) ] : null,
            $contact_id ? [ 'id' => $contact_id, 'title' => get_the_title( $contact_id ) ] : null,
        ] );
        $footer_items = array_filter( [
            $home_id ? [ 'id' => $home_id, 'title' => get_the_title( $home_id ) ] : null,
            $about_id ? [ 'id' => $about_id, 'title' => get_the_title( $about_id ) ] : null,
            $news_id ? [ 'id' => $news_id, 'title' => __( 'Insights', 'ay-aip-base' ) ] : null,
            $contact_id ? [ 'id' => $contact_id, 'title' => get_the_title( $contact_id ) ] : null,
            $terms_id ? [ 'id' => $terms_id, 'title' => get_the_title( $terms_id ) ] : null,
            $privacy_id ? [ 'id' => $privacy_id, 'title' => get_the_title( $privacy_id ) ] : null,
        ] );
        foreach ( $items as $item ) {
            wp_update_nav_menu_item( $menu_id, 0, [
                'menu-item-object-id' => $item['id'],
                'menu-item-object'    => 'page',
                'menu-item-type'      => 'post_type',
                'menu-item-title'     => $item['title'],
                'menu-item-status'    => 'publish',
            ] );
        }
        $footer_menu = wp_get_nav_menu_object( 'Footer Menu' );
        if ( ! $footer_menu ) {
            $footer_menu_id = wp_create_nav_menu( 'Footer Menu' );
        } else {
            $footer_menu_id = $footer_menu->term_id;
        }
        if ( $footer_menu_id && is_nav_menu( $footer_menu_id ) ) {
            $existing_footer_items = wp_get_nav_menu_items( $footer_menu_id );
            if ( $existing_footer_items ) {
                foreach ( $existing_footer_items as $footer_item ) {
                    wp_delete_post( $footer_item->ID, true );
                }
            }
            foreach ( $footer_items as $item ) {
                wp_update_nav_menu_item( $footer_menu_id, 0, [
                    'menu-item-object-id' => $item['id'],
                    'menu-item-object'    => 'page',
                    'menu-item-type'      => 'post_type',
                    'menu-item-title'     => $item['title'],
                    'menu-item-status'    => 'publish',
                ] );
            }
        }
        $locations = get_nav_menu_locations();
        $locations['primary'] = $menu_id;
        if ( ! empty( $footer_menu_id ) ) {
            $locations['footer'] = $footer_menu_id;
        }
        set_theme_mod( 'nav_menu_locations', $locations );
        update_option( 'ay_aip_base_demo_menu', [ 'primary' => $menu_id, 'footer' => $footer_menu_id ?? $menu_id ] );
    }
}


function ay_aip_base_wrap_html_block( $html ) {
    return "<!-- wp:html -->
" . $html . "
<!-- /wp:html -->";
}

function ay_aip_base_get_demo_html( $slug ) {
    $path = AY_AIP_BASE_DIR . '/demo-html/' . $slug . '.html';
    if ( ! file_exists( $path ) ) {
        return '';
    }
    $html = file_get_contents( $path );
    if ( false === $html ) {
        return '';
    }
    $theme_uri = esc_url( get_template_directory_uri() );
    $html      = str_replace( '{{theme_uri}}', $theme_uri, $html );
    return trim( $html );
}

function ay_aip_base_import_demo_image( $source, $title = '' ) {
    static $cache  = [];
    static $stored = null;

    if ( empty( $source ) ) {
        return 0;
    }
    if ( isset( $cache[ $source ] ) ) {
        return $cache[ $source ];
    }

    if ( null === $stored ) {
        $stored = get_option( 'ay_aip_base_demo_media', [] );
        if ( ! is_array( $stored ) ) {
            $stored = [];
        }
    }

    $key = md5( $source );
    if ( isset( $stored[ $key ] ) ) {
        $attachment_id = absint( $stored[ $key ] );
        if ( $attachment_id && get_post( $attachment_id ) ) {
            $cache[ $source ] = $attachment_id;
            return $attachment_id;
        }
    }

    if ( ! function_exists( 'media_handle_sideload' ) ) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
    }

    $is_remote = filter_var( $source, FILTER_VALIDATE_URL );
    $tmp       = '';
    if ( $is_remote ) {
        $tmp = download_url( $source );
        if ( is_wp_error( $tmp ) ) {
            return 0;
        }
        $filename = wp_basename( parse_url( $source, PHP_URL_PATH ) );
        if ( ! $filename ) {
            $filename = 'demo-media-' . time() . '.jpg';
        }
    } else {
        $path = $source;
        if ( 0 !== strpos( $path, '/' ) ) {
            $path = AY_AIP_BASE_DIR . '/' . ltrim( $path, '/' );
        }
        if ( ! file_exists( $path ) ) {
            return 0;
        }
        $filename = wp_basename( $path );
        $tmp      = wp_tempnam( $filename );
        if ( ! $tmp || ! copy( $path, $tmp ) ) {
            return 0;
        }
    }

    $file_array = [
        'name'     => sanitize_file_name( $filename ),
        'tmp_name' => $tmp,
    ];

    $attachment_id = media_handle_sideload( $file_array, 0, $title );
    if ( is_wp_error( $attachment_id ) ) {
        @unlink( $tmp );
        return 0;
    }

    update_post_meta( $attachment_id, '_ay_aip_base_demo', 1 );
    $stored[ $key ] = $attachment_id;
    update_option( 'ay_aip_base_demo_media', $stored );
    $cache[ $source ] = $attachment_id;

    return $attachment_id;
}

function ay_aip_base_get_demo_value_card_images() {
    return [
        'global'      => ay_aip_base_import_demo_image( 'img/value-card-1.jpg', 'Value Card - Global' ),
        'experienced' => ay_aip_base_import_demo_image( 'img/value-card-2.jpg', 'Value Card - Experienced' ),
        'innovative'  => ay_aip_base_import_demo_image( 'img/value-card-3.jpg', 'Value Card - Innovative' ),
    ];
}

function ay_aip_base_get_demo_product_icons() {
    return [
        'bridge'        => ay_aip_base_import_demo_image( 'img/ico/bridge.svg', 'Bridge Financing Icon' ),
        'structured'    => ay_aip_base_import_demo_image( 'img/ico/structured.svg', 'Structured Credit Icon' ),
        'portfolio'     => ay_aip_base_import_demo_image( 'img/ico/portfolio.svg', 'Portfolio Financing Icon' ),
        'payments'      => ay_aip_base_import_demo_image( 'img/ico/payments.svg', 'Pre-Delivery Payments Icon' ),
        'hybrid'        => ay_aip_base_import_demo_image( 'img/ico/hybrid-debt.svg', 'Hybrid Debt Icon' ),
        'leases'        => ay_aip_base_import_demo_image( 'img/ico/diamond.svg', 'Operating & Finance Leases Icon' ),
        'aircraft'      => ay_aip_base_import_demo_image( 'img/ico/aircraft.svg', 'Aircraft Icon' ),
        'engines'       => ay_aip_base_import_demo_image( 'img/ico/engines.svg', 'Engines Icon' ),
        'ffp'           => ay_aip_base_import_demo_image( 'img/ico/frequent-flyer.svg', 'Frequent Flyer Programs Icon' ),
        'slots'         => ay_aip_base_import_demo_image( 'img/ico/slots-gates.svg', 'Slots, Gates & Routes Icon' ),
        'ground'        => ay_aip_base_import_demo_image( 'img/ico/ground.svg', 'Ground Equipment Icon' ),
        'receivables'   => ay_aip_base_import_demo_image( 'img/ico/recievables.svg', 'Receivables Icon' ),
    ];
}

function ay_aip_base_match_value_image_key( $title ) {
    $title = strtolower( $title );
    if ( false !== strpos( $title, 'global' ) ) {
        return 'global';
    }
    if ( false !== strpos( $title, 'experienced' ) ) {
        return 'experienced';
    }
    if ( false !== strpos( $title, 'innovative' ) ) {
        return 'innovative';
    }
    return '';
}

function ay_aip_base_match_product_icon_key( $title ) {
    $map = [
        'bridge-financing'                      => 'bridge',
        'structured-credit'                     => 'structured',
        'portfolio-financing-revolving-facilities' => 'portfolio',
        'pre-delivery-payments'                 => 'payments',
        'hybrid-debt'                           => 'hybrid',
        'operating-finance-leases'              => 'leases',
        'aircraft'                              => 'aircraft',
        'engines'                               => 'engines',
        'frequent-flyer-programs'               => 'ffp',
        'slots-gates-routes'                    => 'slots',
        'ground-equipment'                      => 'ground',
        'receivables'                           => 'receivables',
        'sale-leasebacks'                       => 'bridge',
        'sale-leaseback'                        => 'bridge',
        'engine-pools'                          => 'engines',
        'abs-advisory'                          => 'structured',
        'structured-finance'                    => 'structured',
        'advisory-services'                     => 'ffp',
        'specialized-financing'                 => 'hybrid',
    ];
    $slug = sanitize_title( $title );
    return $map[ $slug ] ?? '';
}

function ay_aip_base_match_icon_feature_icon( $title ) {
    $map = [
        'flexible-solutions'   => 'solid:sliders',
        'fast-approvals'       => 'solid:gauge-high',
        'global-reach'         => 'solid:globe',
        'expert-team'          => 'solid:people-group',
        'proven-track-record'  => 'solid:chart-line',
        'trusted-partner'      => 'solid:handshake',
    ];
    $slug = sanitize_title( $title );
    return $map[ $slug ] ?? '';
}

function ay_aip_base_make_link_field( $url = '', $title = '', $target = '_self' ) {
    $url   = trim( $url );
    $title = trim( $title );
    $target = $target ?: '_self';

    if ( '' === $url && '' === $title ) {
        return null;
    }

    return [
        'url'    => $url ? esc_url_raw( $url ) : '',
        'title'  => $title,
        'target' => $target,
    ];
}

function ay_aip_base_home_content() {
    return ay_aip_base_filter_preset_content( '' );
}

function ay_aip_base_about_content() {
    return ay_aip_base_filter_preset_content( '' );
}

function ay_aip_base_news_content() {
    // News page uses home.php template with ACF hero fields, no content needed
    return '';
}

function ay_aip_base_contact_content() {
    return ay_aip_base_filter_preset_content( '' );
}

function ay_aip_base_terms_content() {
    $html = ay_aip_base_get_demo_html( 'terms' );
    return ay_aip_base_filter_preset_content( $html ? ay_aip_base_wrap_html_block( $html ) : '' );
}

function ay_aip_base_privacy_content() {
    $html = ay_aip_base_get_demo_html( 'privacy' );
    return ay_aip_base_filter_preset_content( $html ? ay_aip_base_wrap_html_block( $html ) : '' );
}

function ay_aip_base_blocks_content() {
    return '';
}
function ay_aip_base_seed_home_builder( $page_id ) {
    if ( ! function_exists( 'update_field' ) || ! $page_id ) {
        return;
    }

    $value_images  = ay_aip_base_get_demo_value_card_images();
    $product_icons = ay_aip_base_get_demo_product_icons();
    $hero_bg_image = ay_aip_base_import_demo_image( 'img/passenger-air-vehicle-parked-on-the-airport-apron-2024-10-18-09-02-37-utc-scaled.jpg', 'Hero Background' );

    $sections = [
        [
            'acf_fc_layout'       => 'hero_section',
            'hero_section_heading'=> 'Specialist Aviation Finance',
            'hero_section_lead'   => 'Alliant AirFinance is an experienced commercial aviation lending platform with deep expertise in the aviation finance sector.',
            'hero_section_background_image' => $hero_bg_image,
            'hero_section_overlay_color' => '#223a69',
            'hero_section_overlay_opacity' => 30,
            'hero_section_text_color' => '#ffffff',
            'hero_section_button_style_type' => 'outline',
            'cta_link'            => ay_aip_base_make_link_field( '#contact', 'Contact Us' ),
        ],
        [
            'acf_fc_layout'           => 'value_cards',
            'value_cards_background'  => 'white',
            'value_cards_title'       => 'Alliant Values',
            'value_cards_subtitle'    => 'Our values are more than statements on a page — they define who we are and how we work. They guide our decisions, shape our culture, and set the standard for the impact we create. Each value reflects a commitment to our clients, our people, and our shared future.',
            'value_cards_cards'       => [
                [
                    'title'       => 'We Are Global',
                    'description' => 'Our reach spans markets and cultures, giving us the insight to navigate complexity and connect opportunities worldwide. By embracing diversity and building partnerships that last, we deliver capital and solutions aligned to our clients\' risk.',
                    'image'       => $value_images['global'],
                ],
                [
                    'title'       => 'We Are Experienced',
                    'description' => 'Our track record spans years of guiding clients across the full aviation lifecycle—from acquisition to exit, through changing markets and evolving regulations. Experience shapes our decisions and sharpens the value we bring to every transaction.',
                    'image'       => $value_images['experienced'],
                ],
                [
                    'title'       => 'We Are Innovative',
                    'description' => 'Curiosity drives us to challenge convention and explore better ways forward. We tailor financing structures with precision, apply emerging technologies with purpose, and rethink traditional approaches where others see only risk.',
                    'image'       => $value_images['innovative'],
                ],
            ],
            'value_cards_button_label' => 'Learn more',
            'value_cards_button_url'   => home_url( '/about/' ),
        ],
        [
            'acf_fc_layout'             => 'product_offerings',
            'product_offerings_theme'   => 'grey',
            'product_offerings_title'   => 'Product Offerings',
            'product_offerings_subtitle'=> 'Alliant AirFinance believes its full-suite of product offerings, along with Alliant AirFinance’s certainty of execution, positions Alliant AirFinance as a preferred solutions provider for counterparties.',
            'product_offerings_items'   => [
                [ 'icon_image' => $product_icons['bridge'], 'title' => 'Bridge Financing' ],
                [ 'icon_image' => $product_icons['structured'], 'title' => 'Structured Credit' ],
                [ 'icon_image' => $product_icons['portfolio'], 'title' => 'Portfolio Financing / Revolving Facilities' ],
                [ 'icon_image' => $product_icons['payments'], 'title' => 'Pre-Delivery Payments' ],
                [ 'icon_image' => $product_icons['hybrid'], 'title' => 'Hybrid Debt' ],
                [ 'icon_image' => $product_icons['leases'], 'title' => 'Operating & Finance Leases' ],
                [ 'icon_image' => $product_icons['aircraft'], 'title' => 'Aircraft' ],
                [ 'icon_image' => $product_icons['engines'], 'title' => 'Engines' ],
                [ 'icon_image' => $product_icons['ffp'], 'title' => 'Frequent Flyer Programs' ],
                [ 'icon_image' => $product_icons['slots'], 'title' => 'Slots Gates & Routes' ],
                [ 'icon_image' => $product_icons['ground'], 'title' => 'Ground Equipment' ],
                [ 'icon_image' => $product_icons['receivables'], 'title' => 'Receivables' ],
            ],
        ],
    ];

    $sections = ay_aip_base_filter_preset_content( $sections );
    $sections = ay_aip_base_filter_preset_content( $sections );
    update_field( 'field_page_sections_flexible', ay_aip_base_prepare_sections_for_save( $sections ), $page_id );
}

function ay_aip_base_seed_about_builder( $page_id ) {
    if ( ! function_exists( 'update_field' ) || ! $page_id ) {
        return;
    }

    $person_image  = ay_aip_base_import_demo_image( 'img/person.jpg', 'Team Member' );
    $hero_bg_image = ay_aip_base_import_demo_image( 'img/passenger-air-vehicle-parked-on-the-airport-apron-2024-10-18-09-02-37-utc-scaled.jpg', 'Hero Background' );

    $sections = [
        [
            'acf_fc_layout'        => 'hero_section',
            'hero_section_heading' => 'Alliant Mission',
            'hero_section_lead'    => 'Alliant AirFinance believes its asset expertise and platform capabilities enable us to provide counterparties bespoke financial solutions across asset types and capital structures.',
            'hero_section_background_image' => $hero_bg_image,
            'hero_section_overlay_color' => '#223a69',
            'hero_section_overlay_opacity' => 30,
            'hero_section_text_color' => '#ffffff',
            'hero_section_button_style_type' => 'outline',
        ],
        [
            'acf_fc_layout'        => 'about_team',
            'about_team_title'     => 'Our Team',
            'about_team_subtitle'  => 'The Alliant team brings together deep expertise, diverse perspectives, and a shared commitment to delivering tailored solutions that help clients navigate complexity with confidence.',
            'about_team_members'   => [
                [ 'name' => 'Firstname Lastname', 'title' => 'Chief Executive Officer', 'photo' => $person_image ],
                [ 'name' => 'Firstname Lastname', 'title' => 'Chief Financial Officer', 'photo' => $person_image ],
                [ 'name' => 'Firstname Lastname', 'title' => 'Head of Aviation Finance', 'photo' => $person_image ],
                [ 'name' => 'Firstname Lastname', 'title' => 'Managing Director', 'photo' => $person_image ],
                [ 'name' => 'Firstname Lastname', 'title' => 'Senior Vice President', 'photo' => $person_image ],
            ],
        ],
    ];

    $sections = ay_aip_base_filter_preset_content( $sections );
    update_field( 'field_page_sections_flexible', ay_aip_base_prepare_sections_for_save( $sections ), $page_id );
}

function ay_aip_base_seed_contact_builder( $page_id ) {
    if ( ! function_exists( 'update_field' ) || ! $page_id ) {
        return;
    }

    $hero_bg_image = ay_aip_base_import_demo_image( 'img/passenger-air-vehicle-parked-on-the-airport-apron-2024-10-18-09-02-37-utc-scaled.jpg', 'Hero Background' );

    $sections = [
        [
            'acf_fc_layout'        => 'hero_section',
            'hero_section_heading' => 'Contact Alliant AirFinance',
            'hero_section_lead'    => 'To learn more about our services or discuss how we can support your equipment financing needs, feel free to get in touch with our team today.',
            'hero_section_background_image' => $hero_bg_image,
            'hero_section_overlay_color' => '#223a69',
            'hero_section_overlay_opacity' => 30,
            'hero_section_text_color' => '#ffffff',
            'hero_section_button_style_type' => 'outline',
        ],
        [
            'acf_fc_layout'   => 'contact_form',
            'contact_heading' => 'Send Us A Message',
            'contact_body'    => 'The Alliant team brings together deep expertise, diverse perspectives, and a shared commitment to delivering tailored solutions that help clients navigate complexity with confidence.',
            'contact_form_id' => 0,
        ],
    ];

    $sections = ay_aip_base_filter_preset_content( $sections );
    update_field( 'field_page_sections_flexible', ay_aip_base_prepare_sections_for_save( $sections ), $page_id );
}

function ay_aip_base_seed_blocks_builder( $page_id ) {
    if ( ! function_exists( 'update_field' ) || ! $page_id ) {
        return;
    }

    $value_images  = ay_aip_base_get_demo_value_card_images();
    $product_icons = ay_aip_base_get_demo_product_icons();
    $hero_bg_image = ay_aip_base_import_demo_image( 'img/passenger-air-vehicle-parked-on-the-airport-apron-2024-10-18-09-02-37-utc-scaled.jpg', 'Hero Background' );

    $sections = [
        [
            'acf_fc_layout'       => 'hero',
            'hero_variant'        => 'large',
            'hero_layout_style'   => 'default',
            'hero_text_mode'      => 'light',
            'hero_text_color'     => '#ffffff',
            'hero_heading'        => 'Your Trusted Partner in Aviation Finance',
            'hero_subheading'     => 'Delivering flexible financing solutions for commercial airlines, business aviation, and aircraft operators worldwide.',
            'hero_buttons'        => [
                [
                    'link'  => ay_aip_base_make_link_field( home_url( '/contact/' ), 'Get Started' ),
                    'style' => 'primary',
                    'button_style_type'       => 'solid',
                ],
                [
                    'link'  => ay_aip_base_make_link_field( home_url( '/about/' ), 'Learn More' ),
                    'style' => 'secondary',
                    'button_style_type'       => 'outline',
                ],
            ],
        ],
        [
            'acf_fc_layout'       => 'hero_section',
            'hero_section_heading'=> 'Specialist Aviation Finance',
            'hero_section_lead'   => 'Alliant AirFinance is an experienced commercial aviation lending platform with deep expertise in the aviation finance sector.',
            'hero_section_background_image' => $hero_bg_image,
            'hero_section_overlay_color' => '#223a69',
            'hero_section_overlay_opacity' => 30,
            'hero_section_text_color' => '#ffffff',
            'hero_section_button_style_type' => 'outline',
            'cta_link'            => ay_aip_base_make_link_field( '#contact', 'Contact Us' ),
        ],
        [
            'acf_fc_layout'       => 'hero',
            'hero_variant'        => 'small',
            'hero_layout_style'   => 'default',
            'hero_text_mode'      => 'light',
            'hero_text_color'     => '#ffffff',
            'hero_heading'        => 'Aviation Finance Solutions',
            'hero_subheading'     => 'Flexible financing for commercial airlines and business aviation worldwide.',
        ],
        [
            'acf_fc_layout'           => 'text_content',
            'text_content_background'  => 'white',
            'text_content_title'       => 'Comprehensive Aviation Finance Solutions',
            'text_content_body'        => '<p>At Alliant AirFinance, we specialize in providing tailored financing solutions for the aviation industry. Our team combines deep industry expertise with innovative financial structuring to help clients achieve their aircraft acquisition goals.</p><p>Whether you\'re a commercial airline expanding your fleet, a private operator seeking business jet financing, or a lessor managing aircraft portfolios, we deliver customized solutions that align with your operational requirements and financial objectives.</p><p>With over three decades of experience in aviation finance, we understand the unique challenges and opportunities in this dynamic sector. Our commitment to excellence and client service has made us a trusted partner for aviation financing across the globe.</p>',
        ],
        [
            'acf_fc_layout'        => 'icon_features',
            'icon_features_background' => 'white',
            'icon_features_title'  => 'Why Choose Alliant',
            'icon_features_subtitle' => 'Trusted expertise in aviation finance',
            'icon_features_items'  => [
                [
                    'icon_name' => 'solid:sliders',
                    'title'       => 'Flexible Solutions',
                    'description' => 'Customized financing structures tailored to your specific operational and financial requirements.',
                ],
                [
                    'icon_name' => 'solid:gauge-high',
                    'title'       => 'Fast Approvals',
                    'description' => 'Streamlined processes and quick decision-making to meet your timeline needs.',
                ],
                [
                    'icon_name' => 'solid:globe',
                    'title'       => 'Global Reach',
                    'description' => 'International expertise with experience across multiple jurisdictions and markets.',
                ],
                [
                    'icon_name' => 'solid:people-group',
                    'title'       => 'Expert Team',
                    'description' => 'Decades of combined experience in aviation finance and aircraft transactions.',
                ],
                [
                    'icon_name' => 'solid:chart-line',
                    'title'       => 'Proven Track Record',
                    'description' => 'Successfully financed over $5 billion in aircraft transactions worldwide.',
                ],
                [
                    'icon_name' => 'solid:handshake',
                    'title'       => 'Trusted Partner',
                    'description' => 'Long-term relationships built on transparency, integrity, and exceptional service.',
                ],
            ],
        ],
        [
            'acf_fc_layout'          => 'value_cards',
            'value_cards_title'      => 'Value Cards',
            'value_cards_subtitle'   => 'Showcase gradient-backed cards with imagery and overlays.',
            'value_cards_cards'      => [
                [ 'title' => 'Card One', 'description' => 'Sample supporting copy.', 'image' => $value_images['global'] ],
                [ 'title' => 'Card Two', 'description' => 'Highlight differentiators or proof points.', 'image' => $value_images['experienced'] ],
                [ 'title' => 'Card Three', 'description' => 'Keep descriptions concise.', 'image' => $value_images['innovative'] ],
            ],
            'value_cards_button_label' => 'Primary CTA',
            'value_cards_button_url'   => home_url( '/about/' ),
        ],
        [
            'acf_fc_layout'        => 'card_grid',
            'card_section_title'   => 'Capital solutions across the lifecycle',
            'card_section_subtitle'=> 'Deploy bespoke structures that unlock liquidity without compromising fleet flexibility.',
            'card_background'      => 'light',
            'card_items'           => [
                [
                    'icon'       => 'fas fa-plane-departure',
                    'heading'    => 'Sale-Leasebacks',
                    'body'       => 'Source competitive lease terms for new deliveries and mid-life aircraft.',
                    'cta_label'  => 'Explore structure',
                    'cta_url'    => home_url( '/about/' ),
                ],
                [
                    'icon'       => 'fas fa-industry',
                    'heading'    => 'Engine Pools',
                    'body'       => 'Optimize shop visit timing with shared spare engine programs.',
                    'cta_label'  => 'See program',
                    'cta_url'    => home_url( '/contact/' ),
                ],
                [
                    'icon'       => 'fas fa-chart-line',
                    'heading'    => 'ABS Advisory',
                    'body'       => 'Bring portfolios to market with data-backed investor materials.',
                    'cta_label'  => 'Request deck',
                    'cta_url'    => home_url( '/news/' ),
                ],
                [
                    'icon'       => 'fas fa-briefcase',
                    'heading'    => 'Sale-Leaseback',
                    'body'       => 'Unlock capital from your existing fleet while maintaining operational control.',
                    'cta_label'  => 'Learn More',
                    'cta_url'    => home_url( '/about/' ),
                ],
                [
                    'icon'       => 'fas fa-shield-alt',
                    'heading'    => 'Structured Finance',
                    'body'       => 'Custom solutions for complex transactions across multiple jurisdictions.',
                    'cta_label'  => 'Learn More',
                    'cta_url'    => home_url( '/contact/' ),
                ],
                [
                    'icon'       => 'fas fa-people-group',
                    'heading'    => 'Advisory Services',
                    'body'       => 'Strategic consulting on fleet planning, market analysis, and transaction structuring.',
                    'cta_label'  => 'Learn More',
                    'cta_url'    => home_url( '/news/' ),
                ],
            ],
        ],
        [
            'acf_fc_layout'             => 'product_offerings',
            'product_offerings_theme'   => 'grey',
            'product_offerings_title'   => 'Product Offerings',
            'product_offerings_subtitle'=> 'Grey/white card grid with iconography.',
            'product_offerings_items'   => [
                [ 'icon_image' => $product_icons['bridge'], 'title' => 'Bridge Financing', 'description' => 'Short-term capital between commitments.' ],
                [ 'icon_image' => $product_icons['structured'], 'title' => 'Structured Credit', 'description' => 'Layered debt stacks and hybrids.' ],
                [ 'icon_image' => $product_icons['portfolio'], 'title' => 'Portfolio Financing / Revolving Facilities', 'description' => 'Revolving facilities for fleets.' ],
                [ 'icon_image' => $product_icons['payments'], 'title' => 'Pre-Delivery Payments' ],
                [ 'icon_image' => $product_icons['hybrid'], 'title' => 'Hybrid Debt' ],
                [ 'icon_image' => $product_icons['leases'], 'title' => 'Operating & Finance Leases' ],
                [ 'icon_image' => $product_icons['aircraft'], 'title' => 'Aircraft' ],
                [ 'icon_image' => $product_icons['engines'], 'title' => 'Engines' ],
                [ 'icon_image' => $product_icons['ffp'], 'title' => 'Frequent Flyer Programs' ],
                [ 'icon_image' => $product_icons['slots'], 'title' => 'Slots Gates & Routes' ],
                [ 'icon_image' => $product_icons['ground'], 'title' => 'Ground Equipment' ],
                [ 'icon_image' => $product_icons['receivables'], 'title' => 'Receivables' ],
            ],
        ],
        [
            'acf_fc_layout'      => 'values',
            'values_title'       => 'Our Core Values',
            'values_subtitle'    => 'The principles that guide everything we do',
            'values_items'       => [
                [
                    'number'      => '01',
                    'value_icon_svg' => '<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
                    'title'       => 'Integrity',
                    'description' => 'We operate with transparency and honesty in every transaction.',
                ],
                [
                    'number'      => '02',
                    'value_icon_svg' => '<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>',
                    'title'       => 'Expertise',
                    'description' => 'Decades of aviation finance experience enable us to provide innovative solutions.',
                ],
                [
                    'number'      => '03',
                    'value_icon_svg' => '<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
                    'title'       => 'Partnership',
                    'description' => 'We view our clients as long-term partners and work collaboratively.',
                ],
                [
                    'number'      => '04',
                    'value_icon_svg' => '<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>',
                    'title'       => 'Excellence',
                    'description' => 'We strive for the highest standards in service delivery.',
                ],
            ],
        ],
        [
            'acf_fc_layout'          => 'media_content',
            'media_content_background'=> 'white',
            'media_content_alignment' => 'left',
            'media_content_title'     => 'Tailored Aviation Finance Solutions',
            'media_content_body'      => '<p>With over 30 years of experience in the aviation industry, we understand the unique challenges that come with aircraft financing.</p><p>From commercial airlines to private operators, we provide flexible financing options that help you acquire the aircraft you need to grow your business.</p>',
            'media_content_button_label' => 'Learn More',
            'media_content_button_url'   => home_url( '/about/' ),
        ],
        [
            'acf_fc_layout'          => 'media_content',
            'media_content_background'=> 'white',
            'media_content_alignment' => 'right',
            'media_content_title'     => 'Expert Team & Industry Knowledge',
            'media_content_body'      => '<p>Our dedicated team brings together decades of combined experience in aviation finance, investment banking, and aircraft operations.</p><p>Whether you\'re looking to finance a single aircraft or an entire fleet, our experts provide the guidance and support you need.</p>',
            'media_content_button_label' => 'Meet Our Team',
            'media_content_button_url'   => home_url( '/about/' ),
        ],
        [
            'acf_fc_layout' => 'stats',
            'stats_items'   => [
                [ 'value' => '$12B+', 'heading' => 'Aircraft financed' ],
                [ 'value' => '45+', 'heading' => 'Countries supported' ],
                [ 'value' => '120', 'heading' => 'Engine events managed annually' ],
                [ 'value' => '98%', 'heading' => 'Client retention' ],
            ],
        ],
        [
            'acf_fc_layout'            => 'testimonial',
            'testimonial_background'   => 'white',
            'testimonial_quote'        => 'Alliant AirFinance provided exceptional service throughout our fleet financing process. Their deep industry knowledge and flexible approach helped us structure a deal that perfectly aligned with our operational needs.',
            'testimonial_author_name'  => 'John Anderson',
            'testimonial_author_title' => 'CEO, Global Air Services',
        ],
        [
            'acf_fc_layout'        => 'team_grid',
            'team_section_title'   => 'Leadership team',
            'team_section_subtitle'=> 'Cross-functional experts spanning aircraft trading, ABS structuring, and airline operations.',
            'team_background'      => 'light',
            'team_members'         => [
                [
                    'name'  => 'Maya Chen',
                    'title' => 'Chief Investment Officer',
                    'bio'   => 'Leads portfolio construction and capital markets execution across commercial programs.',
                ],
                [
                    'name'  => 'Luca Martinez',
                    'title' => 'Head of Technical Services',
                    'bio'   => 'Oversees maintenance strategy and engine event optimization for global operators.',
                ],
                [
                    'name'  => 'Priya Natarajan',
                    'title' => 'Managing Director, Advisory',
                    'bio'   => 'Partners with airlines on balance-sheet restructuring and structured finance transactions.',
                ],
            ],
        ],
        [
            'acf_fc_layout' => 'cta_banner',
            'cta_icon'      => '<i class="fas fa-headset"></i>',
            'cta_heading'   => 'Ready to structure your next aviation transaction?',
            'cta_body'      => 'Our specialists build tailored capital stacks for airlines, OEMs, and lessors navigating volatile markets.',
            'cta_buttons'   => [
                [
                    'label' => 'Schedule a call',
                    'url'   => home_url( '/contact/' ),
                    'style' => 'primary',
                ],
                [
                    'label' => 'Download overview',
                    'url'   => home_url( '/about/' ),
                    'style' => 'secondary',
                ],
            ],
        ],
        [
            'acf_fc_layout'        => 'pricing',
            'pricing_background'   => 'white',
            'pricing_title'        => 'Financing Solutions',
            'pricing_subtitle'     => 'Flexible options tailored to your needs',
            'pricing_cards'        => [
                [
                    'title'    => 'Direct Financing',
                    'subtitle' => 'Traditional loan structure',
                    'features' => [
                        [ 'text' => 'Competitive interest rates' ],
                        [ 'text' => 'Flexible terms up to 15 years' ],
                        [ 'text' => 'Fixed or variable rates' ],
                        [ 'text' => 'Quick approval process' ],
                        [ 'text' => 'Minimal documentation' ],
                    ],
                    'button_label' => 'Learn More',
                    'button_url'   => home_url( '/contact/' ),
                    'button_style' => 'outline',
                ],
                [
                    'title'    => 'Operating Lease',
                    'subtitle' => 'Off-balance sheet solution',
                    'featured' => 1,
                    'badge_text' => 'Most Popular',
                    'features' => [
                        [ 'text' => 'Preserve capital' ],
                        [ 'text' => 'Tax advantages' ],
                        [ 'text' => 'Flexible end-of-lease options' ],
                        [ 'text' => 'No balloon payment' ],
                        [ 'text' => 'Maintenance options available' ],
                    ],
                    'button_label' => 'Get Started',
                    'button_url'   => home_url( '/contact/' ),
                    'button_style' => 'primary',
                ],
                [
                    'title'    => 'Sale-Leaseback',
                    'subtitle' => 'Unlock aircraft value',
                    'features' => [
                        [ 'text' => 'Immediate liquidity' ],
                        [ 'text' => 'Maintain operational control' ],
                        [ 'text' => 'Improve balance sheet' ],
                        [ 'text' => 'Fund growth initiatives' ],
                        [ 'text' => 'Transparent process' ],
                    ],
                    'button_label' => 'Learn More',
                    'button_url'   => home_url( '/contact/' ),
                    'button_style' => 'outline',
                ],
            ],
        ],
        [
            'acf_fc_layout'      => 'video',
            'video_background'   => 'white',
            'video_title'        => 'See How We Work',
            'video_url'          => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'video_caption'      => 'Learn about our comprehensive approach to aviation finance and how we partner with clients worldwide.',
        ],
        [
            'acf_fc_layout'      => 'accordion',
            'accordion_title'    => 'Frequently Asked Questions',
            'accordion_items'    => [
                [
                    'title'        => 'What types of aircraft financing do you offer?',
                    'content'      => 'We provide comprehensive financing solutions for commercial jets, business aviation, cargo aircraft, and rotorcraft, including direct loans, sale-leasebacks, and structured products.',
                    'default_open' => 1,
                ],
                [
                    'title'   => 'How quickly can you close a transaction?',
                    'content' => 'Our streamlined diligence process allows us to issue indicative terms within days and close most transactions within 30-45 days.',
                ],
                [
                    'title'   => 'Do you operate globally?',
                    'content' => 'Yes, our team supports operators across North America, Latin America, EMEA, and APAC with localized expertise.',
                ],
            ],
        ],
        [
            'acf_fc_layout'      => 'gallery',
            'gallery_background' => 'white',
            'gallery_title'      => 'Our Portfolio',
            'gallery_subtitle'   => 'Explore aircraft we have helped finance',
            'gallery_items'      => [
                [ 'title' => 'Commercial Aviation', 'description' => 'Narrowbody + widebody programs' ],
                [ 'title' => 'Business Aviation', 'description' => 'Private jet financing' ],
                [ 'title' => 'Regional Aircraft', 'description' => 'Turboprop and regional jets' ],
                [ 'title' => 'Cargo Operations', 'description' => 'Freighter conversions' ],
                [ 'title' => 'Rotorcraft', 'description' => 'Helicopter portfolios' ],
                [ 'title' => 'Asset Management', 'description' => 'Full lifecycle support' ],
            ],
        ],
        [
            'acf_fc_layout'        => 'logo_grid',
            'logo_grid_title'      => 'Trusted by Leading Aviation Companies',
            'logo_grid_subtitle'   => 'Partners worldwide rely on our expertise',
            'logo_grid_items'      => [
                [ 'logo_svg' => '<svg width="120" height="40" viewBox="0 0 120 40" fill="currentColor" opacity="0.6"><text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" font-size="14" font-weight="600">AIRLINE CO</text></svg>' ],
                [ 'logo_svg' => '<svg width="120" height="40" viewBox="0 0 120 40" fill="currentColor" opacity="0.6"><text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" font-size="14" font-weight="600">JET SERVICES</text></svg>' ],
                [ 'logo_svg' => '<svg width="120" height="40" viewBox="0 0 120 40" fill="currentColor" opacity="0.6"><text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" font-size="14" font-weight="600">SKYLINE</text></svg>' ],
                [ 'logo_svg' => '<svg width="120" height="40" viewBox="0 0 120 40" fill="currentColor" opacity="0.6"><text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" font-size="14" font-weight="600">GLOBAL AIR</text></svg>' ],
                [ 'logo_svg' => '<svg width="120" height="40" viewBox="0 0 120 40" fill="currentColor" opacity="0.6"><text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" font-size="14" font-weight="600">AVIATION+</text></svg>' ],
                [ 'logo_svg' => '<svg width="120" height="40" viewBox="0 0 120 40" fill="currentColor" opacity="0.6"><text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" font-size="14" font-weight="600">AERO GROUP</text></svg>' ],
            ],
        ],
        [
            'acf_fc_layout'        => 'locations',
            'locations_title'      => 'Global Presence',
            'locations_subtitle'   => 'Serving clients worldwide',
            'locations_items'      => [
                [
                    'name'        => 'New York',
                    'designation' => 'Headquarters',
                    'address'     => "200 Park Avenue
New York, NY 10166
USA",
                    'phone'       => '+1 212 555 0188',
                    'email'       => 'nyc@alliantairfinance.com',
                    'location_icon_svg' => '<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>',
                ],
                [
                    'name'        => 'London',
                    'designation' => 'EMEA Office',
                    'address'     => "100 Bishopsgate
London EC2N 4AG
United Kingdom",
                    'phone'       => '+44 20 3695 0000',
                    'email'       => 'london@alliantairfinance.com',
                    'location_icon_svg' => '<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>',
                ],
                [
                    'name'        => 'Singapore',
                    'designation' => 'APAC Office',
                    'address'     => "8 Marina Boulevard #05-02
Marina Bay Financial Centre
Singapore 018981",
                    'phone'       => '+65 6808 7888',
                    'email'       => 'singapore@alliantairfinance.com',
                    'location_icon_svg' => '<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>',
                ],
                [
                    'name'        => 'Dubai',
                    'designation' => 'Middle East Office',
                    'address'     => "Level 14, The Gate Building
Dubai International Financial Centre
Dubai, UAE",
                    'phone'       => '+971 4 363 4400',
                    'email'       => 'dubai@alliantairfinance.com',
                    'location_icon_svg' => '<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>',
                ],
            ],
        ],
        [
            'acf_fc_layout'      => 'careers',
            'careers_title'      => 'Join Our Team',
            'careers_subtitle'   => 'Build your career in aviation finance with industry-leading experts',
            'careers_jobs'       => [
                [
                    'title'       => 'Senior Vice President, Commercial Aviation',
                    'location'    => 'New York, NY',
                    'type'        => 'Full-time',
                    'department'  => 'Finance',
                    'description' => 'Lead commercial aircraft financing transactions, develop client relationships, and provide strategic guidance on fleet acquisition.',
                    'apply_label' => 'Apply Now',
                    'apply_url'   => home_url( '/contact/' ),
                ],
                [
                    'title'       => 'Director, Asset Management',
                    'location'    => 'London, UK',
                    'type'        => 'Full-time',
                    'department'  => 'Operations',
                    'description' => 'Oversee portfolio performance, remarketing strategies, and maintenance event planning across global fleets.',
                    'apply_label' => 'Apply Now',
                    'apply_url'   => home_url( '/contact/' ),
                ],
                [
                    'title'       => 'Vice President, Capital Markets',
                    'location'    => 'Singapore',
                    'type'        => 'Full-time',
                    'department'  => 'Capital Markets',
                    'description' => 'Execute ABS transactions, manage investor relationships, and originate structured finance mandates.',
                    'apply_label' => 'Apply Now',
                    'apply_url'   => home_url( '/contact/' ),
                ],
            ],
            'careers_cta_title'       => "Don't see the right role?",
            'careers_cta_description' => "We're always looking for exceptional talent. Send us your resume and we'll keep you in mind for future opportunities.",
            'careers_cta_label'       => 'Submit Your Resume',
            'careers_cta_url'         => home_url( '/contact/' ),
        ],
        [
            'acf_fc_layout'   => 'contact_form',
            'contact_heading' => 'Start the conversation',
            'contact_body'    => 'Share your fleet objectives and we will tailor a financing roadmap in under five business days.',
            'contact_form_id' => 1,
        ],
    ];

    update_field( 'field_page_sections_flexible', ay_aip_base_prepare_sections_for_save( $sections ), $page_id );
}

add_action( 'admin_init', 'ay_aip_base_migrate_demo_media_attachments', 20 );
function ay_aip_base_migrate_demo_media_attachments() {
    if ( ! function_exists( 'get_field' ) || ! current_user_can( 'manage_options' ) ) {
        return;
    }
    if ( get_option( 'ay_aip_base_media_migrated' ) ) {
        return;
    }

    $posts = get_posts( [
        'post_type'      => 'page',
        'post_status'    => 'any',
        'meta_key'       => '_ay_aip_base_demo',
        'meta_value'     => 1,
        'posts_per_page' => -1,
        'fields'         => 'ids',
    ] );

    if ( empty( $posts ) ) {
        return;
    }

    $value_images   = ay_aip_base_get_demo_value_card_images();
    $value_sequence = array_values( array_filter( $value_images ) );
    $product_icons  = ay_aip_base_get_demo_product_icons();

    $value_images   = ay_aip_base_get_demo_value_card_images();
    $value_sequence = array_values( array_filter( $value_images ) );
    $product_icons  = ay_aip_base_get_demo_product_icons();

    foreach ( $posts as $post_id ) {
        $sections = get_field( 'page_sections', $post_id );
        if ( empty( $sections ) || ! is_array( $sections ) ) {
            continue;
        }
        $modified = false;

        foreach ( $sections as &$section ) {
            if ( 'value_cards' === ( $section['acf_fc_layout'] ?? '' ) && ! empty( $section['value_cards_cards'] ) ) {
                $index = 0;
                foreach ( $section['value_cards_cards'] as &$card ) {
                    if ( ! empty( $card['image'] ) ) {
                        continue;
                    }
                    $key = '';
                    if ( ! empty( $card['title'] ) ) {
                        $key = ay_aip_base_match_value_image_key( $card['title'] );
                    }
                    if ( $key && ! empty( $value_images[ $key ] ) ) {
                        $card['image'] = $value_images[ $key ];
                        $modified      = true;
                        continue;
                    }
                    if ( $value_sequence ) {
                        $card['image'] = $value_sequence[ $index % count( $value_sequence ) ];
                        $index++;
                        $modified = true;
                    }
                }
            }
            if ( 'product_offerings' === ( $section['acf_fc_layout'] ?? '' ) && ! empty( $section['product_offerings_items'] ) ) {
                foreach ( $section['product_offerings_items'] as &$item ) {
                    if ( ! empty( $item['icon_image'] ) ) {
                        continue;
                    }
                    $key = '';
                    if ( ! empty( $item['title'] ) ) {
                        $key = ay_aip_base_match_product_icon_key( $item['title'] );
                    }
                    if ( $key && ! empty( $product_icons[ $key ] ) ) {
                        $item['icon_image'] = $product_icons[ $key ];
                        $modified           = true;
                        continue;
                    }
                    if ( ! empty( $product_icons ) ) {
                        $item['icon_image'] = reset( $product_icons );
                        $modified           = true;
                    }
                }
            }
            if ( 'icon_features' === ( $section['acf_fc_layout'] ?? '' ) && ! empty( $section['icon_features_items'] ) ) {
                foreach ( $section['icon_features_items'] as &$item ) {
                    if ( ! empty( $item['icon_name'] ) ) {
                        continue;
                    }
                    $key = '';
                    if ( ! empty( $item['title'] ) ) {
                        $key = ay_aip_base_match_icon_feature_icon( $item['title'] );
                    }
                    if ( $key ) {
                        $item['icon_name'] = $key;
                        $modified          = true;
                    }
                }
                unset( $item );
            }
        }
        unset( $section );

        if ( $modified ) {
            update_field( 'field_page_sections_flexible', ay_aip_base_prepare_sections_for_save( $sections ), $post_id );
        }
    }

    update_option( 'ay_aip_base_media_migrated', time() );
}

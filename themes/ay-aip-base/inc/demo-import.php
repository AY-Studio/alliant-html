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

add_action( 'admin_post_ay_aip_base_import_demo', 'ay_aip_base_handle_import' );
function ay_aip_base_handle_import() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'Insufficient permissions.', 'ay-aip-base' ) );
    }
    check_admin_referer( 'ay_aip_base_import_demo' );

    $result = ay_aip_base_run_import();
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

function ay_aip_base_run_import() {
    require_once ABSPATH . 'wp-admin/includes/taxonomy.php';
    require_once ABSPATH . 'wp-admin/includes/post.php';

    $home_content    = ay_aip_base_home_content();
    $about_content   = ay_aip_base_about_content();
    $news_content    = ay_aip_base_news_content();
    $contact_content = ay_aip_base_contact_content();
    $blocks_content  = ay_aip_base_blocks_content();
    $terms_content   = ay_aip_base_terms_content();
    $privacy_content = ay_aip_base_privacy_content();

    $home_id    = ay_aip_base_upsert_page( 'Home', 'home', $home_content );
    $about_id   = ay_aip_base_upsert_page( 'About', 'about', $about_content );
    $news_id    = ay_aip_base_upsert_page( 'News & Insights', 'news', $news_content );
    $contact_id = ay_aip_base_upsert_page( 'Contact', 'contact', $contact_content );
    $terms_id   = ay_aip_base_upsert_page( 'Terms & Conditions', 'terms', $terms_content );
    $privacy_id = ay_aip_base_upsert_page( 'Privacy Policy', 'privacy-policy', $privacy_content );
    ay_aip_base_upsert_page( 'Blocks Library', 'blocks-library', $blocks_content, 'templates/template-pagebuilder.php' );

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

    return true;
}

function ay_aip_base_run_reset() {
    $demo_posts = get_posts( [
        'post_type'      => [ 'page', 'post' ],
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
    $samples = [
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
            update_post_meta( $post_id, 'ay_source_html', $sample['html'] ?? '' );
        }
    }
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

function ay_aip_base_home_content() {
    $html = ay_aip_base_get_demo_html( 'home' );
    return $html ? ay_aip_base_wrap_html_block( $html ) : '';
}

function ay_aip_base_about_content() {
    $html = ay_aip_base_get_demo_html( 'about' );
    return $html ? ay_aip_base_wrap_html_block( $html ) : '';
}

function ay_aip_base_news_content() {
    $html = ay_aip_base_get_demo_html( 'news' );
    return $html ? ay_aip_base_wrap_html_block( $html ) : '';
}

function ay_aip_base_contact_content() {
    $html = ay_aip_base_get_demo_html( 'contact' );
    return $html ? ay_aip_base_wrap_html_block( $html ) : '';
}

function ay_aip_base_terms_content() {
    $html = ay_aip_base_get_demo_html( 'terms' );
    return $html ? ay_aip_base_wrap_html_block( $html ) : '';
}

function ay_aip_base_privacy_content() {
    $html = ay_aip_base_get_demo_html( 'privacy' );
    return $html ? ay_aip_base_wrap_html_block( $html ) : '';
}

function ay_aip_base_blocks_content() {
    return <<<HTML
<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">Blocks Library</h1>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>This page collects sample sections so editors can copy/paste components into new layouts.</p>
<!-- /wp:paragraph -->

<!-- wp:columns {"align":"wide"} -->
<div class="wp-block-columns alignwide"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Card Example</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Swap this with ACF Card Grid block once content is ready.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
HTML;
}

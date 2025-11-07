<?php
/**
 * Register ACF blocks.
 */

add_filter( 'block_categories_all', function ( $categories ) {
    $categories[] = [
        'slug'  => 'ay-aip-base',
        'title' => __( 'AY Base Blocks', 'ay-aip-base' ),
    ];
    return $categories;
} );

add_action( 'acf/init', 'ay_aip_base_register_acf_blocks' );
function ay_aip_base_register_acf_blocks() {
    if ( ! function_exists( 'acf_register_block_type' ) ) {
        return;
    }

    $blocks = [
        'hero'        => __( 'Hero', 'ay-aip-base' ),
        'card-grid'   => __( 'Card Grid', 'ay-aip-base' ),
        'stats'       => __( 'Stats', 'ay-aip-base' ),
        'cta-banner'  => __( 'CTA Banner', 'ay-aip-base' ),
        'contact-form'=> __( 'Contact Form', 'ay-aip-base' ),
        'team-grid'   => __( 'Team Grid', 'ay-aip-base' ),
    ];

    foreach ( $blocks as $slug => $title ) {
        acf_register_block_type( [
            'name'            => $slug,
            'title'           => $title,
            'description'     => sprintf( __( '%s block', 'ay-aip-base' ), $title ),
            'render_callback' => 'ay_aip_base_render_block',
            'category'        => 'ay-aip-base',
            'icon'            => 'layout',
            'supports'        => [
                'align' => [ 'wide', 'full' ],
            ],
            'keywords'        => [ 'ay', $slug ],
        ] );
    }
}

function ay_aip_base_render_block( $block, $content = '', $is_preview = false, $post_id = 0 ) {
    $slug = str_replace( 'acf/', '', $block['name'] );
    $template = "template-parts/blocks/{$slug}";
    if ( locate_template( $template . '.php' ) ) {
        set_query_var( 'ay_block', $block );
        set_query_var( 'ay_is_preview', $is_preview );
        get_template_part( $template );
        set_query_var( 'ay_block', null );
        set_query_var( 'ay_is_preview', null );
    }
}

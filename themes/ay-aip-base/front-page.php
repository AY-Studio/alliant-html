<?php
/**
 * Front page template.
 */

get_header();

$page_id          = get_queried_object_id();
$page_template    = get_page_template_slug( $page_id );
$uses_builder_tpl = ( 'templates/template-pagebuilder.php' === $page_template );
?>
<div class="builder-wrap">
<?php
if ( $uses_builder_tpl ) {
    if ( ! ay_aip_base_render_page_sections( $page_id ) ) {
        while ( have_posts() ) :
            the_post();
            the_content();
        endwhile;
    }
} else {
    while ( have_posts() ) :
        the_post();
        the_content();
    endwhile;
}
?>
</div>
<?php
get_footer();

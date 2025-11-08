<?php
/**
 * Template Name: Page Builder
 * Description: Blank canvas template with only header and footer so editors can compose layouts with blocks or third-party builders.
 */

global $post;

get_header();

?>
<div class="builder-wrap">
<?php
if ( ! ay_aip_base_render_page_sections() ) :
    while ( have_posts() ) :
        the_post();
        the_content();
    endwhile;
endif;
?>
</div>
<?php
get_footer();

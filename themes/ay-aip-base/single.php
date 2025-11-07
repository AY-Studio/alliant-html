<?php
/**
 * Single post template.
 */

get_header();
?>
<div class="container">
<?php
while ( have_posts() ) :
    the_post();
    get_template_part( 'template-parts/content/content', get_post_type() );
    the_post_navigation();
endwhile;
?>
</div>
<?php
get_footer();

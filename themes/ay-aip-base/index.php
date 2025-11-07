<?php
/**
 * Main template file.
 */

get_header();
?>
<div class="container">
<?php if ( have_posts() ) : ?>
    <?php while ( have_posts() ) : the_post(); ?>
        <?php get_template_part( 'template-parts/content/content', get_post_type() ); ?>
    <?php endwhile; ?>
    <?php the_posts_navigation(); ?>
<?php else : ?>
    <?php get_template_part( 'template-parts/content/content', 'none' ); ?>
<?php endif; ?>
</div>
<?php
get_footer();

<?php
/**
 * Search results template.
 */

get_header();
?>
<div class="container">
    <header>
        <h1><?php printf( esc_html__( 'Search Results for: %s', 'ay-aip-base' ), '<span>' . esc_html( get_search_query() ) . '</span>' ); ?></h1>
    </header>
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

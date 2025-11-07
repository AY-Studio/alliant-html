<?php
/**
 * Single post template.
 */

get_header();
?>
<article class="article-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="mb-4">
                    <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="back-link">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2">
                            <path d="M19 12H5M5 12L12 19M5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <?php esc_html_e( 'BACK TO NEWS & INSIGHTS', 'ay-aip-base' ); ?>
                    </a>
                </div>

                <h1 class="article-title mb-4"><?php the_title(); ?></h1>

                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="article-image mb-4">
                        <?php the_post_thumbnail( 'ay_aip_base_hero', [ 'class' => 'img-fluid' ] ); ?>
                    </div>
                <?php endif; ?>

                <div class="article-meta mb-4">
                    <p class="text-muted mb-0"><?php echo esc_html( get_the_date( 'F j, Y' ) ); ?></p>
                </div>

                <div class="article-content">
                    <?php
                    the_content();
                    ?>
                </div>

                <div class="article-share mt-5 pt-4 border-top">
                    <p class="fw-bold mb-3"><?php esc_html_e( 'SHARE:', 'ay-aip-base' ); ?></p>
                    <div class="d-flex gap-2">
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode( get_permalink() ); ?>" class="btn-social" aria-label="Share on LinkedIn" target="_blank" rel="noopener noreferrer">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_permalink() ); ?>" class="btn-social" aria-label="Share on Facebook" target="_blank" rel="noopener noreferrer">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode( get_permalink() ); ?>" class="btn-social" aria-label="Share on Twitter" target="_blank" rel="noopener noreferrer">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                        </a>
                        <a href="mailto:?subject=<?php echo rawurlencode( get_the_title() ); ?>&body=<?php echo urlencode( get_permalink() ); ?>" class="btn-social" aria-label="Share via Email">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</article>
<?php
get_footer();

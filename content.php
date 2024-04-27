<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package draftly
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class('posts-entry fbox blogposts-list'); ?>>
    <?php
    // Insert the view count at the top of the post.
    if (function_exists('techviews_display_views')) {
        echo '<div class="post-views-count">' . techviews_display_views() . '</div>';
    }
    ?>
    <?php if ( has_post_thumbnail() ) : ?>
        <div class="featured-img-box">
            <a href="<?php the_permalink(); ?>" class="featured-thumbnail" rel="bookmark">
                <div class="featured-thumbnail-inner" style="background-image:url(<?php the_post_thumbnail_url('full'); ?>);">
                </div>
            </a>
        <?php else : ?>
            <div class="no-featured-img-box">
        <?php endif; ?>
        <div class="content-wrapper">
            <div class="entry-meta">
                <div class="post-data-text">
                    <?php draftly_posted_on(); ?>
                </div>
            </div><!-- .entry-meta -->
            <header class="entry-header">
                <?php
                if ( is_singular() ) :
                    the_title( '<h1 class="entry-title">', '</h1>' );
                else :
                    the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
                endif;
                ?>
            </header><!-- .entry-header -->
            <div class="entry-content">
                <!-- Post content -->
            </div><!-- .entry-content -->
        </div><!-- .content-wrapper -->
    </div><!-- .(no-)featured-img-box -->
</article><!-- #post-<?php the_ID(); ?> -->

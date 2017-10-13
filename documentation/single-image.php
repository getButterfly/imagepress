<?php get_header(); ?>

<section id="content" role="main" class="ip-main">
    <?php if(have_posts()) : while(have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php
            /* BEGIN IMAGEPRESS CODE */
            /* main image display (required) */
			if(function_exists('ip_main'))
				ip_main(get_the_ID());

            /* related images (optional, can be placed in the sidebar) */
			if(function_exists('ip_related'))
                ip_related(get_the_ID());
            /* END IMAGEPRESS CODE */
            ?>
        </article>

        <?php comments_template('', true); ?>
    <?php endwhile; endif; ?>
</section>

<?php get_footer(); ?>

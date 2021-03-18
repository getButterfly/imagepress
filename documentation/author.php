<?php get_header(); ?>

<section id="content-wide" role="main">
	<article <?php post_class(); ?>>
		<section class="entry-content">
			<?php
			/* BEGIN IMAGEPRESS AUTHOR CODE */
			if (function_exists('imagepress_author')) {
				imagepress_author();
			}
			/* END IMAGEPRESS AUTHOR CODE */
			?>
		</section>
	</article>
</section>

<?php get_footer();

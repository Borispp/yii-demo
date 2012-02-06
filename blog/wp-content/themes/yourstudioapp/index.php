<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<article <?php post_class()?> id="post-<?php the_ID()?>">
			<h2><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title();?></a></h2>
			<div class="meta cf">
				<span class="cat">Posted in <?php the_category(', ');?></span>
				on
				<time><?php the_date()?></time>

				<a href="<?php comments_link(); ?>" class="comments"><?php comments_number('No Comments','One Comment','% Comments'); ?></a>
			</div>
			<?php if (has_post_thumbnail()) : ?>
				<figure class="featured">
					<a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_post_thumbnail(array(200,200))?></a>
				</figure>
			<?php endif; ?>
			<div class="story cf">
				<?php the_content('Read More'); ?>
			</div>
	</article>
	<?php endwhile; else: ?>
		<?php get_template_part('_notfound', 'index')?>
	<?php endif; ?>
	<nav class="cf">
		<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'twentyeleven' ) ); ?></div>
		<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'twentyeleven' ) ); ?></div>
	</nav>
<?php get_footer(); ?>
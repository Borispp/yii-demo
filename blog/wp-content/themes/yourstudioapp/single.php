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
			
			<div class="pages"><?php wp_link_pages() ?></div>
			
			<div class="share cf">
				<div class="tweet">
					<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php the_permalink()?>">Tweet</a>
				</div>
				
				<div class="fb-like" data-href="<?php the_permalink()?>" data-send="false" data-layout="button_count" data-width="100" data-show-faces="false"></div>
				
				<div class="gplus">
					<g:plusone size="medium"></g:plusone>
				</div>
			</div>
			
			<?php comments_template();?>
	</article>
	<?php endwhile; else: ?>
		<?php get_template_part('_notfound', 'index')?>
	<?php endif; ?>

	<nav class="cf">
		<span class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">&larr;</span> Previous Post'); ?></span>
		<span class="nav-next"><?php next_post_link( '%link', 'Next Post <span class="meta-nav">&rarr;</span>'); ?></span>
	</nav>

<?php get_footer(); ?>
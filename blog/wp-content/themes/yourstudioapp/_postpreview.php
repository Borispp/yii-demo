<div class="preview-wrapper">
    <div class="preview wrapper <?php echo has_post_thumbnail() || has_post_format('gallery') ? '' : 'no-image'?>">
        <div class="content">
            <h4><?php flotheme_first_category()?></h4>
            <h2><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title();?></a></h2>
            <span class="meta">
                <span class="date"><?php the_time('m.d.Y')?></span>
                &middot;
                <a href="<?php comments_link(); ?>" class="comments"><?php comments_number('no comments','one comment','% comments'); ?></a>
            </span>
            <div class="excerpt">
                <?php the_excerpt();?>
            </div>
            
            <a href="<?php the_permalink();?>" class="toggle">Open post</a>
            <span class="loading"></span>
        </div>
        <?php if (has_post_format('gallery')) : ?>
            <div class="gallery">
                <div class="wrap">
                    <ul>
                        <?php if (has_post_thumbnail ()) : ?>
                            <li><?php the_post_thumbnail(array(600,400))?></li>
                        <?php endif; ?>
                        <?php foreach(flotheme_get_all_images() as $image):?>
                            <li><?php echo $image?></li>
                        <?php endforeach;?>
                    </ul>
                </div>
            </div>
        <?php elseif (has_post_thumbnail()):?>
            <figure>
                <a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_post_thumbnail(array(600,400))?></a>
            </figure>
        <?php endif; ?>
    </div>
</div>
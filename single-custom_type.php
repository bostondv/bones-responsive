<?php
/*
This is the custom post type post template.
If you edit the post type name, you've got
to change the name of this template to
reflect that name change.

i.e. if your custom post type is called
register_post_type( 'bookmarks',
then your single template should be
single-bookmarks.php

*/
?>

<?php get_header(); ?>
			
			<div id="content">
			
				<div id="inner-content" class="wrap clearfix">
			
					<div id="main" class="eightcol clearfix" role="main">
					
						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
						
						<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">
							
							<header>
								
								<h1 class="single-title"><?php the_title(); ?></h1>
								
								<p class="meta"><?php _e("Posted", "bonestheme"); ?> <time datetime="<?php echo the_time('Y-m-j'); ?>" pubdate><?php the_time('F jS, Y'); ?></time> <?php _e("by", "bonestheme"); ?> <?php the_author_posts_link(); ?> <span class="amp">&</span> <?php _e("filed under", "bonestheme"); ?> <?php echo get_the_term_list( get_the_ID(), 'custom_cat', "" ) ?>.</p>
							
							</header> <!-- end article header -->
						
							<section class="post-content clearfix">
								
								<?php the_content(); ?>
						
							</section> <!-- end article section -->
							
							<footer>
					
								<p class="tags"><?php echo get_the_term_list( get_the_ID(), 'custom_tag', '<span class="tags-title">Custom Tags:</span> ', ', ' ) ?></p>
								
							</footer> <!-- end article footer -->
						
						</article> <!-- end article -->
						
						<?php comments_template(); ?>

						<?php get_template_part( 'section', 'pagination' ); ?>		
						
						<?php endwhile; ?>
						
						<?php else : ?>
						
							<?php get_template_part( 'loop', '404' ); ?>
						
						<?php endif; ?>
					
					</div> <!-- end #main -->
    				
					<?php get_sidebar(); // sidebar 1 ?>
				
				</div> <!-- end #inner-content -->
    
			</div> <!-- end #content -->

<?php get_footer(); ?>
<?php get_header(); ?>
			
			<div id="content">
			
				<div id="inner-content" class="wrap clearfix">
			
					<div id="main" class="eightcol clearfix" role="main">
					
						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
						
						<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">
							
							<header>
								
								<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
								
								<p class="meta"><?php _e("Posted", "bonestheme"); ?> <time datetime="<?php echo the_time('Y-m-j'); ?>" pubdate><?php the_time('F jS, Y'); ?></time> <?php _e("by", "bonestheme"); ?> <?php the_author_posts_link(); ?> <span class="amp">&</span> <?php _e("filed under", "bonestheme"); ?> <?php the_category(', '); ?>.</p>
							
							</header> <!-- end article header -->
						
							<section class="post-content clearfix">
								<?php the_content(__('<span class="read-more">Read more &rarr;</span>', "bonestheme")); ?>
						
							</section> <!-- end article section -->
							
							<footer>
					
								<p class="tags"><?php the_tags('<span class="tags-title">Tags:</span> ', ', ', ''); ?></p>
								
							</footer> <!-- end article footer -->
						
						</article> <!-- end article -->
						
						<?php comments_template(); ?>
						
						<?php endwhile; ?>	
						
						<?php get_template_part( 'section', 'pagination' ); ?>	
						
						<?php else : ?>
						
							<?php get_template_part( 'loop', '404' ); ?>
						
						<?php endif; ?>
					
					</div> <!-- end #main -->
    				
					<?php get_sidebar(); // sidebar 1 ?>
					
				</div> <!-- end #inner-content -->
    
			</div> <!-- end #content -->

<?php get_footer(); ?>
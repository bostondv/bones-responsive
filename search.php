<?php get_header(); ?>
			
			<div id="content">
			
				<div id="inner-content" class="wrap clearfix">
			
					<div id="main" class="ninecol clearfix" role="main">
					
						<h1><span>Search Results for:</span> <?php echo esc_attr(get_search_query()); ?></h1>
					
						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
						
						<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">
							
							<header>
								
								<h3><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
								
								<p class="meta"><?php _e("Posted", "bonestheme"); ?> <time datetime="<?php echo the_time('Y-m-j'); ?>" pubdate><?php the_time('F jS, Y'); ?></time> <?php _e("by", "bonestheme"); ?> <?php the_author_posts_link(); ?> <span class="amp">&</span> <?php _e("filed under", "bonestheme"); ?> <?php the_category(', '); ?>.</p>
							
							</header> <!-- end article header -->
						
							<section class="post-content">
								
								<?php the_excerpt('<span class="read-more">Read more on "'.the_title('', '', false).'" &raquo;</span>'); ?>
						
							</section> <!-- end article section -->
						
						</article> <!-- end article -->
						
						<?php endwhile; ?>	
						
						<?php get_template_part( 'section', 'pagination' ); ?>			
						
						<?php else : ?>
						
							<?php get_template_part( 'loop', '404' ); ?>
						
						<?php endif; ?>
					
					</div> <!-- end #main -->
    				
    				<div id="sidebar1" class="sidebar twocol last">
    					
    					<?php get_search_form(); ?>
    				
    				</div>
    				
    			</div> <!-- end #inner-content -->
    
			</div> <!-- end #content -->

<?php get_footer(); ?>
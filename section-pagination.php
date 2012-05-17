<?php if ( has_previous_posts() || has_next_posts() || has_previous_post() || has_next_post() ) : ?>

	<nav class="pagination">

		<?php if( is_single() ) : ?>

			<?php if(has_previous_post()) : ?>
				<span class="prev"><?php previous_post_link('%link', '&larr; Back'); ?></span>
			<?php endif; ?>
			<?php if(has_next_post()) : ?>
				<span class="next"><?php next_post_link('%link', 'Next &rarr;'); ?></span>
			<?php endif; ?>

		<?php else : ?>

			<?php previous_posts_link('&larr; Back'); ?>
			<?php next_posts_link('Next &rarr;'); ?>

		<?php endif; ?>

	</nav>

<?php endif; ?>
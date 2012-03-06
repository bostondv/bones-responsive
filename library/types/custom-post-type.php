<?php

/**
 * Register post type
 */
function bones_register_person_post_type() {
	register_post_type( 'person', array(
		'label' => 'People',
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'supports' => array( 'title', 'editor', 'thumbnail', 'revisions' ),
		'menu_icon' => get_stylesheet_directory_uri() . '/library/images/custom-post-icon.png',
		'menu_position' => 8,
	));
}
add_action('init','bones_register_person_post_type');


/**
 * Register group taxonomy
 */
function bones_register_group_taxonomy() {
	register_taxonomy( 'person_group', array('person'), array(
		'label' => 'Groups',
		'public' => true,
		'hierarchical' => true,
		'show_ui' => true,
		'query_var' => true
	));
}
add_action('init','bones_register_group_taxonomy');

/**
 * Register role taxonomy
 */
function bones_register_role_taxonomy() {
	register_taxonomy( 'person_role', array('person'), array(
		'label' => 'Roles',
		'public' => true,
		'hierarchical' => false,
		'show_ui' => true,
		'query_var' => true
	));
}
add_action('init','bones_register_role_taxonomy');

/**
 * Setup custom columns for person post type
 */
function bones_person_columns( $columns ) {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'image' => 'Image',
		'title' => 'Title',
		'groups' => 'Groups',
		'roles' => 'Roles',
		'date' => 'Date'
	);
	return $columns;
}
add_filter('manage_person_posts_columns', 'bones_person_columns');

/**
 * Create content for custom columns
 */
function bones_person_columns_content( $column, $post_id ) {
	global $typenow;
	if ($typenow == 'person') {
		switch ($column) {
			case 'groups':
				$taxonomy = 'person_group';
				$post_type = get_post_type($post_id);
				$terms = get_the_terms($post_id, $taxonomy);
				if ( !empty($terms) ) {
					foreach ( $terms as $term )
						$post_terms[] = "<a href='edit.php?post_type={$post_type}&{$taxonomy}={$term->slug}'> " . esc_html(sanitize_term_field('name', $term->name, $term->term_id, $taxonomy, 'edit')) . "</a>";
					echo join( ', ', $post_terms );
				} else {
					echo '<em>' . __('No intervals.') . '</em>';
				} 
				break;
			case 'roles':
				$taxonomy = 'person_role';
				$post_type = get_post_type($post_id);
				$terms = get_the_terms($post_id, $taxonomy);
				if ( !empty($terms) ) {
					foreach ( $terms as $term )
						$post_terms[] = "<a href='edit.php?post_type={$post_type}&{$taxonomy}={$term->slug}'> " . esc_html(sanitize_term_field('name', $term->name, $term->term_id, $taxonomy, 'edit')) . "</a>";
					echo join( ', ', $post_terms );
				} else {
					echo '<em>' . __('No intervals.') . '</em>';
				} 
				break;
			case 'image':
				add_image_size('admin-thumb', 75, 75, true);
				the_post_thumbnail('admin-thumb');
				break;
		}
	}
}
add_action('manage_posts_custom_column', 'bones_person_columns_content', 10, 2);

/**
 * Set columns as sortable
 */
function bones_person_sortable_columns() {
	return array(
		'title' => 'title',
		'date' => 'date'
	);
}
add_filter('manage_edit-person_sortable_columns', 'bones_person_sortable_columns');
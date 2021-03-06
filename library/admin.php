<?php
/* 
This file handles the admin area and functions.
You can use this file to make changes to the
dashboard. Updates to this page are coming soon.
It's turned off by default, but you can call it
via the functions file.

Developed by: Eddie Machado
URL: http://themble.com/bones/

Special Thanks for code & inspiration to:
@jackmcconnell - http://www.voltronik.co.uk/
Digging into WP - http://digwp.com/2010/10/customize-wordpress-dashboard/
*/

/************* DASHBOARD WIDGETS *****************/

// disable default dashboard widgets
function disable_default_dashboard_widgets() {
	// remove_meta_box('dashboard_right_now', 'dashboard', 'core');    // Right Now Widget
	remove_meta_box('dashboard_recent_comments', 'dashboard', 'core'); // Comments Widget
	remove_meta_box('dashboard_incoming_links', 'dashboard', 'core');  // Incoming Links Widget
	remove_meta_box('dashboard_plugins', 'dashboard', 'core');         // Plugins Widget

	// remove_meta_box('dashboard_quick_press', 'dashboard', 'core');  // Quick Press Widget
	remove_meta_box('dashboard_recent_drafts', 'dashboard', 'core');   // Recent Drafts Widget
	remove_meta_box('dashboard_primary', 'dashboard', 'core');         // Wordpress Blog Feed
	remove_meta_box('dashboard_secondary', 'dashboard', 'core');       // Other Wordpress News
	
	// removing plugin dashboard boxes 
	remove_meta_box('yoast_db_widget', 'dashboard', 'normal');         // Yoast's SEO Plugin Widget
	remove_meta_box('rg_forms_dashboard', 'dashboard', 'norma;');      // Gravity Forms Widget
	
	/* 
	have more plugin widgets you'd like to remove? 
	share them with us so we can get a list of 
	the most commonly used. :D
	https://github.com/eddiemachado/bones/issues
	*/
}
add_action('admin_menu', 'disable_default_dashboard_widgets');


/************* CUSTOM LOGIN PAGE *****************/

// calling your own login css so you can style it 
function bones_login_css() {
	/* i couldn't get wp_enqueue_style to work :( */
	echo '<link rel="stylesheet" href="' . get_stylesheet_directory_uri() . '/library/css/login.css">';
}

// changing the logo link from wordpress.org to your site 
function bones_login_url() { echo bloginfo('url'); }

// changing the alt text on the logo to show your site name 
function bones_login_title() { echo get_option('blogname'); }

// calling it only on the login page
add_action('login_head', 'bones_login_css');
add_filter('login_headerurl', 'bones_login_url');
add_filter('login_headertitle', 'bones_login_title');


/************* CUSTOMIZE ADMIN *******************/

/*
I don't really reccomend editing the admin too much
as things may get funky if Wordpress updates. Here
are a few funtions which you can choose to use if 
you like.
*/

// Custom Backend Footer
function bones_custom_admin_footer() {
	echo '<span id="footer-thankyou">Developed by <a href="http://pomelodesign.com" target="_blank">Pomelo Design</a></span>. Built using <a href="http://themble.com/bones" target="_blank">Bones</a>.';
}

// adding it to the admin area
add_filter('admin_footer_text', 'bones_custom_admin_footer');

/************* CUSTOM ADMIN CSS *******************/

if (is_admin()) wp_enqueue_style('custom-admin', get_stylesheet_directory_uri() . '/library/css/admin.css');

/************* MANAGE POSTS *******************/

/**
 * Filter the request to just give posts for the given taxonomy, if applicable.
 */
function bones_taxonomy_filter() {
	global $typenow;

	// If you only want this to work for your specific post type,
	// check for that $type here and then return.
	// This function, if unmodified, will add the dropdown for each
	// post type / taxonomy combination.

	$post_types = get_post_types(array('_builtin' => false ));

	if (in_array($typenow, $post_types)) {
		$filters = get_object_taxonomies($typenow);

		foreach ($filters as $tax_slug) {
			$tax_obj = get_taxonomy($tax_slug);
			wp_dropdown_categories( 
				array(
					'show_option_all' => __('Show All '.$tax_obj->label, 'skeleton'),
					'taxonomy' 	  => $tax_slug,
					'name' 		  => $tax_obj->name,
					'orderby' 	  => 'name',
					'selected' 	  => $_GET[$tax_slug],
					'hierarchical' 	  => $tax_obj->hierarchical,
					'show_count' 	  => false,
					'hide_empty' 	  => true
				) 
			);
		}
	}
}
add_action('restrict_manage_posts','bones_taxonomy_filter');

/**
 * Add a filter to the query for the dropdowns
 */
function bones_taxonomy_filter_request($query) {
	global $pagenow, $typenow;

	if ('edit.php' == $pagenow) {
		$filters = get_object_taxonomies( $typenow );
		foreach ( $filters as $tax_slug ) {
			$var = &$query->query_vars[$tax_slug];
			if ( isset( $var ) ) {
				$term = get_term_by( 'id', $var, $tax_slug );
				$var = $term->slug;
			}
		}
	}
}
add_filter('parse_query', 'bones_taxonomy_filter_request');

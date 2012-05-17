<?php
/*
Bones Plugins & Extra Functionality
Author: Eddie Machado
URL: http://themble.com/bones/

This file contains extra features not 100% ready to be included
in the core. Feel free to edit anything here or even help us fix
and optimize the code! 

IF YOU WANT TO SUBMIT A FIX OR CORRECTION, JOIN US ON GITHUB:
https://github.com/eddiemachado/bones/issues

IF YOU WANT TO DISABLE THIS FILE, REMOVE IT'S CALL IN THE FUNCTIONS.PHP FILE
*/


/* 
Social Integration
This is a collection of snippets I edited or reused from
social plugins. No need to use a plugin when you can 
replicate it in only a few lines I say, so here we go.
For more info, or to add more open graph stuff, check
out: http://yoast.com/facebook-open-graph-protocol/
*/

// get the image for the google + and facebook integration 
function bones_get_socialimage() {
  global $post, $posts;

  $src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), '', '' );

  if ( has_post_thumbnail($post->ID) ) {
    $socialimg = $src[0];
  } else {
    $socialimg = '';
    $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post-content, $matches);
    if (array_key_exists(1, $matches))
      if (array_key_exists(0, $matches[1]))
        $socialimg = $matches [1] [0];
  }

  if(empty($socialimg))
    $socialimg = get_template_directory_uri() . '/library/images/nothumb.gif';

  return $socialimg;
}

// facebook share correct image fix (thanks to yoast)
function bones_facebook_connect() {
	echo "\n" . '<!-- facebook open graph stuff -->' . "\n";
	echo '<!-- place your facebook app id below -->';
	echo '<meta property="fb:app_id" content="1234567890"/>' . "\n";
	global $post;	
	echo '<meta property="og:site_name" content="'. get_bloginfo("name") .'"/>' . "\n";
	echo '<meta property="og:url" content="'. get_permalink() .'"/>' . "\n";
	echo '<meta property="og:title" content="'.get_the_title().'" />' . "\n";
	if (is_singular()) {
		echo '<meta property="og:type" content="article"/>' . "\n";
		echo '<meta property="og:description" content="' .strip_tags( get_the_excerpt() ).'" />' . "\n";
	}
	echo '<meta property="og:image" content="'. bones_get_socialimage() .'"/>' . "\n";
	echo '<!-- end facebook open graph -->' . "\n";
}

// google +1 meta info
function bones_google_header() {
	if (is_singular()) {
		echo '<!-- google +1 tags -->' . "\n";
		global $post;
		echo '<meta itemprop="name" content="'.get_the_title().'">' . "\n";
		echo '<meta itemprop="description" content="' .strip_tags( get_the_excerpt() ).'">' . "\n";
		echo '<meta itemprop="image" content="'. bones_get_socialimage() .'">' . "\n";
		echo '<!-- end google +1 tags -->' . "\n";
	}
}
	
	// add this in the header 
	add_action('wp_head', 'bones_facebook_connect');
	add_action('wp_head', 'bones_google_header');

	
// adding the rel=me thanks to yoast	
function yoast_allow_rel() {
	global $allowedtags;
	$allowedtags['a']['rel'] = array ();
}
add_action( 'wp_loaded', 'yoast_allow_rel' );

// adding facebook, twitter, & google+ links to the user profile
function bones_add_user_fields( $contactmethods ) {
	// Add Facebook
	$contactmethods['user_fb'] = 'Facebook';
	// Add Twitter
	$contactmethods['user_tw'] = 'Twitter';
	// Add Google+
	$contactmethods['google_profile'] = 'Google Profile URL';
	// Save 'Em
	return $contactmethods;
}
add_filter('user_contactmethods','bones_add_user_fields',10,1);

// Convert to plural
function plural($num) {
	if ($num != 1)
		return "s";
}

// Relative time function
function the_relative_time($date) {
	$diff = time() - strtotime($date);
	if ($diff<60)
		return $diff . " second" . plural($diff) . " ago";
	$diff = round($diff/60);
	if ($diff<60)
		return $diff . " minute" . plural($diff) . " ago";
	$diff = round($diff/60);
	if ($diff<24)
		return $diff . " hour" . plural($diff) . " ago";
	$diff = round($diff/24);
	if ($diff<7)
		return $diff . " day" . plural($diff) . " ago";
	$diff = round($diff/7);
	if ($diff<4)
		return $diff . " week" . plural($diff) . " ago";
	return "on " . date("F j, Y", strtotime($date));
}

// Fetch tweets
function fetch_tweets($username, $maxtweets) {
	 //Using simplexml to load URL
	 $tweets = simplexml_load_file("http://twitter.com/statuses/user_timeline/" . $username . ".rss");

	 $tweet_array = array();  //Initialize empty array to store tweets
	 foreach ( $tweets->channel->item as $tweet ) { 
		  //Loop to limitate nr of tweets.
		  if ($maxtweets == 0) {
			   break;
		  } else {
			   $twit = $tweet->description;  //Fetch the tweet itself

			   //Remove the preceding 'username: '
			   $twit = substr(strstr($twit, ': '), 2, strlen($twit));

			   // Convert URLs into hyperlinks
			   $twit = preg_replace("/(http:\/\/)(.*?)\/([\w\.\/\&\=\?\-\,\:\;\#\_\~\%\+]*)/", "<a href=\"\\0\">\\0</a>", $twit);

			   // Convert usernames (@) into links 
			   $twit = preg_replace("(@([a-zA-Z0-9\_]+))", "<a href=\"http://www.twitter.com/\\1\">\\0</a>", $twit);

			   // Convert hash tags (#) to links 
			   $twit = preg_replace('/(^|\s)#(\w+)/', '\1<a href="http://search.twitter.com/search?q=%23\2">#\2</a>', $twit);

			   //Specifically for non-English tweets, converts UTF-8 into ISO-8859-1
			   $twit = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $twit);

			   //Get the date it was posted
			   $pubdate = strtotime($tweet->pubDate); 
			   $propertime = gmdate('F jS Y, H:i', $pubdate);  //Customize this to your liking

			   //Store tweet and time into the array
			   $tweet_item = array(
					 'desc' => $twit,
					 'date' => $propertime,
			   );
			   array_push($tweet_array, $tweet_item);

			   $maxtweets--;
		  }
	 }
	 //Return array
	 return $tweet_array;
}

// Fetch facebook feed
function fetch_fb_feed($url, $maxnumber) {
	 /* The following line is absolutely necessary to read Facebook feeds. Facebook will not recognize PHP as a browser and therefore won't fetch anything. So we define a browser here */
	 ini_set('user_agent', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.5; en-US; rv:1.9.1.3) Gecko/20090824 Firefox/3.5.3');

	 $updates = simplexml_load_file($url);  //Load feed with simplexml

	 $fb_array = array();  //Initialize empty array to store statuses
	 foreach ( $updates->channel->item as $fb_update ) {
		  if ($maxnumber == 0) {
			   break;
		  } else {
			   $desc = $fb_update->description;

			   //Add www.facebook.com to hyperlinks
			   $desc = str_replace('href="', 'href="http://www.facebook.com', $desc); 

				//Converts UTF-8 into ISO-8859-1 to solve special symbols issues
			   $desc = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $desc);

				//Get status update time
			   $pubdate = strtotime($fb_update->pubDate);
			   $propertime = gmdate('F jS Y, H:i', $pubdate);  //Customize this to your liking

			   //Get link to update
			   $linkback = $fb_update->link;

			   //Store values in array
			   $fb_item = array(
					  'desc' => $desc,
					  'date' => $propertime,
					  'link' => $linkback
			   );
			   array_push($fb_array, $fb_item);

			   $maxnumber--;
		  }          
	 }
	 //Return array
	 return $fb_array;
}

// Fetch youtube videos
function fetch_youtube_videos( $username, $maxnumber ) {
	
	$counter = 0;

	// Data feed in json format
	$data = @json_decode(file_get_contents('http://gdata.youtube.com/feeds/api/users/'.$username.'/uploads?alt=json'), TRUE);

	$video_array = array();

	foreach($data['feed']['entry'] as $vid) {
		if ($maxnumber == 0) {
			break;
		} else {
			//Get the video title
			$title = $vid['title']['$t'];
			
			//Get the video description
			$desc = $vid['content']['$t'];

			//Get the video thumbnail source url
			$image = $vid['media$group']['media$thumbnail'][0]['url'];

			//Get the video time
			$pubdate = strtotime($vid['updated']['$t']);
			$propertime = gmdate('F jS Y, H:i', $pubdate);

			//Get the video url
			$url = $vid['media$group']['media$content'][0]['url'];

			//Store values in array
			$video_item = array(
				'title' => $title,
				'desc' => $desc,
				'image' => $image,
				'date' => $propertime,
				'url' => $url
			);
			array_push($video_array, $video_item);

			$maxnumber--;
		}

	}

	return $video_array;

}

// Get dropdown of taxonomy terms with slug values
function get_terms_dropdown($taxonomy, $args){
	$terms = get_terms($taxonomy, $args);
	$tax = get_taxonomy($taxonomy);
	// TODO Post type link not working on taxonomy
	$post_type = get_query_var('post_type');
	$post_type_link = get_post_type_archive_link( $post_type );
	$output = "<select name=\"$taxonomy\" class=\"$taxonomy\">";
	$output .= "<option value=\"$post_type_link\">".__('Select')." $tax->label</option>";
	$current_term = get_query_var($taxonomy);
	foreach($terms as $term){
		if ( $current_term == $term->slug ) {
			$selected = "selected=\"selected\"";
		}
		$term_link = get_term_link($term->name, $taxonomy);
		$output .= "<option value=\"$term_link\" $selected>$term->name</option>";
		unset($selected);
	}
	$output .= "</select>";
return $output;
}

//functions tell whether there are previous or next 'pages' from the current page
//returns 0 if no 'page' exists, returns a number > 0 if 'page' does exist
//ob_ functions are used to suppress the previous_posts_link() and next_posts_link() from printing their output to the screen

function has_previous_posts() {
	ob_start();
	previous_posts_link();
	$result = strlen(ob_get_contents());
	ob_end_clean();
	return $result;
}

function has_next_posts() {
	ob_start();
	next_posts_link();
	$result = strlen(ob_get_contents());
	ob_end_clean();
	return $result;
}

function has_previous_post() {
	ob_start();
	previous_post_link();
	$result = strlen(ob_get_contents());
	ob_end_clean();
	return $result;
}

function has_next_post() {
	ob_start();
	next_post_link();
	$result = strlen(ob_get_contents());
	ob_end_clean();
	return $result;
}
<?php
function add_custom_api(){
register_rest_route('wp/v2', '/custom', array(
  'methods' => 'GET', 
  'callback' => 'query_posts_and_pages_with_params' 
) );
}
function query_posts_and_pages_with_params($request) {
  $arg = $request->get_param( 's' );
  return query_posts_and_pages($arg);
}
function query_posts_and_pages($arg){
  $args = array(
      'post_type' => array('post', 'page'),
      'showposts' => '100',
      's' => $arg,
      'order' => 'DESC',
      'orderby' => 'date',
  );
  return fetchByPostTypeAndTax($args);
}

function fetchByPostTypeAndTax($args) {
    // Run a custom query
    $meta_query = new WP_Query($args);
    if($meta_query->have_posts()) {
        //Define an empty array
        $data = array();
        // Store each post's data in the array
        while($meta_query->have_posts()) {
            $meta_query->the_post();
            $id = get_the_ID();
            $post = get_post($id);
            $link = get_permalink($id);
            $featured_image = get_the_post_thumbnail_url($id);
            $post_object = (object) [
                'id' => $post->ID,
                'title' => (object) ['rendered' => $post->post_title],
                'date' => $post->post_date,
                'slug' => $post->post_name,
                'link' => $link,
                'featured_img_url' => $featured_image,
                'image' => get_the_post_thumbnail_url($post->ID),
                'excerpt' => (object) ['rendered' => get_the_excerpt()],
                'type' => get_post_type( $post->ID)
            ];
            $data[] = $post_object;
        }
        // Return the data
        return $data;
    } else {
        // If there is no post
        return 'No post to show';
    }
  }

  
  
  
  


add_action( 'rest_api_init', 'add_custom_api');
<?php
function add_custom_api(){
register_rest_route('wp/v2', '/posts', array(
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
  return get_posts($args);
}


add_action( 'rest_api_init', 'add_custom_api');
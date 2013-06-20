<?php
require_once('../../../wp-load.php');

$query = new WP_Query('post_type=proglang');
if ( $query->have_posts() ) {

  $posts_id = array();

  while ( $query->have_posts() ) {
    $query->the_post();

    array_push($posts_id, get_the_id());

  }
}

$url = array();
$counter = 0;

foreach ( $posts_id as $post_id ) {
  if ( $counter > 4 )
    continue;
  $image = get_post_meta($post_id, 'wp_custom_logo', true);
  if ( isset ( $image ) && $image ) {
    array_push($url, $image['url']);
    $counter++;
  }
}

echo json_encode($url);




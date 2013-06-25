<?php
  require_once ( '../../../../wp-load.php');
  global $wpdb;

  $url = $_POST['url'];

  $unlink_url = preg_replace('#.*\/dossier-cache/content#', '', $url);
  unlink( '../../' . $unlink_url );

  $result = $wpdb->query("DELETE FROM $wpdb->postmeta WHERE meta_value like '%$url%'");

  echo $result;

<?php
/*
Plugin Name: Programming Language CRUD - Widget
Plugin URI: http://plugin.geek.com
Description: CRUD of Programming Languages - Widget.
Version: 1.0
Author: Mohamed Benaida
Author URI: http://momz.fr/
License: GEEKNU
*/

add_action("widgets_init", function() {
  register_widget( "proglang" );
});

class Proglang extends WP_widget
{

  public function Proglang()
  {

    $options = array(
      "classname"   => "programming_languages_widget",
      "description" => "Collection object highlight"
    );

    $this->WP_widget("proglang-widget", "Programming languages Widget", $options);

  }

  public function form($instance)
  {

    $defaut = array(
      "name" => '',
      "id" => ''
    );

    $instance = wp_parse_args($instance, $defaut);

    global $wpdb;

    $proglangs = new WP_query("post_type=proglang");

    ?>

    <label for="<?php echo $this->get_field_id('first_name'); ?>">First Prog. lang. name</label>
      <p>
        <select name="<?php echo $this->get_field_name('first_name'); ?>" id="<?php echo $this->get_field_id('first_name'); ?>">

    <?php
    while ( $proglangs->have_posts() ) : $proglangs->the_post();
      global $post;

      $name = get_post_meta($post->ID, 'meta_box_name', true);
    ?>

      <option value="<?php echo $post->ID . '" ' . selected( $instance['first_name'], $post->ID ); ?>><?php echo $name ; ?></option>

    <?php

    endwhile;

    ?>

      </select>
    </p>
    <label for="<?php echo $this->get_field_id('second_name'); ?>">Second Prog. lang. name</label>
      <p>
        <select name="<?php echo $this->get_field_name('second_name'); ?>" id="<?php echo $this->get_field_id('second_name'); ?>">

    <?php
    while ( $proglangs->have_posts() ) : $proglangs->the_post();
      global $post;

      $name = get_post_meta($post->ID, 'meta_box_name', true);
    ?>

      <option value="<?php echo $post->ID . '" ' . selected( $instance['second_name'], $post->ID ); ?>><?php echo $name ; ?></option>

    <?php

    endwhile;

    ?>

      </select>
    </p>

    <?php

  } // end form function

  public function update( $new_instance, $old_instance )
  {

    $instance = $old_instance;

    $instance['first_name']   = esc_attr($new_instance['first_name']);
    $instance['second_name']   = esc_attr($new_instance['second_name']);

    return $instance;

  }

  // widget front end display
  public function widget($args, $instance)
  {

    extract($args);
    global $wpdb;

    // value get for each object
    $first_proglang = $wpdb->get_results( "select meta_value from wpLP_postmeta where meta_key in ('meta_box_name', 'meta_box_color') and post_id = {$instance['first_name']}" );
    $second_proglang = $wpdb->get_results( "select meta_value from wpLP_postmeta where meta_key in ('meta_box_name', 'meta_box_color') and post_id = {$instance['second_name']}" );

    echo $before_widget;

  ?>

  <div id='top-proglang'>
    <table class='table-collectionteam'>
      <thead>
        <tr>
          <th><strong>Highlight of programming languages</strong></th>
          <th></th>
        </tr>
      </thead>
      <tbody>

  <?php

      echo ("
            <tr>
              <td>{$first_proglang[0]->meta_value}</td>
              <td><span style='width:20px;height:20px;display:block;background-color:{$first_proglang[1]->meta_value};' title='{$first_proglang[1]->meta_value}'></span></td>
            </tr>
            <tr>
              <td>{$second_proglang[0]->meta_value}</td>
              <td><span style='width:20px;height:20px;display:block;background-color:{$second_proglang[1]->meta_value};' title='{$second_proglang[1]->meta_value}'></span></td>
            </tr>
           ");

  ?>

      </tbody>
    </table>
  </div>

  <?php

    echo $after_widget;

  } // end widget function

} // proglang class end

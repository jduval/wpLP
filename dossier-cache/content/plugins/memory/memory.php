<?php
/*
Plugin Name: Memory game
Plugin URI: http://memory.jerome-duval.com
Description: Memory is a card game in which all of the cards are laid face down on a surface and two cards are flipped face up over each turn. The object of the game is to turn over pairs of matching cards.
Version: 1.0
Author: Jérôme Duval
Author URI: http://jerome-duval.com/
License: GAMELULZ
*/

function memory_menu()
{

  //$page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position
  add_menu_page(
    'Memory',
    'Memory',
    'manage_options',
    'memory_configuration', // wp-admin/admin.php?page=$var
    'memory_back',
    '',
    3
  );

}
add_action( 'admin_menu', 'memory_menu' );

function memory_back()
{

  // checking if configuration option for memory is set
  $config = get_option('config_mem', 'none');

  if ( isset ( $_POST['config_mem'] ) && $_POST['config_mem'] ) {
    if ( ! ( isset ( $config ) && $config ) ) {
      add_option('config_mem', $_POST['config_mem']); // no option set
    } else {
      update_option('config_mem', $_POST['config_mem']);
    }
  }

  // get new configuration option (after update)
  $config = get_option('config_mem', 'none');

  ?>

  <div>
    <h4>Memory Configuration</h4>
    <form action="" method="post">
      <label>Enable to play Memory with...</label>
      <select name="config_mem" id="config_mem">
        <option>---</option>
        <option value="1">Number</option>
        <option value="2">Picture</option>
      </select>
      <input type="submit" name="submit_config_mem" id="submit_config_mem" value="Save" />
    </form>
  </div>
  <p>Current configuration : <?php if ( $config == '1' ) echo 'Number'; else if ( $config == '2' ) echo 'Picture'; else echo 'None'; ?></p>

  <?php

}

function memory_front()
{

  wp_register_style('memory-css', plugins_url() . '/memory/css/style.css');
  wp_enqueue_style('memory-css');

  wp_enqueue_script('memory', plugins_url() . '/memory/js/memory.js');

  $config = get_option('config_mem', 'none');

  ?>

  <div>
    <h4>Good luck!</h4>
    <table id="mem-table" data-value="<?php echo $config; ?>">
      <tbody>
        <tr>
          <td id='t1' class='td-mem'></td>
          <td id='t2' class='td-mem'></td>
          <td id='t3' class='td-mem'></td>
          <td id='t4' class='td-mem'></td>
        </tr>
        <tr>
          <td id='t5' class='td-mem'></td>
          <td id='t6' class='td-mem'></td>
          <td id='t7' class='td-mem'></td>
          <td id='t8' class='td-mem'></td>
        </tr>
      </tbody>
    </table>
  </div>

  <?php

}
// write [memory_game] to display the game
add_shortcode('memory_game', 'memory_front');

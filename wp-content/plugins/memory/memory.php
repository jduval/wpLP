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
    'memory_slug',
    'memory_back',
    '',
    3
  );

}
add_action( 'admin_menu', 'memory_menu' );

function memory_back()
{

  // post processing
  // set option

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

  <?php

}

function memory_front()
{

  //$options = get_option('option

  ?>

  <div>
    <h4>Good luck!</h4>
    <ul>
      <li>1</li>
      <li>2</li>
      <li>3</li>
      <li>4</li>
      <li>5</li>
      <li>6</li>
    </ul>
  </div>
  <?php
}
// write [memory_game] to display the game
add_shortcode('memory_game', 'memory_front');

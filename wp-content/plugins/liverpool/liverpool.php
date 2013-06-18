<?php
/*
Plugin Name: Liverpool selector
Plugin URI: http://plugin.liverpool.co.uk
Description: Liverpool's players CRUD.
Version: 1.0
Author: Pierre Dominique
Author URI: http://dominiquepierre.fr/
License: GNULIGAN
*/

function liverpool_init ()
{

  $labels = array(
    'name'              => 'Liverpool\'s Player',
    'add_new'           => 'Add a new player',
    'all_items'         => 'Players',
    'edit_new_item'     => 'Add a new player',
    'edit_item'         => 'Edit player',
    'new_item'          => 'New player',
    'view_item'         => 'Player\'s infos',
    'search_items'      => 'Looking for a player?',
    'not_found'         => 'None player!',
    'not_found_in_trash' => 'None player in trash!',
    'parent_item_colon' => 'Player',
    'menu_name'         => 'Liverpool\'s player'
  );

  register_post_type('player',
    array(
      'labels'              => $labels,
      'public'              => true,
      'publicly_queryable'  => false,
      'menu_position'       => 100,
      'supports'            => array('thumbnail')
    )
  );

} // end liverpool_init();
add_action('init', 'liverpool_init');

// adding player's info meta box
function liverpool_info_meta_box()
{
  // $id, $title, $callback, $post_type, $context, $priority
  add_meta_box(
    'liverpool-meta-box',
    'Fill player\'s informations',
    'liverpool_meta_box_cb',
    'player',
    'normal',
    'high'
  );
}
add_action('add_meta_boxes', 'liverpool_info_meta_box');

// display of meta box with values
function liverpool_meta_box_cb($post)
{
  // $post is already set, and contains an object: the WordPress post
  global $post;

  $values     = get_post_custom( $post->ID );

  $firstname  = isset( $values['meta_box_firstname'] ) ? $values['meta_box_firstname'][0] : null;
  $lastname   = isset( $values['meta_box_lastname'] ) ? $values['meta_box_lastname'][0] : null;
  $position   = isset( $values['meta_box_position'] ) ? $values['meta_box_position'][0] : null;
  $number     = isset( $values['meta_box_number'] ) ? $values['meta_box_number'][0] : null;

  $img = get_post_meta($post->ID, 'wp_custom_image', true);
  $image = isset ( $img ) ? '<a href="' . $img['url'] . '" target="_blank">' . $img['url'] . '</a>' : null;

  //The nonce field is used to validate that the contents of the form request came from the current site and not somewhere else.
  wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );

?>
      <p>
        <label for="meta_box_firstname">First name</label>
        <input type="text" name="meta_box_firstname" id="meta_box_name" value="<?php echo $firstname; ?>" />
      </p>
      <p>
        <label for="meta_box_lastname">Last name</label>
        <input type="text" name="meta_box_lastname" id="meta_box_lastname" value="<?php echo $lastname; ?>" />
        </p>
        <p>
            <label for="meta_box_position">Position</label>
            <select name="meta_box_position" id="meta_box_position">
              <option value="Attack" <?php selected( $position, 'Attacker' ); ?>>Attacker</option>
              <option value="Defend" <?php selected( $position, 'Defender' ); ?>>Defender</option>
              <option value="Midfield" <?php selected( $position, 'Midfield' ); ?>>Midfield</option>
              <option value="Goalkeep" <?php selected( $position, 'Goalkeeper' ); ?>>Goalkeeper</option>
            </select>
        </p>
        <p>
          <label for="meta_box_number">Number</label>
            <select name="meta_box_number" id="meta_box_number">
              <option></option>
              <?php
                for ( $i = 1 ; $i <= 50 ; $i++ ) {
                  echo "<option value='{$i}'" . selected($number, $i) . ">{$i}</option>";
                }
              ?>
            </select>
        </p>
        <p>
          <p>Current picture : <?php echo isset ( $image ) ? $image : 'None' ; ?></p>
        </p>

<?php
} // liverpool meta box cb function end

// meta box to upload picture
function liverpool_img_meta_box() {

  // $id, $title, $callback, $post_type, $context, $priority
  add_meta_box(
    'wp_custom_attachment',
    'Picture',
    'wp_img_attachment',
    'player',
    'advanced',
    'high'
  );

} // end liverpool_img_meta_box
add_action('add_meta_boxes', 'liverpool_img_meta_box'); // upload img

function wp_img_attachment() {

  wp_nonce_field(plugin_basename(__FILE__), 'wp_custom_image_nonce');

  $html = '<p class="description">';
  $html .= 'Upload player\'s picture here.';
  $html .= '</p>';
  $html .= '<input type="file" id="wp_custom_image" name="wp_custom_image" value="" size="25">';

  echo $html;

} // end wp_img_attachment

function modify_form() // allows form to send $_FILE
{

  echo '<script type="text/javascript">
          jQuery("#post").attr("enctype", "multipart/form-data");
        </script>';

}
add_action('admin_footer','modify_form');

// save edit or new player
function liverpool_meta_box_save( $post_id )
{
  if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
    return;

  // check for security related to nonce
  if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) )
    return;

  // current user can edit?
  if( !current_user_can( 'edit_post' ) )
    return;

  if( isset( $_POST['meta_box_firstname'] ) )
    update_post_meta( $post_id, 'meta_box_firstname', wp_kses_post( $_POST['meta_box_firstname']) );

  if( isset( $_POST['meta_box_lastname'] ) )
    update_post_meta( $post_id, 'meta_box_lastname', wp_kses_post( $_POST['meta_box_lastname']) );

  if( isset( $_POST['meta_box_position'] ) )
    update_post_meta( $post_id, 'meta_box_position', esc_attr( $_POST['meta_box_position'] ) );

  if( isset( $_POST['meta_box_number'] ) )
    update_post_meta( $post_id, 'meta_box_number', esc_attr( $_POST['meta_box_number'] ) );

  // Make sure the file array isn't empty
  if(!empty($_FILES['wp_custom_image']['name'])) {

    // Setup the array of supported file types. In this case, it's just PDF.
    $supported_types = array('image/png','image/jpeg');

    // Get the file type of the upload
    $arr_file_type = wp_check_filetype(basename($_FILES['wp_custom_image']['name']));
    $uploaded_type = $arr_file_type['type'];

    // Check if the type is supported. If not, throw an error.
    if(in_array($uploaded_type, $supported_types)) {

      // Use the WordPress API to upload the file
      $upload = wp_upload_bits($_FILES['wp_custom_image']['name'], null, file_get_contents($_FILES['wp_custom_image']['tmp_name']));

      if(isset($upload['error']) && $upload['error'] != 0) {
        wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
      } else {
        update_post_meta($post_id, 'wp_custom_image', $upload);
      } // end if/else

    } else {
      wp_die("The file type that you've uploaded is not a image.");
    } // end if/else

  } // end if

} // end save
add_action('save_post', 'liverpool_meta_box_save');

// frontend table display
function liverpool_show($limit = 5)
{

  $players = new WP_Query("post_type=player&posts_per_pasge=$limit");

  ?>
  <div id="liverpool-players">
    <table class="players-table">
      <thead>
        <tr>
          <th>First name</th>
          <th>Last name</th>
          <th>Position</th>
          <th>Number</th>
          <th>Picture</th>
        </tr>
      </thead>
      <tbody>
      <?php
        if ( $players->have_posts() ) :
          while ( $players->have_posts() ) : $players->the_post();
            global $post;
            $image = get_post_meta($post->ID,'wp_custom_image', true);
            if ( ! ( isset ( $image ) && $image ) )
              $image['url'] = null;
            echo ("
              <tr>
                <td>" . get_post_meta($post->ID, 'meta_box_firstname', true) . "</td>
                <td>" . get_post_meta($post->ID, 'meta_box_lastname', true) . "</td>
                <td>" . get_post_meta($post->ID, 'meta_box_position', true) . "</td>
                <td>" . get_post_meta($post->ID, 'meta_box_number', true) . "</td>
                <td><img src='{$image['url']}' width=150 height=150 />
              </tr>
            ");
          endwhile;
        else :
          echo wpautop( 'Sorry, no players were found' );
        endif;
      ?>
      </tbody>
    </table>
  </div>

<?php
} // end liverpool_show function
// write [liverpool_crud] to display the crud
add_shortcode('liverpool_crud','liverpool_show');

function liverpool_manage_posts_columns($columns)
{
  unset($columns['title'], $columns['date']);

  $columns = array_merge($columns,
    array(
      'firstname'       => __('First name'),
      'lastname'        => __('Last name'),
      'team'            => __('Position'),
      'number'          => __('Number'),
      'picture'         => __('Picture'),
      'edit'            => __('Edit')
    ));
  return $columns;
}
add_filter('manage_edit-player_columns', 'liverpool_manage_posts_columns');

function liverpool_manage_posts_custom_column($column, $post_id)
{

  switch ($column) {
    case 'firstname':
      $liverpool_player = get_post_meta($post_id, 'meta_box_firstname', true);
      break;
    case 'lastname':
      $liverpool_player = get_post_meta($post_id, 'meta_box_lastname', true);
      break;
    case 'team':
      $liverpool_player = get_post_meta($post_id, 'meta_box_position', true);
      break;
    case 'number':
      $liverpool_player = get_post_meta($post_id, 'meta_box_number', true);
      break;
    case 'picture':
      $image = get_post_meta($post_id, 'wp_custom_image', true);
      if ( isset ( $image ) && $image ) {
        $liverpool_player = "<img src='{$image['url']}' width=150 height=150 />";
      }
      break;
    case 'edit':
      $liverpool_player = "<a href='post.php?post={$post_id}&action=edit'>Edit</a>";
      break;
  }

  echo $liverpool_player;

}
add_action('manage_posts_custom_column', 'liverpool_manage_posts_custom_column', 10, 2);

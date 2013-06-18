<?php
/*
Plugin Name: Programming Language CRUD
Plugin URI: http://plugin.geek.com
Description: CRUD of Programming Languages.
Version: 1.0
Author: Mohamed Bena
Author URI: http://momz.fr/
License: GEEKNU
*/

function proglang_init ()
{

  $labels = array(
    'name'              => 'Programming language',
    'add_new'           => 'Add a new programming language',
    'all_items'         => 'Programming languages',
    'edit_new_item'     => 'Add a new programming language',
    'edit_item'         => 'Edit programming language',
    'new_item'          => 'New programming language',
    'view_item'         => 'Programming language\'s infos',
    'search_items'      => 'Looking for a programming language?',
    'not_found'         => 'None programming language!',
    'not_found_in_trash' => 'None programming language in trash!',
    'parent_item_colon' => 'Programming language',
    'menu_name'         => 'Programming language'
  );

  register_post_type('proglang',
    array(
      'labels'              => $labels,
      'public'              => true,
      'publicly_queryable'  => false,
      'menu_position'       => 100,
      'supports'            => array('thumbnail')
    )
  );

  wp_enqueue_style( 'wp-color-picker' );
  wp_enqueue_script( 'wp-color', plugins_url() . '/programming-language/js/wp-color.js' );
  wp_enqueue_script( 'wp-color-picker', 'wp-color-picker' );

}
add_action('init', 'proglang_init');

// adding proglang's info meta box
function proglang_info_meta_box()
{
  // $id, $title, $callback, $post_type, $context, $priority
  add_meta_box(
    'proglang-meta-box',
    'Fill programming language\'s informations',
    'proglang_meta_box_cb',
    'proglang',
    'normal',
    'high'
  );
}
add_action('add_meta_boxes', 'proglang_info_meta_box');

// display of meta box with values
function proglang_meta_box_cb($post)
{
  // $post is already set, and contains an object: the WordPress post
  global $post;

  $values     = get_post_custom( $post->ID );

  $name       = isset( $values['meta_box_name'] ) ? $values['meta_box_name'][0] : null;
  $desc       = isset( $values['meta_box_desc'] ) ? $values['meta_box_desc'][0] : null;
  $color      = isset( $values['meta_box_color'] ) ? $values['meta_box_color'][0] : null;
  $type       = isset( $values['meta_box_type'] ) ? $values['meta_box_type'][0] : null;
  $date       = isset( $values['meta_box_date'] ) ? $values['meta_box_date'][0] : null;
  $difficulty = isset( $values['meta_box_difficulty'] ) ? $values['meta_box_difficulty'][0] : null;

  $img = get_post_meta($post->ID, 'wp_custom_logo', true);
  $image = isset ( $img ) ? '<a href="' . $img['url'] . '" target="_blank">' . $img['url'] . '</a>' : null;

  //The nonce field is used to validate that the contents of the form request came from the current site and not somewhere else.
  wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );

?>
      <p>
        <label for="meta_box_name">Name</label>
        <input type="text" name="meta_box_name" id="meta_box_name" value="<?php echo $name; ?>" />
      </p>
      <p>
        <label for="meta_box_desc">Description</label>
        <textarea name="meta_box_desc" id="meta_box_desc" value="<?php echo $desc; ?>"></textarea>
      </p>
      <p>
        <label for="meta_box_color">Color</label>
        <input type="text" name="meta_box_color" id="meta_box_color" class="input-color" value="<?php echo $color; ?>" />
      </p>
      <p>
        <label for="meta_box_type">Type</label>
        <select name="meta_box_type" id="meta_box_type">
          <option value="Interpreted language" <?php selected( $type, 'Interpreted language' ); ?>>Interpreted language</option>
          <option value="Procedural programming" <?php selected( $type, 'Procedural programming' ); ?>>Procedural programming</option>
        </select>
      </p>
        <p>
          <label for="meta_box_date">Date of creation</label>
          <input type="date" name="meta_box_date" id="meta_box_date" value="<?php echo $date; ?>" />
        </p>
        <p>
          <label for="meta_box_difficulty">Difficulty</label>
          <input type="radio" name="meta_box_difficulty" value="easy">Easy
          <input type="radio" name="meta_box_difficulty" value="hard">Hard
        </p>
        <p>
          <p>Current picture : <?php echo isset ( $image ) ? $image : 'None' ; ?></p>
        </p>

<?php
} // langprog meta box cb function end

// meta box to upload picture
function proglang_logo_meta_box() {

  // $id, $title, $callback, $post_type, $context, $priority
  add_meta_box(
    'wp_custom_attachment',
    'Logo',
    'wp_logo_attachment',
    'proglang',
    'advanced',
    'high'
  );

} // end proglang_img_meta_box
add_action('add_meta_boxes', 'proglang_logo_meta_box'); // upload img

function wp_logo_attachment() {

  wp_nonce_field(plugin_basename(__FILE__), 'wp_custom_image_nonce');

  $html = '<p class="description">';
  $html .= 'Upload Programming language\'s logo here.';
  $html .= '</p>';
  $html .= '<input type="file" id="wp_custom_logo" name="wp_custom_logo" value="" size="25">';

  echo $html;

} // end wp_logo_attachment

function modify_proglang_form() // allows form to send $_FILE
{

  echo '<script type="text/javascript">
          jQuery("#post").attr("enctype", "multipart/form-data");
        </script>';

}
add_action('admin_footer','modify_proglang_form');

// save edit or new proglang
function proglang_meta_box_save( $post_id )
{
  if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
    return;

  // check for security related to nonce
  if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) )
    return;

  // current user can edit?
  if( !current_user_can( 'edit_post' ) )
    return;

  if( isset( $_POST['meta_box_name'] ) )
    update_post_meta( $post_id, 'meta_box_name', wp_kses_post( $_POST['meta_box_name']) );

  if( isset( $_POST['meta_box_desc'] ) )
    update_post_meta( $post_id, 'meta_box_desc', wp_kses_post( $_POST['meta_box_desc']) );

  if( isset( $_POST['meta_box_type'] ) )
    update_post_meta( $post_id, 'meta_box_type', esc_attr( $_POST['meta_box_type'] ) );

  if( isset( $_POST['meta_box_date'] ) )
    update_post_meta( $post_id, 'meta_box_date', esc_attr( $_POST['meta_box_date'] ) );

  if( isset( $_POST['meta_box_difficulty'] ) )
    update_post_meta( $post_id, 'meta_box_difficulty', esc_attr( $_POST['meta_box_difficulty'] ) );

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
add_action('save_post', 'proglang_meta_box_save');

// frontend table display
function proglang_show($limit = 5)
{

  $proglangs = new WP_Query("post_type=proglang&posts_per_pasge=$limit");

  ?>
  <div id="proglang">
    <table class="proglangs-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Description</th>
          <th>Color</th>
          <th>Type</th>
          <th>Creation date</th>
          <th>Difficulty</th>
          <th>Logo</th>
        </tr>
      </thead>
      <tbody>
      <?php
        if ( $proglangs->have_posts() ) :
          while ( $proglangs->have_posts() ) : $proglangs->the_post();
            global $post;
            $image = get_post_meta($post->ID,'wp_custom_logo', true);
            if ( ! ( isset ( $image ) && $image ) )
              $image['url'] = null;
            echo ("
              <tr>
                <td>" . get_post_meta($post->ID, 'meta_box_name', true) . "</td>
                <td>" . get_post_meta($post->ID, 'meta_box_desc', true) . "</td>
                <td>" . get_post_meta($post->ID, 'meta_box_color', true) . "</td>
                <td>" . get_post_meta($post->ID, 'meta_box_type', true) . "</td>
                <td>" . get_post_meta($post->ID, 'meta_box_date', true) . "</td>
                <td>" . get_post_meta($post->ID, 'meta_box_difficulty', true) . "</td>
                <td><img src='{$image['url']}' width=150 height=150 />
              </tr>
            ");
          endwhile;
        else :
          echo wpautop( 'Sorry, no programming language were found' );
        endif;
      ?>
      </tbody>
    </table>
  </div>

<?php
} // end proglang show function
// write [proglang_crud] to display the crud
add_shortcode('proglang_crud','proglang_show');

function proglang_manage_posts_columns($columns)
{
  unset($columns['title'], $columns['date']);

  $columns = array_merge($columns,
    array(
      'name'       => __('Name'),
      'desc'       => __('Description'),
      'color'      => __('Color'),
      'type'       => __('Type'),
      'date'       => __('Creation date'),
      'difficulty' => __('Difficulty'),
      'logo'       => __('Logo'),
      'modify'     => __('Edit')
    ));
  return $columns;
}
add_filter('manage_edit-proglang_columns', 'proglang_manage_posts_columns');

function proglang_manage_posts_custom_column($column, $post_id)
{

  switch ($column) {
    case 'name':
      $proglang = get_post_meta($post_id, 'meta_box_name', true);
      break;
    case 'desc':
      $proglang = get_post_meta($post_id, 'meta_box_desc', true);
      break;
    case 'color':
      $proglang = get_post_meta($post_id, 'meta_box_color', true);
      break;
    case 'type':
      $proglang = get_post_meta($post_id, 'meta_box_type', true);
      break;
    case 'date':
      $proglang = get_post_meta($post_id, 'meta_box_date', true);
      break;
    case 'difficulty':
      $proglang = get_post_meta($post_id, 'meta_box_difficulty', true);
      break;
    case 'logo':
      $image = get_post_meta($post_id, 'wp_custom_logo', true);
      if ( isset ( $image ) && $image ) {
        $proglang = "<img src='{$image['url']}' width=150 height=150 />";
      }
      break;
    case 'modify':
      $proglang = "<a href='post.php?post={$post_id}&action=edit'>Edit</a>";
      break;
  }

  echo $proglang;

}
add_action('manage_posts_custom_column', 'proglang_manage_posts_custom_column', 10, 2);

<?php
/*
Plugin Name: Web sites Portfolio - ESGI
Plugin URI: http://plugin.izi-portfolio.com
Description: Web sites portfolio CRUD.
Version: 1.0.1
Author: Pierre Grimaud
Author URI: https://github.com/pgrimaud
License: GNULIO
*/

function portfolio_init ()
{

  $labels = array(
    'name'              => 'Web site Portfolio',
    'add_new'           => 'Add a new web site',
    'all_items'         => 'Web sites',
    'edit_new_item'     => 'Add a new web site',
    'edit_item'         => 'Edit web site',
    'new_item'          => 'New web site',
    'view_item'         => 'Web site\'s infos',
    'search_items'      => 'Looking for a web site?',
    'not_found'         => 'Sorry, no web site were found!',
    'not_found_in_trash' => 'Sorry, no web site were found in trash!',
    'parent_item_colon' => 'Web site',
    'menu_name'         => 'Web site portfolio',
  );

  register_post_type(
    'website',
        array(
          'public'              => true,
          'publicly_queryable'  => false,
          'labels'              => $labels,
          'menu_position'       => 100,
          'supports'            => array('thumbnail')
          )
  );

} // end portfolio_init();
add_action('init','portfolio_init');

// adding meta box to add new website
function portfolio_add_meta_box()
{

  // $id, $title, $callback, $post_type, $context, $priority
  add_meta_box(
    'portfolio-meta-box',
    'Fill web site\'s informations',
    'portfolio_meta_box_cb',
    'website',
    'normal',
    'high'
  );

}
add_action('add_meta_boxes','portfolio_add_meta_box');

// meta box to upload picture
function portfolio_sc_meta_box() {

  // $id, $title, $callback, $post_type, $context, $priority
  add_meta_box(
    'wp_custom_attachment',
    'Screenshot(s)',
    'wp_screenshot_attachment',
    'website',
    'advanced',
    'high'
  );

} // end portfolio_sc_meta_box
add_action('add_meta_boxes', 'portfolio_sc_meta_box');

function wp_screenshot_attachment() {

  wp_nonce_field(plugin_basename(__FILE__), 'wp_custom_image_nonce');

  $html = '<p class="description">';
  $html .= 'Upload website\'s screenshot(s) here.';
  $html .= '</p>';
  $html .= '<input type="file" id="wp_custom_sc" name="wp_custom_sc" value="" size="25">';

  echo $html;

} // end wp_screenshot_attachment

function modify_portfolio_form() // allows form to send $_FILE
{

  echo '<script type="text/javascript">
          jQuery("#post").attr("enctype", "multipart/form-data");
        </script>';

}
add_action('admin_footer','modify_portfolio_form');

// display of meta box with values
function portfolio_meta_box_cb($post)
{

  // $post is already set, and contains an object: the WordPress post
  global $post;

  $values     = get_post_custom( $post->ID );

  $projectname  = isset( $values['meta_box_projectname'] ) ? $values['meta_box_projectname'][0] : null;
  $clientname   = isset( $values['meta_box_clientname'] ) ? $values['meta_box_clientname'][0] : null;
  $url          = isset( $values['meta_box_url'] ) ? $values['meta_box_url'][0] : null;
  $servicetype  = isset( $values['my_meta_box_servicetype'] ) ? $values['my_meta_box_servicetype'][0]  : null;
  $technicalenvironment = isset( $values['meta_box_technicalenvironment'] ) ? $values['meta_box_technicalenvironment'][0]  : null;
  $projectduration      = isset( $values['meta_box_projectduration'] ) ? $values['meta_box_projectduration'][0]  : null;

  //The nonce field is used to validate that the contents of the form request came from the current site and not somewhere else.
  wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );

?>
      <p>
        <label for="meta_box_projectname">Project name</label>
        <input type="text" name="meta_box_projectname" id="meta_box_projectname" value="<?php echo $projectname; ?>" />
      </p>
      <p>
        <label for="meta_box_clientname">Client name</label>
        <input type="text" name="meta_box_clientname" id="meta_box_clientname" value="<?php echo $clientname; ?>" />
        </p>
      <p>
      <p>
        <label for="meta_box_url">URL</label>
        <input type="text" name="meta_box_url" id="meta_box_url" value="<?php echo $url; ?>" />
      </p>
      <p>
        <label for="meta_box_servicetype">Service type</label>
        <select name="meta_box_servicetype" id="meta_box_servicetype">
          <option value="Full service" <?php selected( $servicetype, 'Full service' ); ?>>Full service</option>
          <option value="Backend" <?php selected( $servicetype, 'Backend' ); ?>>Backend</option>
          <option value="Frontend" <?php selected( $servicetype, 'Frontend' ); ?>>Frontend</option>
        </select>
      </p>
      <p>
        <label for="meta_box_technicalenvironment">Technical environment</label>
        <input type="text" name="meta_box_technicalenvironment" id="meta_box_technicalenvironment" value="<?php echo $technicalenvironment; ?>" />
      </p>
      <p>
        <label for="meta_box_projectduration">Project duration</label>
        <input type="text" name="meta_box_projectduration" id="meta_box_projectduration" value="<?php echo $projectduration; ?>" />
      </p>
      <div>
        <h4>Current screenshot(s)</h4>
        <ul>
          <?php
            // script added to send ajax query to delete screenshots
            wp_enqueue_script( 'delete-sc', plugins_url() . '/portfolio-esgi/js/delete-sc.js' );

            $screenshots = get_post_meta($post->ID, 'wp_custom_sc');
            if ( isset ( $screenshots ) && $screenshots ) {
              foreach ( $screenshots as $sc ) {
                echo "<li><a href='{$sc['url']}' target='_blank'>{$sc['url']}</a> - <a href='' class='delete-sc' data-url='{$sc['url']}'>delete</a></li>";
              }
            } else {
              echo "<li>None</li>";
            }
          ?>
        </ul>
      </div>

<?php
} // portfolio meta box cb function end

// frontend table display
function portfolio_show($limit = 5)
{

  $websites = new WP_Query("post_type=website&posts_per_pasge=$limit");

  ?>
    <div id="website-portfolio">
      <table class="fe-websites-table">
        <thead>
          <tr>
            <th>Project name</th>
            <th>Client name</th>
            <th>Url</th>
            <th>Screenshot</th>
            <th>Service type</th>
            <th>Technical environment</th>
            <th>Project duration</th>
          </tr>
        </thead>
        <tbody>
        <?php
          if ( $websites->have_posts() ) :
            while ( $websites->have_posts() ) : $websites->the_post();
              global $post;
              $screenshots = get_post_meta($post->ID, 'wp_custom_sc');
              $screens = "<ul>";
              foreach ( $screenshots as $sc ) {
                $screens .= "<li><a href='{$sc['url']}' target='_blank'>{$sc['url']}</a></li>";
              }
              $screens .= "</ul>";
              echo ("
                <tr>
                  <td>" . get_post_meta($post->ID,'meta_box_projectname',true) . "</td>
                  <td>" . get_post_meta($post->ID,'meta_box_clientname',true) . "</td>
                  <td>" . get_post_meta($post->ID,'meta_box_url',true) . "</td>
                  <td>" . $screens . "</td>
                  <td>" . get_post_meta($post->ID,'meta_box_servicetype',true) . "</td>
                  <td>" . get_post_meta($post->ID,'meta_box_technicalenvironment',true) . "</td>
                  <td>" . get_post_meta($post->ID,'meta_box_projectduration',true) . "</td>
                </tr>
              ");
            endwhile;
          endif;
        ?>
        </tbody>
      </table>
    </div>

<?php
} // end portfolio_show function
// write [portfolio_crud] to display the crud
add_shortcode('portfolio_crud','portfolio_show');

function portfolio_manage_posts_columns($columns)
{

  unset($columns['title'], $columns['date']);

  $columns = array_merge($columns,
    array(
      'projectname'          => __('Project name'),
      'clientname'           => __('Client name'),
      'url'                  => __('Url'),
      'servicetype'          => __('Service type'),
      'technicalenvironment' => __('Technical environment'),
      'projectduration'      => __('Project duration'),
      'screenshot'           => __('Screenshots'),
      'edit'                 => __('Edit')
    ));

  return $columns;

}
add_filter('manage_edit-website_columns', 'portfolio_manage_posts_columns');

function portfolio_manage_posts_custom_column($column, $post_id)
{

  switch ($column) {
    case 'projectname':
      $portfolio = get_post_meta($post_id, 'meta_box_projectname', true);
      break;
    case 'clientname':
      $portfolio = get_post_meta($post_id, 'meta_box_clientname', true);
      break;
    case 'url':
      $portfolio = "<a href='" . get_post_meta($post_id, 'meta_box_url', true) . "'>" . get_post_meta($post_id, 'meta_box_url', true) . "</a>";
      break;
    case 'servicetype':
      $portfolio = get_post_meta($post_id, 'meta_box_servicetype', true);
      break;
    case 'technicalenvironment':
      $portfolio = get_post_meta($post_id, 'meta_box_technicalenvironment', true);
      break;
    case 'projectduration':
      $portfolio = get_post_meta($post_id, 'meta_box_projectduration', true);
      break;
    case 'screenshot':
      $screenshots = get_post_meta($post_id, 'wp_custom_sc');
      $portfolio = "<ul>";
      foreach ( $screenshots as $sc ) {
        $portfolio .= "<li><a href='{$sc['url']}' target='_blank'>{$sc['url']}</a></li>";
      }
      $portfolio .= "</ul>";
      break;
    case 'edit':
      $liverpool_player = "<a href='post.php?post={$post_id}&action=edit'>Edit</a>";
      break;
  }

    echo $portfolio;

}
add_action('manage_posts_custom_column', 'portfolio_manage_posts_custom_column', 10, 2);

// save edit or new web site
function portfolio_meta_box_save( $post_id )
{

  if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
    return;

  // check for security related to nonce
  if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) )
    return;

  // current user can edit?
  if( !current_user_can( 'edit_post' ) )
    return;

  if( isset( $_POST['meta_box_projectname'] ) )
    update_post_meta( $post_id, 'meta_box_projectname', wp_kses_post( $_POST['meta_box_projectname']) );

  if( isset( $_POST['meta_box_clientname'] ) )
    update_post_meta( $post_id, 'meta_box_clientname', wp_kses_post( $_POST['meta_box_clientname']) );

  if( isset( $_POST['meta_box_url'] ) )
    update_post_meta( $post_id, 'meta_box_url', esc_attr( $_POST['meta_box_url'] ) );

  if( isset( $_POST['meta_box_servicetype'] ) )
    update_post_meta( $post_id, 'meta_box_servicetype', esc_attr( $_POST['meta_box_servicetype'] ) );

  if( isset( $_POST['meta_box_technicalenvironment'] ) )
    update_post_meta( $post_id, 'meta_box_technicalenvironment', esc_attr( $_POST['meta_box_technicalenvironment'] ) );

  if( isset( $_POST['meta_box_projectduration'] ) )
    update_post_meta( $post_id, 'meta_box_projectduration', esc_attr( $_POST['meta_box_projectduration'] ) );

  // Make sure the file array isn't empty
  if(!empty($_FILES['wp_custom_sc']['name'])) {

    $supported_types = array('image/png','image/jpeg');

    // Get the file type of the upload
    $arr_file_type = wp_check_filetype(basename($_FILES['wp_custom_sc']['name']));
    $uploaded_type = $arr_file_type['type'];

    // Check if the type is supported. If not, throw an error.
    if(in_array($uploaded_type, $supported_types)) {

      // Use the WordPress API to upload the file
      $upload = wp_upload_bits($_FILES['wp_custom_sc']['name'], null, file_get_contents($_FILES['wp_custom_sc']['tmp_name']));

      if(isset($upload['error']) && $upload['error'] != 0) {
        wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
      } else {
        add_post_meta($post_id, 'wp_custom_sc', $upload);
      } // end if/else

    } else {
      wp_die("The file type that you've uploaded is not a image.");
    } // end if/else

  } // end if

}
add_action('save_post', 'portfolio_meta_box_save');


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

add_action('init','portfolio_init');
add_action('add_meta_boxes','portfolio_add_meta_box');

add_action('manage_posts_custom_column', 'portfolio_manage_posts_custom_column', 10, 2);
add_filter('manage_edit-website_columns', 'portfolio_manage_posts_columns');

add_action('save_post', 'portfolio_meta_box_save');

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

  add_image_size('website',1000,300,true);
  wp_enqueue_script('script',plugins_url().'/portfolio/js/script.js',array('jquery'),'1.1.1',true);

} // end portfolio_init();

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

// display of meta box with values
function portfolio_meta_box_cb($post)
{
  // $post is already set, and contains an object: the WordPress post
  global $post;

  $values     = get_post_custom( $post->ID );

  $projectname  = isset( $values['meta_box_projectname'] ) ? $values['meta_box_projectname'][0] : null;
  $clientname   = isset( $values['meta_box_clientname'] ) ? $values['meta_box_clientname'][0] : null;
  $url          = isset( $values['meta_box_url'] ) ? $values['meta_box_url'][0] : null;
  $screenshot   = isset( $values['meta_box_screenshot'] ) ? $values['meta_box_screenshot'][0] : null;
  $servicetype  = isset( $values['my_meta_box_servicetype'] ) ? $values['my_meta_box_servicetype'][0]  : null;
  $technicalenvironment = isset( $values['my_meta_box_technicalenvironment'] ) ? $values['my_meta_box_technicalenvironment'][0]  : null;
  $projectduration      = isset( $values['my_meta_box_projectduration'] ) ? $values['my_meta_box_projectduration'][0]  : null;

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
        <label for="meta_box_screenshot">Screenshot</label>
        <input type="text" name="meta_box_screenshot" id="meta_box_screenshot" value="<?php echo $screenshot; ?>" />
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
              echo ("
                <tr>
                  <td>" . get_post_meta($post->ID,'meta_box_projectname',true) . "</td>
                  <td>" . get_post_meta($post->ID,'meta_box_clientname',true) . "</td>
                  <td>" . get_post_meta($post->ID,'meta_box_url',true) . "</td>
                  <td>" . the_post_thumbnail('website',array('style' => 'width:150px !important;')) . "</td>
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

  $columns = array_merge($columns, array(
                                      'projectname'          => __('Project name'),
                                      'clientname'           => __('Client name'),
                                      'url'                   => __('Url'),
                                      'screenshot'            => __('Screenshots'),
                                      'servicetype'          => __('Service type'),
                                      'technicalenvironment' => __('Technical environment'),
                                      'projectduration'      => __('Project duration'),
                                      'edit'                 => __('Edit')
                                    ));
  return $columns;
}

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
        case 'screenshot':
          if (has_post_thumbnail())
            $portfolio = get_the_post_thumbnail($post_id,'thumbnail');
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
        case 'edit':
          $liverpool_player = "<a href='post.php?post={$post_id}&action=edit'>Edit</a>";
          break;
    }

    echo $portfolio;
}

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
    update_post_meta( $post_id, 'meta_box_projectname', wp_kses( $_POST['meta_box_projectname']) );

  if( isset( $_POST['meta_box_clientname'] ) )
    update_post_meta( $post_id, 'meta_box_clientname', wp_kses( $_POST['meta_box_clientname']) );

  if( isset( $_POST['meta_box_url'] ) )
    update_post_meta( $post_id, 'meta_box_url', esc_attr( $_POST['meta_box_url'] ) );

  if( isset( $_POST['meta_box_screenshot'] ) )
    update_post_meta( $post_id, 'meta_box_screenshot', esc_attr( $_POST['meta_box_screenshot'] ) );

  if( isset( $_POST['meta_box_servicetype'] ) )
    update_post_meta( $post_id, 'meta_box_servicetype', esc_attr( $_POST['meta_box_servicetype'] ) );

  if( isset( $_POST['meta_box_technicalenvironment'] ) )
    update_post_meta( $post_id, 'meta_box_technicalenvironment', esc_attr( $_POST['meta_box_technicalenvironment'] ) );

  if( isset( $_POST['meta_box_projectduration'] ) )
    update_post_meta( $post_id, 'meta_box_projectduration', esc_attr( $_POST['meta_box_projectduration'] ) );
}


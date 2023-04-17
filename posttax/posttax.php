<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.doesnotexist.com
 * @since             1.0.0
 * @package           Posttax
 *
 * @wordpress-plugin
 * Plugin Name:       posttax
 * Plugin URI:        https://www.doesnotexist.com
 * Description:       This is a description of the plugin.
 * Version:           1.0.0
 * Author:            charles
 * Author URI:        https://www.doesnotexist.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       posttax
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'POSTTAX_VERSION', '1.0.0' );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-posttax-activator.php
 */
function activate_posttax() {
	// Require Advanced Custom Fields
	if ( ! is_plugin_active( 'advanced-custom-fields/acf.php' ) and current_user_can( 'activate_plugins' ) ) {
		// Stop activation redirect and show error
		wp_die('Sorry, but this plugin requires the Advanced Custom Fields to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
	}

	/* Creates an option that flushes rewrite rules but only once */
	if ( ! get_option( 'posttax-flush-rewrite-rules-flag' ) ) {
        add_option( 'posttax-flush-rewrite-rules-flag', true );
    }

	if ( ! get_option( 'posttax-create-acf-fields-flag' ) ) {
        add_option( 'posttax-create-acf-fields-flag', true );
    }


	require_once plugin_dir_path( __FILE__ ) . 'includes/class-posttax-activator.php';
	Posttax_Activator::activate();
}

/* Deactivated this plugin if dependency is deactivated  */
function detect_plugin_deactivation( $plugin ) {
	/* List of plugin dependencies */
	if ($plugin==="advanced-custom-fields/acf.php")
	{
		/* On update of active plugins */
		add_action('update_option_active_plugins', 'deactivateBasedOnDependencies');
	}
	return $plugin;
}
/* On plugin deactivation */
add_action( 'deactivated_plugin', 'detect_plugin_deactivation', 10, 1 );


/* Create admin page menu option for post tax */
add_action( 'admin_menu', 'posttax_admin_settings' );

function posttax_admin_settings(){
	add_options_page("Post Tax Settings", "Post Tax Settings", 'manage_options', "posttax-settings-page", "posttax_settings_page");
}

function posttax_settings_page(){
	?> 
	<div class="wrapper">
		<h1> Post Tax Settings </h1>
		<form action="options.php" method="POST">
			<?php 
			settings_fields("posttaxplugin");
			do_settings_sections("posttax-settings-page");
			submit_button();?>
		</form>
	</div>
	
	<?php
}

add_action("admin_init", 'posttax_settings');
function posttax_settings(){
	add_settings_section('posttax_first_section', null, null, "posttax-settings-page" );
	add_settings_field('posttaxplocation' , "Display Location" , 'posttax_location_html' , "posttax-settings-page" , 'posttax_first_section' );
	register_setting('posttaxplugin', 'posttaxplocation', array('sanitize_callback' => 'sanitize_text_field', 'default' => '0'));
}

function posttax_location_html(){
	?> 
	
	<select name="posttaxplocation"> 
		<option value="0" <?php selected(get_option("posttaxplocation"),'0') ?> > Top of Post </option>
		<option value="1" <?php selected(get_option("posttaxplocation"),'1') ?>> Bottom Of Post </option>
	</select>
	

	<?php
}
/* Deactivate this plugin */
function deactivateBasedOnDependencies(){
	deactivate_plugins( "posttax/posttax.php" );
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-posttax-deactivator.php
 */
function deactivate_posttax() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-posttax-deactivator.php';
	Posttax_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_posttax' );
register_deactivation_hook( __FILE__, 'deactivate_posttax' );


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-posttax.php';

define('POSTTAXDOMAIN', 'posttax');
define('POSTTAXPATH', plugin_dir_path( __FILE__ ));

require_once(POSTTAXPATH . '/post-types/register.php');
require_once(POSTTAXPATH . '/taxonomies/register.php');


/**
 * Flush rewrite rules if the previously added flag exists,
 * and then remove the flag.
 */
add_action( 'init', 'myplugin_flush_rewrite_rules_maybe', 20 );
function myplugin_flush_rewrite_rules_maybe() {
    if ( get_option( 'posttax-flush-rewrite-rules-flag' ) ) {
        flush_rewrite_rules();
        delete_option( 'posttax-flush-rewrite-rules-flag' );
    }
}

/* Checks if a field group exists */
function is_field_group_exists($value, $type='post_title') {
	$exists = false;
	if ($field_groups = get_posts(array('post_type'=>'acf-field-group'))) {
		foreach ($field_groups as $field_group) {
			if ($field_group->$type == $value) {
				$exists = true;
			}
		}
	}
	return $exists;
}

/* Adds ACF fields */
function add_acf_group(){
    
    if( function_exists('acf_add_local_field_group') && get_option( 'posttax-create-acf-fields-flag' )):
		delete_option( 'posttax-create-acf-fields-flag' );
        if(!is_field_group_exists("Contact")){
        acf_import_field_group(array(
            'key' => 'group_1',
            'title' => 'Contact',
            'fields' => array(
                array(
                    'key' => 'field_1',
                    'label' => 'fName',
                    'name' => 'fname',
                    'aria-label' => '',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'maxlength' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                ),
                array(
                    'key' => 'field_2',
                    'label' => 'lName',
                    'name' => 'lname',
                    'aria-label' => '',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'maxlength' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                ),
                array(
                    'key' => 'field_3',
                    'label' => 'email',
                    'name' => 'email',
                    'aria-label' => '',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'maxlength' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                ),
                array(
                    'key' => 'field_4',
                    'label' => 'phoneNumber',
                    'name' => 'phonenumber',
                    'aria-label' => '',
                    'type' => 'number',
                    'instructions' => '',
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'min' => '',
                    'max' => '',
                    'placeholder' => '',
                    'step' => '',
                    'prepend' => '',
                    'append' => '',
                ),
                array(
                    'key' => 'field_5',
                    'label' => 'icon',
                    'name' => 'icon',
                    'aria-label' => '',
                    'type' => 'image',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'return_format' => 'url',
                    'library' => 'all',
                    'min_width' => '',
                    'min_height' => '',
                    'min_size' => '',
                    'max_width' => '',
                    'max_height' => '',
                    'max_size' => '',
                    'mime_types' => '',
                    'preview_size' => 'medium',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => '',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_rest' => 1,
        ));
    } 
    if(!is_field_group_exists("Jobs")){
        acf_import_field_group(array(
            'key' => 'group_643d3ffb28cd5',
            'title' => 'Jobs',
            'fields' => array(
                array(
                    'key' => 'field_643d3ffb5f1b3',
                    'label' => 'job-name',
                    'name' => 'job-name',
                    'aria-label' => '',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'maxlength' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                ),
                array(
                    'key' => 'field_643d40265f1b4',
                    'label' => 'description',
                    'name' => 'description',
                    'aria-label' => '',
                    'type' => 'textarea',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'maxlength' => '',
                    'rows' => '',
                    'placeholder' => '',
                    'new_lines' => '',
                ),
                array(
                    'key' => 'field_643d40765f1b6',
                    'label' => 'location',
                    'name' => 'location',
                    'aria-label' => '',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'maxlength' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                ),
                array(
                    'key' => 'field_643d40bf5f1b7',
                    'label' => 'Field of work',
                    'name' => 'field',
                    'aria-label' => '',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'maxlength' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                ),
                array(
                    'key' => 'field_643d598d55059',
                    'label' => 'Email',
                    'name' => 'email',
                    'aria-label' => '',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'maxlength' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'jobs',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_rest' => 1,
        ));
    }
    if(!is_field_group_exists("Applications")){
        
    acf_import_field_group(array(
        'key' => 'group_643d416e44c96',
        'title' => 'Applications',
        'fields' => array(
            array(
                'key' => 'field_643d416e36ca8',
                'label' => 'Name',
                'name' => 'name',
                'aria-label' => '',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'maxlength' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
            ),
            array(
                'key' => 'field_643d419c36ca9',
                'label' => 'Email',
                'name' => 'email',
                'aria-label' => '',
                'type' => 'email',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
            ),
            array(
                'key' => 'field_643d41aa36caa',
                'label' => 'Resume',
                'name' => 'resume',
                'aria-label' => '',
                'type' => 'file',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'return_format' => 'array',
                'library' => 'all',
                'min_size' => '',
                'max_size' => '',
                'mime_types' => '',
            ),
            array(
                'key' => 'field_643d422676d2a',
                'label' => 'Selected Job',
                'name' => 'selected-job',
                'aria-label' => '',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'maxlength' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'application',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
        'show_in_rest' => 1,
    ));
    }
	
    endif;
	
}
add_action('init', 'add_acf_group');

/* On insert of post send an appropriate email*/
add_action( 'wp_insert_post', 'after_task_post_created');
/* Email admins on new post of type of jobs */
function after_task_post_created( $post_id ) {
    //no action if post type not task
    if (get_post_type($post_id) != 'jobs')
    return;
    // If this is a revision, don't send the email.
    if ( wp_is_post_revision( $post_id ) )
    return;

    // if post not yet published so no action taken, i know it can be confused
    if (get_post_status( $post_id ) != 'publish' )
    return;

    // your email action
    $yoursubject = 'There has been a new job posted';

    $yourmessage = "A job posting has been created you can check it out now!";

    /* <TODO: Fix email later> wp mail doesn't work on local host*/
    $adminemail = get_option( 'admin_email' );
    wp_mail( "ccpink99@gmail.com", $yoursubject, $yourmessage );
}

/* On saving a post send an email */
add_action( 'save_post', 'er_send_email_on_post_draft_save' );
function er_send_email_on_post_draft_save( $post_id ) {
    if (get_post_type($post_id) != 'jobs'){
        return;
    }
    //verify post is not a revision
    if ( $post_id->post_status == 'draft' ) {

        $post_title = get_the_title( $post_id );
        $post_url = get_permalink( $post_id );
        $subject = 'A post has been updated';

        $message = "A job has been added to the website:\n\n";
        $message .= "" .$post_title. "\n\n";

        //send email to admin
        /* <TODO: Fix email later> wp mail doesn't work on local host*/
        wp_mail( 'ccpink99@gmail.com', $subject, $message );

    }
}

/* On saving a post send an email */
add_action( 'save_post', 'on_save_application' );
function on_save_application( $post_id ) {
    if (get_post_type($post_id) != 'applications'){
        return;
    }
    //verify post is not a revision
    if ( $post_id->post_status == 'draft' ) {

        $post_title = get_the_title( $post_id );
        $post_url = get_permalink( $post_id );
        $subject = 'A post has been updated';

        $message = "A job has been added to the website:\n\n";
        $message .= "" .$post_title. "\n\n";

        //send email to admin
        /* <TODO: Fix email later> wp mail doesn't work on local host*/
        wp_mail( 'ccpink99@gmail.com', $subject, $message );

    }
}


/* Add all shortcodes */
function posttax_shortcodes_init(){
	foreach(glob(plugin_dir_path( __FILE__ )  .  "/shortcodes/*.php") as $file){
		include($file);
	}
}
add_action('init', 'posttax_shortcodes_init');


/* This adds the single template page for jobs */
function single_job_template($single) {
    global $post;
    /* Checks for single template by post type */
    if ( $post->post_type == 'jobs' ) {
		error_log(plugin_dir_path( __FILE__ )  . '/single-pages/single-jobs.php');
        if ( file_exists( plugin_dir_path( __FILE__ )  . '/single-pages/single-jobs.php' ) ) {
            return plugin_dir_path( __FILE__ )  . '/single-pages/single-jobs.php';
        }
    }
    return $single;
}
/* Filter the single_template with our custom function*/
add_filter('single_template', 'single_job_template');

function add_js_scripts_redo() {
    wp_enqueue_script( 'ajax', plugins_url( 'public/js/thescript.js' , __FILE__ ), array('jquery'), null, true );
    // error_log(POSTTAXPATH .'public/js/thescript.js');
    
    // error_log(admin_url( 'admin-ajax.php' ));
    // pass Ajax Url to script.js
    wp_localize_script('ajax', 'wpAjax',  
        array('ajaxUrl', admin_url('admin-ajax.php')) 
    );
}
add_action('wp_enqueue_scripts', 'add_js_scripts_redo');


function get_projects() {
    $param = $_POST['category'];
    // error_log("I'm on line 38");
    $ajaxposts = new WP_Query([
      'post_type' => 'jobs',
      'meta_query' => array(
        array(
          'key' => $param,
          'orderby' => 'menu_order', 
          'order' => 'desc',
        )
        ),
    ]);
    
    $response = '';
  
    if($ajaxposts->have_posts()) {
        while($ajaxposts->have_posts()) : $ajaxposts->the_post();
            $fields = get_fields();
            $response .= do_shortcode("[single-job name='" . $fields['job-name'] . "' link='" . get_permalink() . "' desc='" .  $fields['description'] . "' location='" . $fields['location'] . "' field='" . $fields['field'] . "' email='" .  $fields['email'] . "' ]");

        endwhile;
    } else {
      $response = 'empty';
    }
    error_log($response);
    echo $response;
    exit;
    
  }

  add_action( 'wp_ajax_get_projects', 'get_projects' );
  add_action( 'wp_ajax_nopriv_get_projects', 'get_projects' );

  
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */

function run_posttax() {

	$plugin = new Posttax();
	$plugin->run();

}

run_posttax();

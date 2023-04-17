<?php 

function posttax_register_business_type() {
    $labels = array(
        'name' => __( 'Businesses', POSTTAXDOMAIN),
        'singular_name' => __('Business', POSTTAXDOMAIN),
        'featured_image' => __('Business Logo', POSTTAXDOMAIN),
        'use_featured_image' => __('Use Logo', POSTTAXDOMAIN),
        'set_featured_image' => __('Set Business Logo', POSTTAXDOMAIN),
        'remove_featured_image' => __('Remove Business Logo', POSTTAXDOMAIN),
        'archives' => __('Business Directory', POSTTAXDOMAIN),
        'add_new' => __('Add New Business', POSTTAXDOMAIN),
        'add_new_item' => __('Add New Business', POSTTAXDOMAIN),
    );

    $args = array(
        'labels' => $labels, 
        'public' => true,
        'has_archive' => 'businesses',
        'rewrite' => array(
            'has_front' => true,
        ),
        'menu_icon' => 'dashicons-building',
        'supports' => array(
            'title',
            'editor',
            'thumbnail',
        ),
        'show_in_rest' => true,
    );

    register_post_type( 'business', $args);

}
add_action('init', 'posttax_register_business_type');



function posttax_register_events_type() {
    $labels = array(
        'name' => __( 'Events', POSTTAXDOMAIN),
        'singular_name' => __('Event', POSTTAXDOMAIN),
        'featured_image' => __('Event Logo', POSTTAXDOMAIN),
        'use_featured_image' => __('Use Logo', POSTTAXDOMAIN),
        'set_featured_image' => __('Set Event Logo', POSTTAXDOMAIN),
        'remove_featured_image' => __('Remove Event Logo', POSTTAXDOMAIN),
        'archives' => __('Event Directory', POSTTAXDOMAIN),
        'add_new' => __('Add New Event', POSTTAXDOMAIN),
        'add_new_item' => __('Add New Event', POSTTAXDOMAIN),
    );

    $args = array(
        'labels' => $labels, 
        'public' => true,
        'has_archive' => 'events',
        'rewrite' => array(
            'has_front' => true,
        ),
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => array(
            'title',
            'editor',
            'thumbnail',
        ),
        'show_in_rest' => true,
    );

    register_post_type( 'event', $args);

}
add_action('init', 'posttax_register_events_type');

function posttax_register_jobs_type() {
    $labels = array(
        'name' => __( 'Jobs', POSTTAXDOMAIN),
        'singular_name' => __('Job', POSTTAXDOMAIN),
        'featured_image' => __('Job Logo', POSTTAXDOMAIN),
        'use_featured_image' => __('Use Logo', POSTTAXDOMAIN),
        'set_featured_image' => __('Set Job Logo', POSTTAXDOMAIN),
        'remove_featured_image' => __('Remove Job Logo', POSTTAXDOMAIN),
        'archives' => __('Jobs Directory', POSTTAXDOMAIN),
        'add_new' => __('Add New Job', POSTTAXDOMAIN),
        'add_new_item' => __('Add New Job', POSTTAXDOMAIN),
    );

    $args = array(
        'labels' => $labels, 
        'public' => true,
        'has_archive' => 'jobs',
        'rewrite' => array(
            'has_front' => true,
        ),
        'menu_icon' => 'dashicons-building',
        'supports' => array(
            'thumbnail',
        ),
        'show_in_rest' => true,
    );

    register_post_type( 'jobs', $args);

}
add_action('init', 'posttax_register_jobs_type');

function posttax_register_applications_type() {
    $labels = array(
        'name' => __( 'Applications', POSTTAXDOMAIN),
        'singular_name' => __('Application', POSTTAXDOMAIN),
        'featured_image' => __('Application Logo', POSTTAXDOMAIN),
        'use_featured_image' => __('Use Logo', POSTTAXDOMAIN),
        'set_featured_image' => __('Set Application Image', POSTTAXDOMAIN),
        'remove_featured_image' => __('Remove Business Logo', POSTTAXDOMAIN),
        'archives' => __('Business Directory', POSTTAXDOMAIN),
        'add_new' => __('Add New Application', POSTTAXDOMAIN),
        'add_new_item' => __('Add New Application', POSTTAXDOMAIN),
    );

    $args = array(
        'labels' => $labels, 
        'public' => true,
        'has_archive' => 'application',
        'rewrite' => array(
            'has_front' => true,
        ),
        'menu_icon' => 'dashicons-building',
        'supports' => array(
            'thumbnail',
        ),
        'show_in_rest' => true,
    );

    register_post_type( 'application', $args);

}
add_action('init', 'posttax_register_applications_type');













<?php 

function posttax_register_size_taxonomy(){
    $labels = array(
        'name' => __('Sizes', POSTTAXDOMAIN),
        'singular_name' => __('Size', POSTTAXDOMAIN),
        'add_new_item' => __('Add New Size', POSTTAXDOMAIN),
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_admin_column' => true,
        'show_in_quick_edit' => true,
        'show_in_rest' => true,
    );

    $post_types = array('business');

    register_taxonomy('size', $post_types, $args);
    
}
add_action('init', 'posttax_register_size_taxonomy');


function posttax_register_location_taxonomy(){
    $labels = array(
        'name' => __('Locations', POSTTAXDOMAIN),
        'singular_name' => __('Location', POSTTAXDOMAIN),
        'add_new_item' => __('Add New Location', POSTTAXDOMAIN),
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_admin_column' => true,
        'show_in_quick_edit' => true,
        'show_in_rest' => true,
        'hierarchical' => true,
        'rewrite' => array(
            'hierarchical' => true,
            'has_front' => true,
        ),
    );

    $post_types = array('business', 'event');

    register_taxonomy('location', $post_types, $args);
    
}
add_action('init', 'posttax_register_location_taxonomy');





?>
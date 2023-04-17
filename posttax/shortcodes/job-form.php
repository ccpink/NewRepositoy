<?php 

function jobs_form_att($atts){
    $default = array(
    );

    $a = shortcode_atts($default, $atts);

    ob_start();

    if(is_user_logged_in()){
    acf_form_head();
    ?>
    
    <div id="content">
    
    <?php
    
    acf_form(array(
        'post_id'       => 'new_post',
        'post_title'    => false,
        'post_content'  => false,
        'new_post'      => array(
            'post_type'     => 'jobs',
            'post_status'   => 'draft'
        ),
        'submit_value'  => 'Create new job'
    ));
    
    acf_form('new_post');
    ?>
    
    </div>

    <?php 
    }

    return ob_get_clean();
}

add_shortcode('jobs-form', 'jobs_form_att');


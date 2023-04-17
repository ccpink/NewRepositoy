<?php 

function applications_form_att($atts){
    $default = array(
    );

    $a = shortcode_atts($default, $atts);

    ob_start();
    acf_form_head();
    ?>
    
    <div id="content">
    
    <?php
    
    acf_form(array(
        'post_id'       => 'new_post',
        'post_title'    => false,
        'post_content'  => false,
        'new_post'      => array(
            'post_type'     => 'application',
            'post_status'   => 'publish'
        ),
        'submit_value'  => 'Create new application'
    ));
  
    acf_form('new-message');
    ?>
    
    </div>

    <?php 
    return ob_get_clean();
}

add_shortcode('applications-form', 'applications_form_att');
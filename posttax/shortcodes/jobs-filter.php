<?php 

function jobs_filter_att($atts){

    $default = array(
    );

    $a = shortcode_atts($default, $atts);

    $projects = new WP_Query([
        'post_type' => 'jobs',
        'posts_per_page' => -1,
    ]);

    ob_start();

    if(is_user_logged_in()){

    ?>

    <div id="content">

        <ul class="cat-list">
            <li><a class="cat-list_item active" href="#!" data-slug="">All projects</a></li>
            <li><a class="cat-list_item active" href="#!" data-slug="field_643d40765f1b6">Location</a></li>
            <li><a class="cat-list_item active" href="#!" data-slug="field_643d598d55059">Email</a></li>
            <li><a class="cat-list_item active" href="#!" data-slug="field_643d3ffb5f1b3">Name</a></li>
            <li><a class="cat-list_item active" href="#!" data-slug="field_643d40bf5f1b7">Job Type</a></li>
        </ul>
        <div class="project-tiles">
        <?php
        
        while($projects->have_posts()) : $projects->the_post();
            $fields = get_fields();
            echo do_shortcode("[single-job name='" . $fields['job-name'] . "' link='" . get_permalink() . "' desc='" .  $fields['description'] . "' location='" . $fields['location'] . "' field='" . $fields['field'] . "' email='" .  $fields['email'] . "' ]");
        endwhile;
        wp_reset_postdata(); 
        ?>
        </div>
    </div>

    <?php 
    }
    
    return ob_get_clean();
}

add_shortcode('jobs-filter', 'jobs_filter_att');


<?php 

function single_job_att($atts){
    $default = array(
        'link' => '',
        'name' => '',
        'desc' => '',
        'location' => '',
        'field' => '',
        'email' => '',
    );

    $a = shortcode_atts($default, $atts);

    ob_start();

   

    ?>
    
    <div class="single-block">
        <div class="jname"><?php echo $a['name']; ?></div>
        <div class="jdesc"><?php echo $a['desc']; ?></div>
        <div class="jlocation"><?php echo $a['location']; ?></div>
        <div class="jfield"><?php echo $a['field']; ?></div>
        <div class="jemail"><?php echo $a['email']; ?></div>
        <div class="link"> <a href="<?php echo $a['link'] ?>"> Link to Job Posting </a> </div>
    </div>

    <?php 
    
    
    return ob_get_clean();
}

add_shortcode('single-job', 'single_job_att');
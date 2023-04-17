<?php echo get_header(); ?>

<?php echo get_post_meta($post->ID, 'key', true); ?>
<!-- Standard Twenty Sixteen article header output -->    
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
            <span class="sticky-post"><?php _e( 'Featured', 'twentysixteen' ); ?>
        <?php endif; ?>
 
        <?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
    </header><!-- .entry-header -->
 
    <?php get_the_excerpt(); ?>
 
    <div class="entry-content">
     
        <!-- TYPES TIP: Custom call to Types function to render a custom field "Consultant Roles" --> 
        <!-- <p><strong>fName: <?php //echo get_post_meta($post->ID, 'fName', true); ?></strong></p>
        <p><strong>lName: <?php //echo get_post_meta($post->ID, 'lName', true); ?></strong></p>
      -->
      <?php 
        $fields = get_fields();
      ?>
      <div>
        <h1> <?php echo $fields['job-name'];  ?> </h1>
        <p> <?php echo $fields['location']; ?> </p>
        <p> <?php echo $fields['field']; ?> </p>
        <p> <?php echo $fields['description']; ?> </p>
      </div>
            <br>
            <br>
        <div>
            <?php echo do_shortcode("[applications-form]") ?>
        </div>
        <br>
        <br>
    </div>

    <script> 
        (function($) {
        
            //class="acf-field-643d422676d2a"
            var input = $( "#acf-field_643d422676d2a" );
            input.val( input.val() + <?php echo get_the_ID(); ?> );
            $('.acf-field-643d422676d2a').hide();

        })(jQuery);

    </script>

    <!-- Standard Twenty Sixteen content output -->   
    <?php
        /* translators: %s: Name of current post */
        the_content( sprintf(
            __( 'Continue reading<span class="screen-reader-text"> "%s"', 'twentysixteen' ),
            get_the_title()
        ) );

        wp_link_pages( array(
            'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentysixteen' ) . '',
            'after'       => '</div>',
            'link_before' => '<span>',
            'link_after'  => '',
            'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'twentysixteen' ) . ' %',
            'separator'   => '<span class="screen-reader-text">, ',
        ) );
    ?>

</article><!-- #post-## -->
<?php echo get_footer(); ?>
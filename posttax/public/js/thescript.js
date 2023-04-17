(function($) {
    
    $(document).ready(function(){

        
        $(document).on('click', ".cat-list_item" , function(){
            alert(wpAjax.ajaxUrl);
        // $('.cat-list_item').on('click', function() {
            $('.cat-list_item').removeClass('active');
            $(this).addClass('active');
            
            $.ajax({
                url: wpAjax.ajaxUrl,
                data: {'action' : 'get_projects',
                'category' : $(this).data("slug"),
                type: 'post',
            },
            success: function( response ) {
                console.log(response);
                $data=$.parseJSON(response);
                $('.projects-container').html($data[0]);
            },
            error: function(result){
                console.log(result);
            }
           
            
            })
            
        });
    })
})(jQuery);
jQuery(document).ready(function(){
    jQuery('#andlang-post-sort').on('change', function(){
        let post_sort_val = jQuery(this).val();

        let category_arr = [];
        jQuery('.filter-category .cat-input.active').each(function(){
            category_arr.push(jQuery(this).val());
        });

        jQuery.ajax({
            type : "POST",
            url : ajax.ajaxurl,
            data : {
                action: "sort_all_posts_columns", 
                column_key: post_sort_val,
                category_arr: category_arr
            },
            success: function(response) {
                let responseJSON = jQuery.parseJSON(response);

                jQuery('.load-more').show();
			
                jQuery('.posts-wrapper').html(responseJSON.result_html);
            }
         });
    })

    // pagination
    let page = 2;
    jQuery('.load-more').on('click', function(){

        let post_sort_val = jQuery('#andlang-post-sort').val();

        let category_arr = [];
        jQuery('.filter-category .cat-input.active').each(function(){
            category_arr.push(jQuery(this).val());
        });

        jQuery.ajax({
            type : "POST",
            url : ajax.ajaxurl,
            data : {
                action: "sort_all_posts_columns", 
                page: page,
                column_key: post_sort_val,
                category_arr: category_arr
            },
            success: function(response) {
                let responseJSON = jQuery.parseJSON(response);

                jQuery('.load-more').show();
				
                if(responseJSON.max <= 1 || responseJSON.max == page) {
                    jQuery('.load-more').hide();
                }
                jQuery('.posts-wrapper').append(responseJSON.result_html);
                page += 1;
            }
         });
    })

    // checkbox checked
        jQuery('.filter-category .cat-input').on('change', function(){
            if(this.checked) {
                jQuery(this).addClass('active');
            }else{
                jQuery(this).removeClass('active');
            }
        })

    // Category Filter
    jQuery('.filter-category-btn').on('click', function(){
        let category_arr = [];
        jQuery('.filter-category .cat-input.active').each(function(){
            category_arr.push(jQuery(this).val());
        });
        
        let post_sort_val = jQuery('#andlang-post-sort').val();
        jQuery.ajax({
            type : "POST",
            url : ajax.ajaxurl,
            data : {
                action: "sort_all_posts_columns",
                column_key: post_sort_val, 
                category_arr: category_arr
            },
            success: function(response) {
                let responseJSON = jQuery.parseJSON(response);

                jQuery('.load-more').show();
			
                jQuery('.posts-wrapper').html(responseJSON.result_html);
            }
         });

    });
});


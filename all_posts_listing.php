<?php
function list_all_posts(){ 
    ob_start();
    ?>
    <div class="andlang-all-posts-wrapper">
    <div class="posts-filter-wrapper">
    <form action="" method="POST">
      <select name="post-filter" id="andlang-post-sort">
        <option value="">Select</option>
        <option value="post_date_desc">Latest</option>
        <option value="post_date_asc">Oldest</option>
      </select>
      <div class="filter-button">Filter</div>
      <div class="filter-category">
        <?php
          $categories = get_categories();

          foreach( $categories as $category ) {
            $cat_slug = $category->slug;
            $cat_id = $category->cat_ID;
            echo '<input type="checkbox" name="'.$cat_slug.'" value="'.$cat_id.'" id="'.$cat_slug.'" class="cat-input" ><label for="'.$cat_slug.'">'.$category->name.'</label>';
           }

           echo '<div class="filter-category-btn">Apply</div>';
        ?>
      </div>
    </form>
    </div>
    <div class="posts-wrapper">
    <?php
       
    $property_per_page = 2;
    if ( get_query_var( 'paged' ) ) { 
        $paged = get_query_var( 'paged' ); 
    } elseif ( get_query_var( 'page' ) ) { 
        $paged = get_query_var( 'page' ); 
    } else { 
        $paged = 1; 
    }
    $args = array( 
    'post_type' => 'post', 
    'post_status'    => array( 'publish' ),
    'posts_per_page' => $property_per_page ? (int)$property_per_page : 6,
    'paged' => $paged,
    'orderby' => 'title',
    'order' => 'ASC'  
    );
    $the_query = new WP_Query( $args ); 
    $max_pages = $the_query->max_num_pages;
    if ( $the_query->have_posts() ) :
       while ( $the_query->have_posts() ) : $the_query->the_post(); 
        $post_id = get_the_id();
        $permalink = get_the_permalink();
        $post_title = get_the_title();
        $post_meta = get_post_meta($post_id);
        $thumbnail = get_the_post_thumbnail_url();
        $excerupt = get_the_excerpt();
        $content = get_the_content();
        if($content != ''){
          $trimmed_content = wp_trim_words($content, 20);
        }
        ?>
        <div class="post-detail">
          <?php if($thumbnail != ''){ ?><img src="<?php echo $thumbnail; ?>" class="post-image" /><?php } ?>
          <span class="post-date"><?php echo get_the_date( 'l F j, Y' ); ?></span>
          <h2 class="post-title"><a href="<?php echo $permalink; ?>"><?php echo $post_title; ?></a></h2>
          <p class="post-description">
            <?php echo $excerupt ?? $trimmed_content; ?>
          </p>
          <a href="<?php echo $permalink; ?>" class="read-more">Read More</a>
        </div>
        <?php endwhile;
  
    wp_reset_postdata(); 
    else: 
        echo 'No Jobs';
    endif; 
    ?>
    </div>
      <button class="load-more">Load More</button>
      </div>
    <?php
    return ob_get_clean();
  
}
add_shortcode('wp_sb_list_all_posts', 'list_all_posts');
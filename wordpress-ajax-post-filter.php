<?php
/*
Plugin Name:  Wordpress Ajax Post Filter
Plugin URI:   
Description:  
Version:      1.0
Author:       Mohit Kumar
Author URI:   
License:      
License URI:  
Text Domain:  wordpress-ajax-post-filter
Domain Path:  
*/
require_once dirname(__FILE__).'/all_posts_listing.php';


function enqueue(){
    // wp_enqueue_style( 'wprh-style', plugin_dir_url( __FILE__ ) . 'css/wprh-style.css'); 
    wp_enqueue_script( 'jQuery', 'https://code.jquery.com/jquery-3.6.0.js');    
    wp_register_script( 'wp_sb_common', plugin_dir_url( __FILE__ ) . 'assets/js/script.js', array('jquery') );

    wp_localize_script( 'wp_sb_common', 'ajax', array(
    'ajaxurl' 		=> admin_url( 'admin-ajax.php' ),
    ) );
    wp_enqueue_script( 'wp_sb_common' );

}
add_action( 'wp_enqueue_scripts', 'enqueue', 0 );

function sort_all_posts_columns(){

    $column_key_full = $_POST['column_key'];
    if($column_key_full == 'post_date_desc'){
      $column_key = 'post_date';
      $sort_order_key = 'DESC';
    }elseif($column_key_full == 'post_date_asc'){
      $column_key = 'post_date';
      $sort_order_key = 'ASC';
    }
    

    $category_array = $_POST['category_arr'];
    $cat = implode(",",$category_array);
    if(isset($_POST['page'])){
      $page = $_POST['page'];
    }else{
      $page = 1;
    }

    if($column_key == 'post_date'){
        $args = array( 
            'post_type' => 'post', 
            'posts_per_page' => 2,
            'post_status'    => array( 'publish' ),
            'paged' => $page,
            'cat' => array( $cat ),
            'orderby'   => array(
              'date' =>$sort_order_key
            )
            );
             
    }else{
        $args = array( 
            'post_type' => 'post', 
            'posts_per_page' => 2,
            'post_status'    => array( 'publish' ),
            'paged' => $page,
            'cat' => array( $cat ),
            'orderby' => 'title',
            'order' => 'ASC' 
            );
    }
    
    $the_query = new WP_Query( $args ); 
    $max_pages = $the_query->max_num_pages;
    $html = '';

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
        
        $html .= '<div class="post">
          <img src="'.$thumbnail.'" class="post-image" />
          <span class="post-date">'.get_the_date( "l F j, Y" ).'</span>
          <h2 class="post-title"><a href="'.$permalink.'">'.$post_title.'</a></h2>
          <p class="post-description">'.$excerupt ?? $trimmed_content.'</p>
          <a href="'.$permalink.'" class="read-more">Read More</a>
        </div>';
         endwhile;
  
    wp_reset_postdata(); 
    else: 
        $html .='No More Jobs';
    endif; 

    $result_json = [
        'result_html' => $html,
        'max' => $max_pages
      ];
      
    echo json_encode($result_json);
    exit();
}
add_action('wp_ajax_sort_all_posts_columns', 'sort_all_posts_columns');
add_action('wp_ajax_nopriv_sort_all_posts_columns', 'sort_all_posts_columns');

// // on plugin activation
register_activation_hook( __FILE__, 'wp_rh_on_activation');
function wp_rh_on_activation() {

}

// on plugin deactivation
register_deactivation_hook( __FILE__, 'wp_rh_on_deactivation' );
function wp_rh_on_deactivation() {
    
} 

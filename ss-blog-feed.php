<?php if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
          
/*
 * Plugin Name: SS Blog feed
 * Description: Shortcode for displaying blog feed
 * Version: 1.0.0
 * Author: The Generation AB
 * Text Domain: saras-blog-feed
 * Domain Path: /languages
 */

function add_plugin_style(){

    wp_enqueue_style( 'ss_style', plugins_url('/ss-blog-feed/css/ss-style.css', __DIR__ ), false, '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'add_plugin_style' );

if( ! is_admin() ) {
	add_shortcode('ss_blog_shortcode', 'ss_blog_feed');
}

function ss_blog_feed( $atts ) {

    $filtered_atts = shortcode_atts( array(
        'posts_per_page'        => 0,
        'offset'                => 0,
        'category_id'			=> false,

        ), $atts );


    $offset = $filtered_atts['offset'];

    $posts_per_page = $filtered_atts['posts_per_page'];

    $category_id = $filtered_atts['category_id'];

	$the_current_page = get_query_var( 'page', 1 );

    $args = array(
    	'post_type' 		=> 'post',
    	'post_status'		=> 'publish',
    	'page'				=> $the_current_page,
        'posts_per_page'    => $posts_per_page,
        'offset'            => $offset,
        'category__in'		=> $category_id,
        'orderby'			=> 'date', 
    );

    
    // Blog post 
    $blog_post = new WP_Query( $args );
    
    if( $blog_post->have_posts() ) :
  
    	while ( $blog_post->have_posts() ) : $blog_post->the_post(); ?>
			<li class="ss-blog-feed-li">
                <a href="<?php the_permalink();?>"><?php the_post_thumbnail();?></a>
            <div class="ss-content">    
			<small><?php the_time('F jS, Y') ?> <br></small>
					
				<h2 class="ss-blog-title"><?php the_title(); ?></h2>
				<?php the_excerpt(); ?>	
			</div>
            <div class="ss-blog-link">	
				<a class="ss-post-button" href="<?php the_permalink();?>">Read More</a> 
            </div>    
			</li>	
		<?php endwhile; 
    		  
    	// restore data
    	wp_reset_postdata(); ?>
     <?php else : ?>
     	<p><?php esc_html_e( 'Sorry, no posts' ); ?></p>
     <?php endif;

}

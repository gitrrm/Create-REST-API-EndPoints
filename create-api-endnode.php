<?php 
/**
 * Create a Rest API endnode for posts.
 * Add this functions to functions.php file and change your post type
 */

function register_articles_rest_route() {
    register_rest_route(
        'custom/v1', // Namespace
        '/articles', // Endpoint
        array(
            'methods'  => 'GET',
            'callback' => 'get_articles',
            'permission_callback' => '__return_true',
        )
    );
}
add_action( 'rest_api_init', 'register_articles_rest_route' );

function get_articles( $data ) {
    $args = array(
        'post_type'      => 'your_articles', // Change the post_type value according to your post type
        'posts_per_page' => -1, // Change this value as needed
        'post_status'    => 'publish',
    );

    $query = new WP_Query( $args );

    if ( $query->have_posts() ) {
        $posts = array();

        while ( $query->have_posts() ) {
            $query->the_post();

            $posts[] = array(
                'id'    => get_the_ID(),
                'title' => get_the_title(),
                'content' => get_the_content(),
                'excerpt' => get_the_excerpt(),
                'link'  => get_permalink(),
            );
        }

        wp_reset_postdata();

        return new WP_REST_Response( $posts, 200 );
    } else {
        return new WP_REST_Response( array(), 200 );
    }
}

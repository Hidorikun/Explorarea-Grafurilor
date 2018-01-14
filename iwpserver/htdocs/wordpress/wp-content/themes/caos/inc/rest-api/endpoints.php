<?php
/**
 * Grab latest post title by an author!
 *
 * @param array $data Options for the function.
 * @return string|null Post title for the latest,  * or null if none.
 */
function caos_metadata_endpoints( $data ) {

    $args = array( 'p' => $data['id'] );
    $the_query = new WP_Query( $args );
    if ( $the_query->have_posts() ) :

        while ( $the_query->have_posts() ) : $the_query->the_post();

            $output = '<ul>';

            $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
            if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
                $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
            }

            $time_string = sprintf( $time_string,
                esc_attr( get_the_date( 'c' ) ),
                esc_html( get_the_date() ),
                esc_attr( get_the_modified_date( 'c' ) ),
                esc_html( get_the_modified_date() )
            );

            $output .= '<li class="meta_date">' . $time_string . '</li>';


            // Hide category and tag text for pages.
                /* translators: used between list items, there is a space after the comma */
                $categories_list = get_the_category_list( esc_html__( ', ', 'caos' ) );
                if ( $categories_list ) {
                    $output .= sprintf( '<li class="meta_categories"><span class="cat-links">%1$s</span></li>', $categories_list ); // WPCS: XSS OK.
                }
                
                /* translators: used between list items, there is a space after the comma */
                $tags_list = get_the_tag_list( '', esc_html__( ', ', 'caos' ) );
                if ( $tags_list ) {
                    $output .= sprintf( '<li class="meta_tags"><span class="tags-links">' . esc_html__( 'Tagged %1$s', 'caos' ) . '</span></li>', $tags_list ); // WPCS: XSS OK.
                }

                $byline = sprintf(
                    esc_html_x( 'by %s', 'post author', 'caos' ),
                    '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
                );
                $output .= '<li class="meta_author">' . $byline . '</li>';
            

            $output .= '</ul>';

            return json_encode( $output );





        endwhile;

    else :
        return null;
    endif;


    


    // if ( empty( $posts ) ) {
    //     return null;
    // }

    // return $posts[0]->post_title;
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'caos/v1', '/metadata/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'caos_metadata_endpoints',
        /*'args' => array(
            'id' => array(
                'validate_callback' => 'is_numeric'
            ),
        ),*/
    ) );
} );
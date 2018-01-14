<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Caos
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('post-box'); ?> data-href="<?php the_permalink(); ?>">

	<div class="post-box-container">
        <?php 
        if ( is_sticky() ) {
             echo '<i class="fa fa-star"></i>';
         }
        ?>
		<?php
        $thumbnail_src = "";
		if ( has_post_thumbnail() ) :
			$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'caos_post');
			$thumbnail_src = $thumbnail_src[0];
		endif;
		?>
        <div class="post-box-image" style="background-image: url(<?php echo esc_url( $thumbnail_src ); ?>);"><span></span></div>
        <div class="post-box-text">
            <?php
            $categories_list = get_the_category_list( esc_html__( ', ', 'caos' ) );
			if ( $categories_list ) {
				printf( '<span class="post-box-meta">%1$s</span>', $categories_list ); // WPCS: XSS OK.
			}
            ?>
            <?php the_title( sprintf( '<h2 class="post-box-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
            <?php the_excerpt(); ?>
        </div><!-- .post-box-text -->

        <div class="post-complete"></div><!-- .post-complete -->

    </div><!-- .post-box-container -->

</article><!-- #post-## -->
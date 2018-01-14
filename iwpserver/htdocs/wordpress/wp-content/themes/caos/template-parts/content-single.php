<?php
/**
 * Template part for displaying single posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Caos
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php $thumbnail_src = ""; ?>
	<?php if ( has_post_thumbnail() ) : ?>
		<?php
		$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'caos_post');
		$thumbnail_src = $thumbnail_src[0];
		?>
		<div class="post-image" style="background-image: url(<?php echo esc_url( $thumbnail_src ); ?>);"><span></span></div>
    <?php endif; ?>
		

    <div class="post-content">

    	<?php the_title( '<h1 class="post-title">', '</h1>' ); ?>
    	<div class="metadata">
	        <?php caos_metadata(); ?>
	        <div class="clearfix"></div>
	    </div><!-- /metadata -->

    	<div class="entry">
			<?php the_content(); ?>
			<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'caos' ),
					'after'  => '</div>',
				) );
			?>
		</div><!-- .entry-content -->

		<div class="clearfix"></div>	    
	</div><!-- /post_content -->

</article><!-- #post-## -->


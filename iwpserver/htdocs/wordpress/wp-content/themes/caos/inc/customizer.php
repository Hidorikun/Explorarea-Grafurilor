<?php
/**
 * Caos Theme Customizer.
 *
 * @package Caos
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function caos_customize_register( $wp_customize ) {

	/**
	 * Control for the PRO buttons
	 */
	class caos_Pro_Version extends WP_Customize_Control{
		public function render_content()
		{
			$args = array(
				'a' => array(
					'href' => array(),
					'title' => array()
					),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
				);
			echo wp_kses( $this->label, $args );
		}
	}

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';




}
add_action( 'customize_register', 'caos_customize_register' );











/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function caos_customize_preview_js() {
	
	wp_register_script( 'caos_customizer_preview', get_template_directory_uri() . '/js/customizer-preview.js', array( 'customize-preview' ), '20151024', true );
	wp_localize_script( 'caos_customizer_preview', 'wp_customizer', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'theme_url' => get_template_directory_uri(),
		'site_name' => get_bloginfo( 'name' )
	));
	wp_enqueue_script( 'caos_customizer_preview' );

}
add_action( 'customize_preview_init', 'caos_customize_preview_js' );


/**
 * Load scripts on the Customizer not the Previewer (iframe)
 */
function caos_customize_js() {

	wp_enqueue_script( 'caos_customizer_top_buttons', get_template_directory_uri() . '/js/theme-customizer-top-buttons.js', array( 'jquery' ), true  );
	wp_localize_script( 'caos_customizer_top_buttons', 'topbtns', array(
			'pro' => esc_html__( 'More Themes', 'caos' )
	) );
	
	wp_enqueue_script( 'caos_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-controls' ), '20151024', true );

}
add_action( 'customize_controls_enqueue_scripts', 'caos_customize_js' );










/*
Sanitize Callbacks
*/

/**
 * Sanitize for post's categories
 */
function caos_sanitize_categories( $value ) {
    if ( ! array_key_exists( $value, caos_categories_ar() ) )
        $value = '';
    return $value;
}

/**
 * Sanitize return an non-negative Integer
 */
function caos_sanitize_integer( $value ) {
    return absint( $value );
}

/**
 * Sanitize return pro version text
 */
function caos_pro_version( $input ) {
    return $input;
}

/**
 * Sanitize Any
 */
function caos_sanitize_any( $input ) {
    return $input;
}

/**
 * Sanitize Text
 */
function caos_sanitize_text( $str ) {
	return sanitize_text_field( $str );
} 

/**
 * Sanitize Textarea
 */
function caos_sanitize_textarea( $text ) {
	return esc_textarea( $text );
}

/**
 * Sanitize URL
 */
function caos_sanitize_url( $url ) {
	return esc_url( $url );
}

/**
 * Sanitize Boolean
 */
function caos_sanitize_bool( $string ) {
	return (bool)$string;
} 

/**
 * Sanitize Text with html
 */
function caos_sanitize_text_html( $str ) {
	$args = array(
			    'a' => array(
			        'href' => array(),
			        'title' => array()
			    ),
			    'br' => array(),
			    'em' => array(),
			    'strong' => array(),
			    'span' => array(),
			);
	return wp_kses( $str, $args );
}

/**
 * Sanitize GPS Latitude and Longitud
 * http://stackoverflow.com/a/22007205
 */
function caos_sanitize_lat_long( $coords ) {
	if ( preg_match( '/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?),[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/', $coords ) ) {
	    return $coords;
	} else {
	    return 'error';
	}
} 



/**
 * Create the "PRO version" buttons
 */
if ( ! function_exists( 'caos_pro_btns' ) ){
	function caos_pro_btns( $args ){

		$wp_customize = $args['wp_customize'];
		$title = $args['title'];
		$label = $args['label'];
		if ( isset( $args['priority'] ) || array_key_exists( 'priority', $args ) ) {
			$priority = $args['priority'];
		}else{
			$priority = 120;
		}
		if ( isset( $args['panel'] ) || array_key_exists( 'panel', $args ) ) {
			$panel = $args['panel'];
		}else{
			$panel = '';
		}

		$section_id = sanitize_title( $title );

		$wp_customize->add_section( $section_id , array(
			'title'       => $title,
			'priority'    => $priority,
			'panel' => $panel,
		) );
		$wp_customize->add_setting( $section_id, array(
			'sanitize_callback' => 'caos_pro_version'
		) );
		$wp_customize->add_control( new caos_Pro_Version( $wp_customize, $section_id, array(
	        'section' => $section_id,
	        'label' => $label
		   )
		) );
	}
}//end if function_exists

/**
 * Display Text Control
 * Custom Control to display text
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	class caos_Display_Text_Control extends WP_Customize_Control {
		/**
		* Render the control's content.
		*/
		public function render_content() {

	        $wp_kses_args = array(
			    'a' => array(
			        'href' => array(),
			        'title' => array(),
			        'data-section' => array(),
			    ),
			    'br' => array(),
			    'em' => array(),
			    'strong' => array(),
			    'span' => array(),
			);
			$label = wp_kses( $this->label, $wp_kses_args );
	        ?>
			<p><?php echo $label; ?></p>		
		<?php
		}
	}
}



/*
* AJAX call to retreive an image URI by its ID
*/
add_action( 'wp_ajax_nopriv_caos_get_image_src', 'caos_get_image_src' );
add_action( 'wp_ajax_caos_get_image_src', 'caos_get_image_src' );

function caos_get_image_src() {
	$image_id = $_POST['image_id'];
	$image = wp_get_attachment_image_src( absint( $image_id ), 'full' );
	$image = $image[0];
	echo $image;
	die();
}

<?php
/**
 * Radiate Theme Customizer
 *
 * @package ThemeGrill
 * @subpackage Radiate
 * @since Radiate 1.0
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function radiate_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'radiate_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function radiate_customize_preview_js() {
	wp_enqueue_script( 'radiate_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'radiate_customize_preview_js' );

/*****************************************************************************************/

function radiate_register_theme_customizer( $wp_customize ) {
	// remove control
	$wp_customize->remove_control('blogdescription');

	// rename existing section
	$wp_customize->add_section( 'title_tagline' , array(
		'title' => __('Site Title', 'radiate' ),
		'priority' => 20
	) );

	class RADIATE_ADDITIONAL_Control extends WP_Customize_Control {
		public $type = 'textarea';

		public function render_content() {
			?>
			<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
			</label>
			<?php
		}
	}

	$wp_customize->add_setting(
		'radiate_color_scheme',
			array(
				'default'     	=> '#632E9B',
            'capability' => 'edit_theme_options',
				'sanitize_callback' => 'radiate_sanitize_hex_color',
				'sanitize_js_callback' => 'radiate_sanitize_escaping'
			)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'color_scheme',
			array(
				'label'      	=> __( 'Primary Color', 'radiate' ),
				'section'    	=> 'colors',
				'settings'   	=> 'radiate_color_scheme'
			)
		)
	);

	$wp_customize->add_section(
		'radiate_custom_css_section',
		array(
			'title'     => __( 'Custom CSS', 'radiate' ),
			'priority'  => 200
		)
	);

	$wp_customize->add_setting(
		'radiate_custom_css',
		array(
		'default'    =>  '',
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'wp_filter_nohtml_kses',
      'sanitize_js_callback' => 'wp_filter_nohtml_kses'
		)
	);

	$wp_customize->add_control(
		new RADIATE_ADDITIONAL_Control (
			$wp_customize,
			'radiate_custom_css',
			array(
				'label'    	=> __( 'Add your custom css here and design live! (for advanced users)' , 'radiate' ),
				'section'   => 'radiate_custom_css_section',
				'settings'  => 'radiate_custom_css'
			)
		)
	);

	$wp_customize->add_section(
		'radiate_featured_section',
		array(
			'title'     => __( 'Front Page Featured Section', 'radiate' ),
			'priority'  => 220
		)
	);

	$wp_customize->add_setting(
		'page-setting-one',
		array(
         'capability' => 'edit_theme_options',
			'sanitize_callback' => 'radiate_sanitize_integer'
		)
	);
	$wp_customize->add_setting(
		'page-setting-two',
		array(
         'capability' => 'edit_theme_options',
			'sanitize_callback' => 'radiate_sanitize_integer'
		)
	);
	$wp_customize->add_setting(
		'page-setting-three',
		array(
         'capability' => 'edit_theme_options',
			'sanitize_callback' => 'radiate_sanitize_integer'
		)
	);

	$wp_customize->add_control(
		'page-setting-one',
			array(
			'type' => 'dropdown-pages',
			'label' => __( 'First featured page', 'radiate' ),
			'section' => 'radiate_featured_section',
		)
	);
	$wp_customize->add_control(
		'page-setting-two',
			array(
			'type' => 'dropdown-pages',
			'label' => __( 'Second featured page', 'radiate' ),
			'section' => 'radiate_featured_section',
		)
	);
	$wp_customize->add_control(
		'page-setting-three',
			array(
			'type' => 'dropdown-pages',
			'label' => __( 'Third featured page', 'radiate' ),
			'section' => 'radiate_featured_section',
		)
	);

	// Author Bio
   $wp_customize->add_section(
      'radiate_author_bio',
      array(
         'title'     => __( 'Author Bio', 'radiate' ),
         'priority'  => 250
      )
   );

   $wp_customize->add_setting(
      'radiate_author_bio_show',
         array(
         'default' => 0,
         'capability' => 'edit_theme_options',
         'sanitize_callback' => 'radiate_checkbox_sanitize'
      )
   );

   $wp_customize->add_control(
      'radiate_author_bio_show',
         array(
         'type' => 'checkbox',
         'label' => __('Check to enable the Author Bio in the single post page.', 'radiate'),
         'section' => 'radiate_author_bio',
         'settings' => 'radiate_author_bio_show'
      )
   );

   // Hide Search Icon
   $wp_customize->add_section(
      'radiate_search_icon',
      array(
         'title'     => __( 'Header Search Icon', 'radiate' ),
         'priority'  => 270
      )
   );

   $wp_customize->add_setting(
      'radiate_header_search_hide',
        array(
         'default' => 0,
         'capability' => 'edit_theme_options',
         'sanitize_callback' => 'radiate_checkbox_sanitize'
      )
   );

   $wp_customize->add_control(
      'radiate_header_search_hide',
         array(
         'type' => 'checkbox',
         'label' => __('Check to hide Header Search Icon.', 'radiate'),
         'section' => 'radiate_search_icon',
         'settings' => 'radiate_header_search_hide'
      )
   );

	function radiate_sanitize_hex_color( $color ) {
		if ( $unhashed = sanitize_hex_color_no_hash( $color ) )
			return '#' . $unhashed;

		return $color;
	}

	function radiate_sanitize_integer( $input ) {
    	if( is_numeric( $input ) ) {
        return intval( $input );
   	}
	}

	function radiate_sanitize_escaping( $input) {
		$input = esc_attr( $input);
		return $input;
	}

   function radiate_checkbox_sanitize($input) {
      if ( $input == 1 ) {
         return 1;
      } else {
         return '';
      }
   }

}
add_action( 'customize_register', 'radiate_register_theme_customizer' );


function radiate_customizer_css() {
	$primary_color =  get_theme_mod( 'radiate_color_scheme' );
	if( $primary_color && $primary_color != '#632e9b') {
		$customizer_css = ' blockquote{border-color:#EAEAEA #EAEAEA #EAEAEA '.$primary_color.'}.site-title a:hover,a{color:'.$primary_color.'}#masthead .search-form,.main-navigation a:hover,.main-navigation ul li ul li a:hover,.main-navigation ul li ul li:hover>a,.main-navigation ul li.current-menu-ancestor a,.main-navigation ul li.current-menu-item a,.main-navigation ul li.current-menu-item ul li a:hover,.main-navigation ul li.current_page_ancestor a,.main-navigation ul li.current_page_item a,.main-navigation ul li:hover>a{background-color:'.$primary_color.'}.header-search-icon:before{color:'.$primary_color.'}button,input[type=button],input[type=reset],input[type=submit]{background-color:'.$primary_color.'}#content .comments-area a.comment-edit-link:hover,#content .comments-area a.comment-permalink:hover,#content .comments-area article header cite a:hover,#content .entry-meta span a:hover,#content .entry-title a:hover,.comment .comment-reply-link:hover,.comments-area .comment-author-link a:hover,.entry-meta span:hover,.site-header .menu-toggle,.site-header .menu-toggle:hover{color:'.$primary_color.'}.main-small-navigation ul li ul li a:hover,.main-small-navigation ul li:hover,.main-small-navigation ul li a:hover,.main-small-navigation ul li ul li:hover>a,.main-small-navigation ul > .current_page_item, .main-small-navigation ul > .current-menu-item,.main-small-navigation ul li.current-menu-item ul li a:hover{background-color:'.$primary_color.'}#featured_pages a.more-link:hover{border-color:'.$primary_color.';color:'.$primary_color.'}a#back-top:before{background-color:'.$primary_color.'}a#scroll-up span{color:'.$primary_color.'}';
	?>
	<style type="text/css"><?php echo $customizer_css; ?></style>
	<?php
	}
	?>
	<style type="text/css"><?php echo trim( get_theme_mod( 'radiate_custom_css' ) ); ?></style>
	<?php
}
add_action( 'wp_head', 'radiate_customizer_css' );

/*****************************************************************************************/

/**
 * Enqueue scripts for customizer
 */
function radiate_customizer_js() {
   wp_enqueue_script( 'radiate_customizer_script', get_template_directory_uri() . '/js/radiate_customizer.js', array("jquery"), 'false', true  );

   wp_localize_script( 'radiate_customizer_script', 'radiate_customizer_obj', array(

      'info' => __( 'Theme Info', 'radiate' ),
      'pro' => __('View PRO version','radiate')

   ) );
}
add_action( 'customize_controls_enqueue_scripts', 'radiate_customizer_js' );
?>
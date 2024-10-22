<?php
/**
 * OnePress functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package OnePress
 */

if ( ! function_exists( 'onepress_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function onepress_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on OnePress, use a find and replace
	 * to change 'onepress' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'onepress', get_template_directory() . '/languages' );

	/*
	 * Add default posts and comments RSS feed links to head.
	 */
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/**
	 * Excerpt for page
	 */
	add_post_type_support( 'page', 'excerpt' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'onepress-blog-small', 300, 150, true );
	add_image_size( 'onepress-small', 480, 300, true );
	add_image_size( 'onepress-medium', 640, 400, true );

	/*
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary'      => esc_html__( 'Primary Menu', 'onepress' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * This theme styles the visual editor to resemble the theme style.
	 */
	add_editor_style( array( 'assets/css/editor-style.css', onepress_fonts_url() ) );
}
endif; // onepress_setup
add_action( 'after_setup_theme', 'onepress_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function onepress_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'onepress_content_width', 800 );
}
add_action( 'after_setup_theme', 'onepress_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function onepress_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'onepress' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

}
add_action( 'widgets_init', 'onepress_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function onepress_scripts() {
	wp_enqueue_style( 'onepress-fonts', onepress_fonts_url(), array(), null );
	wp_enqueue_style( 'onepress-animate', get_template_directory_uri() .'/assets/css/animate.min.css', array(), '1.0.0' );
	wp_enqueue_style( 'onepress-fa', get_template_directory_uri() .'/assets/css/font-awesome.min.css', array(), '4.4.0' );
	wp_enqueue_style( 'onepress-bootstrap', get_template_directory_uri() .'/assets/css/bootstrap.min.css', array(), '4.0.0' );
    wp_enqueue_style( 'onepress-style', get_template_directory_uri().'/style.css' );

	wp_enqueue_script('jquery');
	wp_enqueue_script( 'onepress-js-plugins', get_template_directory_uri() . '/assets/js/plugins.js', array(), '1.0.0', true );
	wp_enqueue_script( 'onepress-js-bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array(), '4.0.0', true );
	wp_enqueue_script( 'onepress-theme', get_template_directory_uri() . '/assets/js/theme.js', array(), '20120206', true );
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Animation from settings
	$onepress_js_settings = array( 
		'onepress_disable_animation' => get_theme_mod( 'onepress_animation_disable' ),
		'onepress_disable_sticky_header' => get_theme_mod( 'onepress_sticky_header_disable' )
	);
	wp_localize_script('jquery','onepress_js_settings', $onepress_js_settings);

}
add_action( 'wp_enqueue_scripts', 'onepress_scripts' );


if ( ! function_exists( 'onepress_fonts_url' ) ) :
/**
 * Register default Google fonts
 */
function onepress_fonts_url() {
    $fonts_url = '';

 	/* Translators: If there are characters in your language that are not
    * supported by Open Sans, translate this to 'off'. Do not translate
    * into your own language.
    */
    $open_sans = _x( 'on', 'Open Sans font: on or off', 'onepress' );

    /* Translators: If there are characters in your language that are not
    * supported by Raleway, translate this to 'off'. Do not translate
    * into your own language.
    */
    $raleway = _x( 'on', 'Raleway font: on or off', 'onepress' );

    if ( 'off' !== $raleway || 'off' !== $open_sans ) {
        $font_families = array();

        if ( 'off' !== $raleway ) {
            $font_families[] = 'Raleway:400,500,600,700,300,100,800,900';
        }

        if ( 'off' !== $open_sans ) {
            $font_families[] = 'Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic';
        }

        $query_args = array(
            'family' => urlencode( implode( '|', $font_families ) ),
            'subset' => urlencode( 'latin,latin-ext' ),
        );

        $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
    }

    return esc_url_raw( $fonts_url );
}
endif;

if ( ! function_exists( 'onepress_admin_scripts' ) ) :
/**
 * Enqueue scripts for admin page only: Theme info page
 */
function onepress_admin_scripts( $hook ) {
	if ( $hook === 'widgets.php' || $hook === 'appearance_page_ft_onepress'  ) {
		wp_enqueue_style('onepress-admin-css', get_template_directory_uri() . '/assets/css/admin.css');
	}
}
endif;
add_action('admin_enqueue_scripts', 'onepress_admin_scripts');


if ( ! function_exists( 'onepress_register_required_plugins' ) ) :
/**
 * Register the required plugins for this theme.
 *
 * In this example, we register five plugins:
 * - one included with the TGMPA library
 * - two from an external source, one from an arbitrary source, one from a GitHub repository
 * - two from the .org repo, where one demonstrates the use of the `is_callable` argument
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function onepress_register_required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(
		 array(
			'name'               => 'Contact Form 7', // The plugin name.
			'slug'               => 'contact-form-7', // The plugin slug (typically the folder name).
			'source'             => '', // The plugin source.
			'required'           => false, // If false, the plugin is only 'recommended' instead of required.
			'version'            => '4.2', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
			'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
			'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
			'external_url'       => '', // If set, overrides default API URL and points to an external URL.
        )
	);

	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
	$config = array(
		'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'themes.php',            // Parent menu slug.
		'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.

		'strings'      => array(
			'page_title'                      => esc_html__( 'Install Required Plugins', 'onepress' ),
			'menu_title'                      => esc_html__( 'Install Plugins', 'onepress' ),
			'installing'                      => esc_html__( 'Installing Plugin: %s', 'onepress' ), // %s = plugin name.
			'oops'                            => esc_html__( 'Something went wrong with the plugin API.', 'onepress' ),
			'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'onepress' ), // %1$s = plugin name(s).
			'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'onepress' ), // %1$s = plugin name(s).
			'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %1$s plugin.', 'Sorry, but you do not have the correct permissions to install the %1$s plugins.', 'onepress' ), // %1$s = plugin name(s).
			'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'onepress' ), // %1$s = plugin name(s).
			'notice_ask_to_update_maybe'      => _n_noop( 'There is an update available for: %1$s.', 'There are updates available for the following plugins: %1$s.', 'onepress' ), // %1$s = plugin name(s).
			'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %1$s plugin.', 'Sorry, but you do not have the correct permissions to update the %1$s plugins.', 'onepress' ), // %1$s = plugin name(s).
			'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'onepress' ), // %1$s = plugin name(s).
			'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'onepress' ), // %1$s = plugin name(s).
			'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %1$s plugin.', 'Sorry, but you do not have the correct permissions to activate the %1$s plugins.', 'onepress' ), // %1$s = plugin name(s).
			'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'onepress' ),
			'update_link' 					  => _n_noop( 'Begin updating plugin', 'Begin updating plugins', 'onepress' ),
			'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins', 'onepress' ),
			'return'                          => esc_html__( 'Return to Required Plugins Installer', 'onepress' ),
			'plugin_activated'                => esc_html__( 'Plugin activated successfully.', 'onepress' ),
			'activated_successfully'          => esc_html__( 'The following plugin was activated successfully:', 'onepress' ),
			'plugin_already_active'           => esc_html__( 'No action taken. Plugin %1$s was already active.', 'onepress' ),  // %1$s = plugin name(s).
			'plugin_needs_higher_version'     => esc_html__( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'onepress' ),  // %1$s = plugin name(s).
			'complete'                        => esc_html__( 'All plugins installed and activated successfully. %1$s', 'onepress' ), // %s = dashboard link.
			'contact_admin'                   => esc_html__( 'Please contact the administrator of this site for help.', 'onepress' ),
			'nag_type'                        => 'updated', // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
		),

	);

	tgmpa( $plugins, $config );
}

endif;
add_action( 'tgmpa_register', 'onepress_register_required_plugins' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load TGM class plugin activation.
 */
require get_template_directory() . '/inc/tgm-plugin-activation.php';

/**
 * Add theme info page
 */
require get_template_directory() . '/inc/dashboard.php';

/**
 * Get media from a variable
 *
 * @param array $media
 * @return false|string
 */
if ( ! function_exists( 'onepress_get_media_url' ) ) {
	function onepress_get_media_url($media = array())
	{
		$media = wp_parse_args($media, array('url' => '', 'id' => ''));
		$url = '';
		if ($media['id'] != '') {
			$url = wp_get_attachment_url($media['id']);
		}
		if ($url == '' && $media['url'] != '') {
			$url = $media['url'];
		}
		return $url;
	}
}




/**
 * Get theme actions required
 *
 * @return array|mixed|void
 */
function onepress_get_actions_required( ) {

    $actions = array();
    $front_page = get_option( 'page_on_front' );
    $actions['page_on_front'] = 'dismiss';
    $actions['page_template'] = 'dismiss';
    if ( $front_page <= 0  ) {
        $actions['page_on_front'] = 'active';
        $actions['page_template'] = 'active';

    } else {
        if ( get_post_meta( $front_page, '_wp_page_template', true ) == 'template-frontpage.php' ) {
            $actions['page_template'] = 'dismiss';
        } else {
            $actions['page_template'] = 'active';
        }
    }

    $actions = apply_filters( 'onepress_get_actions_required', $actions );
    $actions_dismiss =  get_option( 'onepress_actions_dismiss' );

    if (  $actions_dismiss && is_array( $actions_dismiss ) ) {
        foreach ( $actions_dismiss as $k => $v ) {
            if ( isset ( $actions[ $k ] ) ) {
                $actions[ $k ] = 'dismiss';
            }
        }
    }

    return $actions;
}


add_action('switch_theme', 'onepress_reset_actions_required');
function onepress_reset_actions_required () {
    delete_option('onepress_actions_dismiss');
}

<?php

	wp_enqueue_script( 'jquery' );
	
	//HTML5 Shiv ==============================================
	wp_enqueue_script( 'html5shiv', get_template_directory_uri() . '/js/html5shiv.js', array(), '3.7.3', true );
	//=================================================================

	//History ========================================================
	wp_enqueue_script( 'history', get_template_directory_uri() . '/js/jquery.history.js', array(), '1.8', true );
	//=================================================================

	//Easing ==========================================================
	wp_enqueue_script( 'easing', get_template_directory_uri() . '/js/jquery.easing.1.3.js',array( 'jquery' ), '1.3', true );
	//=================================================================

	//Modernizr Plugin ================================================
	wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/js/modernizr.custom.67069.js', '2.8.3', true );
	//=================================================================
	
	//Pace  ===========================================================
	wp_enqueue_script( 'pace', get_template_directory_uri() . '/js/pace.js', array(), '0.2.0', true);
	//=================================================================
	
	//Bootstrap JS ========================================
	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.js', array(), '3.3.5', true);
	//=================================================================
	
	//Comment Reply ===================================================
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	//=================================================================


	
	//Customs Scripts =================================================
	wp_enqueue_script( 'caos_theme-custom', get_template_directory_uri() . '/js/script.js', array( 'jquery', 'bootstrap', 'history' ), '1.0', true );
	//=================================================================

?>
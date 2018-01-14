jQuery(document).ready(function($) {

	//Anomation at load -----------------
	Pace.on('done', function(event) {
		
	});//Pace

	var History = window.History; // Note: Using a capital H instead of a lower h
	var State = History.getState();

	// Bind to StateChange Event
    History.Adapter.bind(window,'statechange',function(){ // Note: We are using statechange instead of popstate
        State = History.getState(); // Note: We are using History.getState() instead of event.state
        console.log(State);
        animate_post(State.data.post_id);
    });




	$("body").on('click', '.post-box:not(.post-open)', function(event) {
		event.preventDefault();

		var $post = $(this);
		var post_title = $post.find('.post-box-title a').text();
		var post_href = $post.attr('data-href');
		
		var $link = jQuery( 'link[rel="https://api.w.org/"]' );
		var rest_api_plugin = $('body').attr('data-rest-api');//if rest plugin is enable
		if ($link.length && rest_api_plugin == "true") {
			// Bind to StateChange Event
		    History.pushState({state: 'ajax-post', post_id : $post.attr('id'), action: 'load-post'}, post_title, post_href);
		}else{//if REST API is present
			// similar behavior as clicking on a link
			window.location.href = post_href;
		};
	
	});// on click



	$("body").on('click', '.post-box .post-close-btn', function(event) {
		event.preventDefault();
		/* Act on the event */

		var $post = $(this).parents('.post');
		var title = $('body').attr('data-name');
		var href = $('body').attr('data-url');
		
		var $link = jQuery( 'link[rel="https://api.w.org/"]' );
		var rest_api_plugin = $('body').attr('data-rest-api');//if rest plugin is enable
		if ($link.length && rest_api_plugin == "true") {
			// Bind to StateChange Event
		    History.pushState({state: 'ajax-post', post_id : $post.attr('id'), action: 'close-post'}, title, href);
		}else{//if REST API is present
			// similar behavior as clicking on a link
			window.location.href = href;
		};

	});//on click



	function animate_post(post_id){


		/* Act on the event */
		var $post = $('#' + post_id);
		var $post_container = $post.find('.post-box-container');
		var box_position = $post.get(0).getBoundingClientRect();
		var box_position_left = box_position.left;
		var box_width = $post.width();
		var box_height = $post.outerHeight();

		if ($post.hasClass('post-open')) {
			/*
			* Close Post
			===========================*/

			//Scroll must be at top to avoid flickering
			$post_container.animate({ scrollTop: 0 }, "fast", function(){

				$post.toggleClass('post-open');
				$('body').toggleClass('body-open');
				$post_container.toggleClass('open');
				$('body').css('overflow', 'visible');

				//Hide post-complete
				setTimeout(function(){
					$post.find('.post-complete').css({
						height: 0,
						overflow: 'hidden',
						padding: 0
					});
				}, 300);


				setTimeout(function(){

					//If the body has scroll will cause a flickering for the scroll bar
					if ( $('body').outerHeight() > $(window).height() && $(window).width() > 768) {
						//box_position_left = box_position_left - 8;
						box_position_left = box_position_left;
					};

					//Animate post container to fullscreen
					$post_container.animate({
						top: box_position.top,
						left: box_position_left,
						zIndex: 1,
						width: box_width,
						//height: box_height
					}, 400, 'easeInOutExpo', function(){

						$post.find('.post-box-image').animate({
							paddingBottom: '141.5%'
						}, 600, 'easeOutExpo', function(){

							//Once in position we can add position relative to the div
							$post_container.css({
								top: 0,
								left: 0,
								position: 'relative',
								width: '100%',
								height: '100%',
								zIndex: 1
							});

							// //Hide all post but current
							$post.find('.post-box-text').toggleClass('ql_animate');
							setTimeout(function(){
								$('.post-box').not($post).each(function(){
							        $(this).toggleClass('ql_animate');
							    });
						    }, 300);

							// //Add hover shadow
							$post.find('.post-box-image').toggleClass('ql_animate');

						});//post-box-image

					});//post-box-container
				}, 500);

			});//scroll to top


		}else{//if hasClass(post-open)};
			/*
			* Load Post
			===========================*/
		
			var $link = jQuery( 'link[rel="https://api.w.org/"]' );
			var api_root = $link.attr( 'href' );
			var post_id = $post.attr( 'id' );
			post_id = post_id.replace('post-', '');

			$.ajax({
				url: api_root + 'wp/v2/posts/' + post_id,
				type: 'GET',
				dataType: 'json',
			})
			.done(function(data) {

				var post_title = data.title.rendered;
				var post_entry = data.content.rendered;

				// Get metadata with an API call
				$.ajax({
					url: api_root + 'caos/v1/metadata/' + post_id,
					type: 'GET',
					dataType: 'json',
				})
				.done(function(metadata_data) {
					//Parse metadata
					var post_metadata = jQuery.parseJSON( metadata_data );

					if ($post.find('.post-complete').text() == '') {
						// Add Close button
						$post.find(".post-complete").append('<button class="post-close-btn"><i class="fa fa-close"></i></button>');

						//Add title
						$post.find(".post-complete").append('<h2 class="post-title">' + post_title + '</h2>');

						//Add metadata
						$post.find(".post-complete").append('<div class="metadata">' + post_metadata + '<div class="clearfix"></div></div>');
						
						// Add entry
						$post.find(".post-complete").append('<div class="entry">' + post_entry + '</div>');

					};
					


					
					/* Start Post animation
					*/
					//Hide all post but current
					$('.post-box').not($post).each(function(){
				        $(this).toggleClass('ql_animate');
				    });
				    //Hide post text
				    setTimeout(function(){
						$post.find('.post-box-text').toggleClass('ql_animate');
				    }, 300);

					//Remove hover shadow
					$post.find('.post-box-image').toggleClass('ql_animate');

				    setTimeout(function(){

				    	$post.find('.post-box-image').animate({
							paddingBottom: '35%'
						}, 600, 'easeOutExpo', function(){

							//Change container to position fixed
							$post_container.css({
								top: box_position.top,
								left: box_position.left,
								position: 'fixed',
								width: box_width,
								height: box_height,
								zIndex: 5
							});

							//Animate post container to fullscreen
							$post_container.animate({
								top: 0,
								left: 0,
								width: '100%',
								height: '100%'
							}, 400, 'easeInOutExpo', function(){

								//Make post-complete visible
								$post.find('.post-complete').css({
									height: 'auto',
									overflow: 'visible',
									padding: '3.75rem 20%'
								});

								//Show post
								$post.toggleClass('post-open');
								$('body').toggleClass('body-open');
								$post_container.toggleClass('open');
								$('body').css('overflow', 'hidden');

							});//post-box-container

						});//post-box-image
				    	
					}, 500);


			    });// done ajax metadata

			})//done ajax get post
			.fail(function() {
				console.log("error");
			});// Ajax

		}//if hassClass

		

	}//animate_post









	$(".ql_scroll_top").click(function() {
	  $("html, body").animate({ scrollTop: 0 }, "slow");
	  return false;
	});


	
	$('.dropdown-toggle').dropdown();
	$('*[data-toggle="tooltip"]').tooltip();
		
});
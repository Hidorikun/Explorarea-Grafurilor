jQuery(document).ready(function($) {
	var $destination = $("#customize-info .accordion-section-title");
 	$destination.prepend('<a style="width: 80%; margin: 10px auto; display: block; text-align: center;" href="https://www.quemalabs.com/" class="button" target="_blank">{pro}</a>'.replace( '{pro}', topbtns.pro ) );
});
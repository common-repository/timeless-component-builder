/**
 * Load media uploader on pages with our custom metabox
 */
jQuery(document).ready(function($) {
	"use strict";

	// Instantiates the variable that holds the media library frame.
	var metaImageFrame;

	// Runs when the media button is clicked.
	// $("body").click(function(e) {

	$("body").on("click", "button.media-uplooad-btn", function(e) {
		e.preventDefault();

		var $btn = $(this);

		// Sets up the media library frame
		metaImageFrame = wp.media.frames.metaImageFrame = wp.media({
			title: "Upload File",
			button: { text: "Use this file" },
		});

		// Runs when an image is selected.
		metaImageFrame.on("select", function() {
			// Grabs the attachment selection and creates a JSON representation of the model.
			var media_attachment = metaImageFrame
				.state()
				.get("selection")
				.first()
				.toJSON();

			$btn.parents(".single-field-wrapper")
				.find("input")
				.attr("value", media_attachment.url);

			$btn.parents(".single-field-wrapper")
				.find(".selected-file")
				.html(
					media_attachment.url +
						"<button class='reset-file-field' type='button'>X</button>",
				);
			// .text(media_attachment.url);
		});

		// Opens the media library frame.
		metaImageFrame.open();
	});

	/**
	 * Repeater
	 */
	// #post
	$(document).ready(function() {
		$("#post").repeater({
			initEmpty: false,
			show: function() {
				$(this).slideDown();
				$(this)
					.find(".selected-file")
					.html("");
			},

			hide: function(deleteElement) {
				if (confirm("Are you sure you want to delete this element?")) {
					$(this).slideUp(deleteElement);
				}
			},

			isFirstItemUndeletable: false,
		});
	});

	/**
	 *
	 * Remove Selected file from input
	 */

	$("body").on("click", "button.reset-file-field", function(e) {
		e.preventDefault();
		var $btn = $(this);
		$btn.parents(".single-field-wrapper")
			.find("input")
			.attr("value", "");

		$btn.parents(".single-field-wrapper")
			.find(".selected-file")
			.html("");
	});
});

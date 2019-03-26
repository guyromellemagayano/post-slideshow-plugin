(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

  $(function () {
    var changeSlide = function(index) {
      $('.post-slideshow-slide').addClass('post-slideshow-hide');
      $('.post-slideshow-slide[data-index="' + index + '"]').removeClass('post-slideshow-hide');
    }

    $('.post-slideshow-nav').on('click', function() {
      var index, state, url;

      index = $(this).attr('data-open');
      state = { 'post_slide' : index };

      if(index==0) {
        $('.post-slideshow-begin-slideshow').removeClass('post-slideshow-hide');
        $('.post-slideshow-overview').removeClass('post-slideshow-hide');

        url = window.location.href;
        url = url.slice(0, url.indexOf('?'));

        history.pushState(state, '', url);
      } else {
        history.pushState(state, '', '?post-slide=' + index);
      }

      if (PostSlideshow.options.force_reload == true) {
        window.location.href = window.location.href;
      } else {
        changeSlide(index);
      }

    });

    $('.post-slideshow-begin-slideshow').on('click', function() {
      var index, state;

      index = $(this).attr('data-open');
      state = { 'post_slide' : index };
      history.pushState(state, '', '?post-slide=' + index);

      if (PostSlideshow.options.force_reload == true) {
        window.location.href = window.location.href;
      } else {
        changeSlide(index);
      }
    });
  });

})( jQuery );

($ => {
   /**
    * All of the code for your admin-facing JavaScript source
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

    var viewData;
    viewData = {};

    $(function() {
      $('button.post-slideshow-add-slide').on('click', function () {
        var output, index;

        index = $('.post-slideshow-slide').length + 1;

        output = '<div class="wrap post-slideshow-slide post-slideshow-add-slide">';
        output += '<div class="meta-box-sortables">';
        output += '<div class="postbox ui-sortable-handle">';
        output += '<h2 class="hndle ui-sortable" id="slide-preview"><i class="fas fa-ellipsis-v"></i><span class="post-slideshow-imgholder"></span><strong data-update="post-slideshow-title">New Slide</strong></h2>';
        output += '<div class="inside">';
        output += '<div class="form-group">';
        output += '<label for="slide-title"><h4>Slide Title</h4></label>';
        output += '<input type="text" class="large-text slide-title widefat" name="post_slideshow_title[]" value="" data-bind="post-slideshow-title" />';
        output += '</div>';
        output += '<div class="form-group">';
        output += '<label for="slide-featured-image"><h4>Featured Image</h4></label>';
        output += '<button type="button" class="button post-slideshow-add-featured-image">Attach Featured Image</button>';
        output += '<input type="hidden" class="slide-featured-image" name="post_slideshow_featured_image[]" />';
        output += '</div>';
        output += '<div class="form-group">';
        output += '<label for="slide-description"><h4>Slide Description</h4></label>';
        output += '<textarea class="slide-description widefat" id="slide_description_' + index + '" name="post_slideshow_description[]"></textarea>';
        output += '</div>';
        output += '</div>';
        output += '</div>';
        output += '</div>';
        output += '</div>';

        if (index == 1) {
          $(this).after(output);
        } else {
          $('.post-slideshow-slide').last().after(output);
        }

        var settings = {
          tinymce: true,
          media_buttons: false,
          height: 300,
          quicktags: {
            buttons: 'strong,em,link,ul,ol,li',
            media_buttons: true,
          }
        };
        wp.editor.initialize('slide_description_' + index, settings);
      });

      $(document).on('click', 'button.post-slideshow-add-featured-image', function () {
        var frame, container;

        container = $(this).parent();

        // New media frame
        frame = wp.media({
          title: 'Select of Upload Media',
          multiple: false,
          frame: 'post',
          state: 'insert'
        });

        frame.on('insert', function () {
          var state, selection, attachment, display, imgurl, preview;

          state = frame.state();
          selection = state.get('selection');
          attachment = selection.first();
          display = state.display(attachment).toJSON();
          attachment = attachment.toJSON();
          imgurl = attachment.sizes['medium'].url;

          container.find('.slide-featured-image').val(attachment.id);
          container.find('button.post-slideshow-add-featured-image').text('Change Featured Image');

          preview = '<div class="post-slideshow-img-preview">';
          preview += '<div class="post-slideshow-img-preview--inner">';
          preview += '<span class="dashicons dashicons-no delete-post-slideshow-img"></span>';
          preview += '<img src="' + imgurl + '" alt="" />';
          preview += '</div>';
          preview += '</div>';

          container.find('.post-slideshow-img-preview').remove();
          container.find('button.post-slideshow-add-featured-image').after(preview);

          frame.close();
        });

        frame.open();

        return false;
      });

      $(document).on('click', '.delete-post-slideshow-img', function () {
        var container;

        container = $(this).parent().parent().parent().parent();
        container.find('.slide-featured-image').val('');
        container.find('.post-slideshow-img-preview').fadeOut(350, function () {
          $(this).remove();
        });
        container.find('.post-slideshow-add-featured-image').text('Attach Featured Image');
      });

      // Update handle title while inputting slide title
      $(document).on('keyup', '[data-bind]', function () {
        var $this;
        var wrap;

        $this = $(this);
        wrap = $this.closest('.wrap');

        updateViewData($this.data('bind'), $this.val());
        updateDisplay(wrap);

        function updateViewData(key, value) {
          viewData[key] = value;
        }

        function updateDisplay(target) {
          var updateEls;
          updateEls = $(target).find('[data-update]');

          updateEls.each(function () {
            $(this).html(viewData[$(this).data('update')]);
          });
        }
      });
    });

})(jQuery);

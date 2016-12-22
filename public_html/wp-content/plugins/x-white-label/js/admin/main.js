// =============================================================================
// JS/SRC/ADMIN/MAIN.JS
// -----------------------------------------------------------------------------
// Plugin admin scripts.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Plugin Specific Functionality
//   02. Global Plugin Functionality
//   03. Media Uploader
// =============================================================================

// Plugin Specific Functionality
// =============================================================================

jQuery(document).ready(function($) {

  //
  // Show/hide settings.
  //

  var $pluginEnable   = $('#x_white_label_enable');
  var $pluginSettings = $('#meta-box-settings');

  $pluginEnable.change(function() {
    if ( $pluginEnable.is(':checked') ) {
      $pluginSettings.show();
    } else {
      $pluginSettings.hide();
    }
  });

});



// Global Plugin Functionality
// =============================================================================

jQuery(document).ready(function($) {

  //
  // Accordion.
  //

  $('.accordion > .toggle').click(function() {
    var $this = $(this);
    if ( $this.hasClass('active') ) {
      $this.removeClass('active').next().slideUp();
    } else {
      $('.accordion > .panel').slideUp();
      $this.siblings().removeClass('active');
      $this.addClass('active').next().slideDown();
      return false;
    }
  });


  //
  // Save button.
  //

  $('#submit').click(function() {
    $(this).addClass('saving').val('Updating');
  });


  //
  // Color picker.
  //

  $('.wp-color-picker').wpColorPicker();


  //
  // Datepicker.
  //

  $('.datepicker').datepicker();


  //
  // Meta box toggle.
  //

  postboxes.add_postbox_toggles(pagenow);

});



// Media Uploader
// =============================================================================

jQuery(document).ready(function($) {
  $('.x-upload-btn-wl').click( function( e ) {
      var self = $(this);
      e.preventDefault();
      var image = wp.media({
        title: 'Upload Image',
        multiple: false
      }).open()
      .on('select', function( e ) {
          var uploaded_image = image.state().get( 'selection' ).first();
          var image_url = uploaded_image.toJSON().url;
          $('#' + self.data('id') ).val(image_url);
          console.log('#_' + self.data('id') + '_thumb')
          $('#_' + self.data('id') + '_thumb' ).html('<div class="x-uploader-image"><img src="' + image_url + '" alt="" /></div>');
      });
  });
});

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

  var $pluginEnable   = $('#x_content_dock_enable');
  var $pluginSettings = $('#meta-box-settings');

  $pluginEnable.change(function() {
    if ( $pluginEnable.is(':checked') ) {
      $pluginSettings.show();
    } else {
      $pluginSettings.hide();
    }
  });



  //
  // Show/hide post entries list
  //

  var $allPagesEnable = $('#x_content_dock_all_pages_active');
  var $pagesRow       = $('#x_content_dock_entries_include_row');

  function checkAllPagesEnable() {
    if ( $allPagesEnable.is(':checked') ) {
      $pagesRow.hide();
    } else {
      $pagesRow.show();
    }
  }

  $allPagesEnable.change(function() {
    checkAllPagesEnable();
  });

  checkAllPagesEnable();



  //
  // Show/hide post entries list
  //

  var $allPostsEnable = $('#x_content_dock_all_posts_active');
  var $postsRow       = $('#x_content_dock_posts_include_row');

  function checkAllPostsEnable() {
    if ( $allPostsEnable.is(':checked') ) {
      $postsRow.hide();
    } else {
      $postsRow.show();
    }
  }

  $allPostsEnable.change(function() {
    checkAllPostsEnable();
  });

  checkAllPostsEnable();



  //
  // Show/hide woocommerce product entries list
  //

  var $allProducutsEnable = $('#x_content_dock_all_woo_products_active');
  var $productsRow       = $('#x_content_dock_woo_products_include_row');

  function checkAllProductsEnable() {
    if ( $allProducutsEnable.is(':checked') ) {
      $productsRow.hide();
    } else {
      $productsRow.show();
    }
  }

  $allProducutsEnable.change(function() {
    checkAllProductsEnable();
  });

  checkAllProductsEnable();


  //
  // Show/hide override options
  //

  var $overrideEnable = $('#x_content_dock_image_override_enable');
  var $overrideRow    = $('.x_content_dock_image_override_image_row');

  function checkOverrideEnable() {
    if ( $overrideEnable.is(':checked') ) {
      $overrideRow.show();
    } else {
      $overrideRow.hide();
    }
  }

  $overrideEnable.change(function() {
    checkOverrideEnable();
  });

  checkOverrideEnable();


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
  $('.x-upload-btn-cd').click( function( e ) {
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

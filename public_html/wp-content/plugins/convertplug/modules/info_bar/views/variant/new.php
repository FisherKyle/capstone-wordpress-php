<div class="wrap smile-add-style bend">
  <div class="wrap-container">
    <div class="bend-heading-section">
      <h1 style="font-size: 38px;" title="<?php echo $_GET['style']; ?>">
        <?php _e( "Create new variant for", "smile" ); ?>
        <span class="cp-strip-text" style="max-width: 260px;top: 10px;"><?php echo $_GET['style']; ?></span></h1>
      <h3><a class="add-new-h2" href="?page=smile-info_bar-designer&style-view=variant&variant-style=<?php echo $_GET['variant-style']; ?>&style=<?php echo $_GET['style']; ?>&theme=<?php echo esc_attr( $_GET['theme'] ); ?>" title="<?php _e( "Back to Variant's List", "smile" ); ?>">
        <?php _e( "Back to Variants List", "smile" ); ?>
        </a></h3>
      <div class="col-sm-8 col-sm-offset-2">
        <h3>
          <?php _e( "Give a name to your variant and hit enter. Don't worry - you can change this in future if you don't like it anymore.", "smile" ); ?>
        </h3>
      </div>
      <div class="container">
        <div class="col-sm-6 col-sm-offset-3 smile-style-name-section">
          <input type="text" id="style-title" name="style-title" placeholder="<?php _e( "Enter title for new variant", "smile" ); ?>" />
        </div>
      </div>
      <div class="bend-content-wrap smile-add-style-content">
        <div class="container ">
          <div class="smile-style-category">
            <?php 
                    if(function_exists('Smile_Style_Dashboard')){   
                      Smile_Style_Dashboard('Smile_Info_Bars','info_bar_variant_tests','info_bar');
                    }
                ?>
            <!-- .styles-list --> 
          </div>
          <!-- .smile-style-category --> 
        </div>
        <!-- .container --> 
      </div>
    </div>
    <!-- .bend-content-wrap --> 
  </div>
  <!-- .wrap-container --> 
</div>
<!-- .wrap -->
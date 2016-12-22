<?php

if( ! class_exists( 'Add_Convertplug_Widget' ) ){
	class Add_Convertplug_Widget extends WP_Widget {
     
		// widget constructor
		public function __construct(){
		  	parent::__construct(
	        	'convertplug_widget', __( 'ConvertPlug Widget', 'smile' ),
		        array(
		            'classname'   => 'convertplug_widget',
		            'description' => __( 'A widget to display modules inline as a part of sidebar area.', 'smile' )
		        )
	      	);
		}

		public function widget( $args, $instance ) {
			
			wp_enqueue_script( 'cp-widget-front-jscript', plugin_dir_url( __FILE__ ). 'assets/js/cp-widgets-front.js', array( 'jquery' ) );

			// outputs the content of the widget
			extract( $args );
     
		    $title         = apply_filters( 'widget_title', $instance['title'] );
			$style_id = "";
		    $select = $instance['select'] ;

		    if ( $select == 'info_bar' ) {
			    $style_id = '[cp_info_bar display="inline" id="'.$instance['style_id_infobar'].'"][/cp_info_bar]';
			} elseif ( $select == 'slide_in' ) {
			    $style_id = '[cp_slide_in display="inline" id="'.$instance['style_id_slidein'].'"][/cp_slide_in]';
			} else {
			    $style_id = '[cp_modal display="inline" id="'.$instance['style_id_modal'].'"][/cp_modal]';
			}
		     
		    echo $before_widget;
		     
		    if ( $title ) {
		        echo $before_title . $title . $after_title;
		    }
		                         
		    // echo $message;
		    echo do_shortcode( $style_id );
		    echo $after_widget;
		}
	    
		public function form( $instance ) {

			wp_enqueue_script( 'cp-widget-jscript', plugin_dir_url( __FILE__ ). 'assets/js/cp-widgets.js', array( 'jquery' ) );

			if( $instance ) {
			    $title = $instance['title'] != '' ? esc_attr($instance['title']) : '' ;
			    $select = $instance['select'] != '' ? esc_attr($instance['select']) : '' ;

				$style_id_modal = isset( $instance['style_id_modal'] ) && $instance['style_id_modal'] != '' ? esc_attr($instance['style_id_modal']) : '' ; // Added
			    $style_id_infobar = isset( $instance['style_id_infobar'] ) && $instance['style_id_infobar'] != '' ? esc_attr($instance['style_id_infobar']) : '' ; // Added
			    $style_id_slidein = isset( $instance['style_id_slidein'] ) && $instance['style_id_slidein'] != '' ? esc_attr($instance['style_id_slidein']) : '' ; // Added

			} else {
			    $title = '';
			    $select = '';

			    $style_id_modal = '' ; // Added
			    $style_id_infobar = '' ; // Added
			    $style_id_slidein = '' ; // Added
			}

			$cp_modules = get_option('convert_plug_modules');

			$select = $select != '' ? $select : strtolower( str_replace( '_Popup', '', $cp_modules[0] ) );

			$cp_modal_name = 'smile_modal_styles';
			$cp_infobar_name = 'smile_info_bar_styles';
			$cp_slidein_name = 'smile_slide_in_styles';

			$cp_modal_id = is_array( get_option( $cp_modal_name ) ) ? array_reverse( get_option( $cp_modal_name ) ) : array();
			$cp_infobar_id = is_array( get_option( $cp_infobar_name ) ) ? array_reverse( get_option( $cp_infobar_name ) ) : array();
			$cp_slidein_id = is_array( get_option( $cp_slidein_name ) ) ? array_reverse( get_option( $cp_slidein_name ) ) : array();
			
			if ( $select == 'info_bar' ) {
				$modal_style = 'display:none';
				$infobar_style = '';
				$slidein_style = 'display:none';
			} elseif ( $select == 'slide_in' ) {
				$modal_style = 'display:none';
				$infobar_style = 'display:none';
				$slidein_style = '';
			} else {
				$modal_style = '';
				$infobar_style = 'display:none';
				$slidein_style = 'display:none';
			}
			?>
			
			<p>
		        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
		        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		    </p>

			<p>
		    <label for="<?php echo $this->get_field_id('select'); ?>"><?php _e('Active Modules:'); ?></label> 
			<select id="<?php echo $this->get_field_id('select'); ?>" name="<?php echo $this->get_field_name('select'); ?>" class="cp-active-modules widefat">
			<?php       
			foreach ($cp_modules as $value) { 
				$value = strtolower( str_replace( '_Popup', '', $value ) );
				echo '<option value="' . $value . '" id="' . $value . '"', $select == $value ? ' selected="selected"' : '', '>', ucwords( str_replace( '_', ' ', $value ) ), '</option>';
			}
			?>
			</select>
			</p>

			
			<p class = "cp-modal-id" style="<?php echo $modal_style ?> ">
		    <?php 
				if ( count( $cp_modal_id ) > 0 ) { 
			?>
		    <label for="<?php echo $this->get_field_id('style_id_modal'); ?>"><?php _e('Style Name:'); ?></label> 
			<select id="<?php echo $this->get_field_id('style_id_modal'); ?>" name="<?php echo $this->get_field_name('style_id_modal'); ?>" class="widefat">
			<?php       
			foreach ($cp_modal_id as $value) { 
				echo '<option value="' . $value['style_id'] . '" id="' . $value['style_id'] . '"', $style_id_modal == $value['style_id'] ? ' selected="selected"' : '', '>', urldecode( $value['style_name'] ), '</option>';
			}
			?>
			</select>
			<?php }else{ ?>
				Looks like you haven't created any style yet! Lets create first style <a href="<?php echo admin_url(); ?>admin.php?page=smile-modal-designer" target="_blank" >here</a>.
			<?php } ?>
			</p>

			<p class = "cp-infobar-id" style="<?php echo $infobar_style ?>" >
		    <?php 
				if ( count( $cp_infobar_id ) > 0 ) { 
			?>
		    <label for="<?php echo $this->get_field_id('style_id_infobar'); ?>"><?php _e('Style Name:'); ?></label> 
			<select id="<?php echo $this->get_field_id('style_id_infobar'); ?>" name="<?php echo $this->get_field_name('style_id_infobar'); ?>" class="widefat">
			<?php       
			foreach ($cp_infobar_id as $value) { 
				echo '<option value="' . $value['style_id'] . '" id="' . $value['style_id'] . '"', $style_id_infobar == $value['style_id'] ? ' selected="selected"' : '', '>', urldecode( $value['style_name'] ), '</option>';
			}
			?>
			</select>
			<?php }else{ ?>
				Looks like you haven't created any style yet! Lets create first style <a href="<?php echo admin_url(); ?>admin.php?page=smile-info_bar-designer" target="_blank" >here</a>.
			<?php } ?>
			</p>

			<p class = "cp-slidein-id" style="<?php echo $slidein_style ?>" >
			<?php 
				if ( count( $cp_slidein_id ) > 0 ) { 
			?>
		    <label for="<?php echo $this->get_field_id('style_id_slidein'); ?>"><?php _e('Style Name:'); ?></label> 
			<select id="<?php echo $this->get_field_id('style_id_slidein'); ?>" name="<?php echo $this->get_field_name('style_id_slidein'); ?>" class="widefat">
			<?php       
			foreach ($cp_slidein_id as $value) { 
				echo '<option value="' . $value['style_id'] . '" id="' . $value['style_id'] . '"', $style_id_slidein == $value['style_id'] ? ' selected="selected"' : '', '>', urldecode( $value['style_name'] ), '</option>';
			}
			?>
			</select>
			<?php }else{ ?>
				Looks like you haven't created any style yet! Lets create first style <a href="<?php echo admin_url(); ?>admin.php?page=smile-slide_in-designer" target="_blank" >here</a>.
			<?php } ?>
			</p>

			<?php
		}
	    
		// Updating widget replacing old instances with new
		public function update( $new_instance, $old_instance ) {
			// processes widget options on save

		    $instance = $old_instance;
	     
		    $instance['title'] = strip_tags( $new_instance['title'] );
		    $instance['select'] = strip_tags( $new_instance['select'] );

		    if ( $new_instance['select'] == 'info_bar' ) {
			    $instance['style_id_infobar'] = strip_tags( $new_instance['style_id_infobar'] );
			} elseif ( $new_instance['select'] == 'slide_in' ) {
			    $instance['style_id_slidein'] = strip_tags( $new_instance['style_id_slidein'] );

			} else {
				$instance['style_id_modal'] = strip_tags( $new_instance['style_id_modal'] );
			}
		     
		    return $instance;
		}
	   
	}
} //End Class Exist if

// Register and load the widget
if( ! function_exists( 'Load_Convertplug_Widget' ) ){
	function Load_Convertplug_Widget() {
	    register_widget( 'Add_Convertplug_Widget' );
	}
}
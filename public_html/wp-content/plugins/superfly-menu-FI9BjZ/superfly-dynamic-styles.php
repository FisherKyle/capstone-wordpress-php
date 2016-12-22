<?php

$width_panel_1 = !empty($options['sf_width_panel_1']) ? $options['sf_width_panel_1'] : 250;
$width_panel_2 = !empty($options['sf_width_panel_2']) ? $options['sf_width_panel_2'] : 250;
$width_panel_3 = !empty($options['sf_width_panel_3']) ? $options['sf_width_panel_3'] : 250;
$width_panel_4 = !empty($options['sf_width_panel_4']) ? $options['sf_width_panel_4'] : 250;

if ($options['sf_sidebar_style'] == 'toolbar') $width_panel_1 = 100;

$panel1_adjusted = $width_panel_1;

$sum1 = $width_panel_1 + $width_panel_2;
$sum2 = $width_panel_1 + $width_panel_2 + $width_panel_3;
$sum2a = $width_panel_1 + $width_panel_2 + $width_panel_3 + $width_panel_4;
$sum3 = $width_panel_2 + $width_panel_3;
$sum4 = $width_panel_2 + $width_panel_3 + $width_panel_4;

$color_panel_1 = $options['sf_color_panel_1'];
$color_panel_2 = $options['sf_color_panel_2'];
$color_panel_3 = $options['sf_color_panel_3'];
$color_panel_4 = $options['sf_color_panel_4'];
$bg_color_panel_1 = !empty($options['sf_bg_color_panel_1']) ? $options['sf_bg_color_panel_1'] : '#202b2d';
$bg_color_panel_2 = !empty($options['sf_bg_color_panel_2']) ? $options['sf_bg_color_panel_2'] : '#b5b5b5';
$bg_color_panel_3 = !empty($options['sf_bg_color_panel_3']) ? $options['sf_bg_color_panel_3'] : '#36939e';
$bg_color_panel_4 = !empty($options['sf_bg_color_panel_4']) ? $options['sf_bg_color_panel_4'] : '#9e466b';

$icon_color = !empty($options['sf_icon_color']) ? $options['sf_icon_color'] : '#777';

$font = $options['sf_font'];
$font_c = $options['sf_c_font'];
$weight = $options['sf_font_weight'];
$opacityLevel = $options['sf_fade_content'] === 'light' ? 0.6 : ($options['sf_fade_content'] === 'dark' ? 0.9 : 0);

$width_panel_23 = $width_panel_1 / 2;
$width_panel_skew_shift = $width_panel_1;
$width_panel_skew_stroke = 0;
$width_panel_skew = $width_panel_1*2;

$line = $options['sf_label_width'];
$transform = $options['sf_uppercase'] === 'yes' ? 'uppercase' : 'capitalize';
$searchbg = $options['sf_search_bg'] === 'light' ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.3)';
?>
<script>
    var SFM_is_mobile = (function () {
        var n = navigator.userAgent;
        var reg = new RegExp('Android\s([0-9\.]*)')
        var match = n.toLowerCase().match(reg);
        var android =  match ? parseFloat(match[1]) : false;
        if (android && android < 3.6) return

        return n.match(/Android|BlackBerry|IEMobile|iPhone|iPad|iPod|Opera Mini/i)
    })();

	var SFM_skew_disabled = (function($){
		var window_width = $(window).width();
		var sfm_width = <?php echo $width_panel_1; ?>;
		if(sfm_width * 2 >= window_width){
			return true;
		}
		return false;
	})(jQuery);

    (function(){
        var mob_bar = '<?php echo $options['sf_mob_nav'] === 'yes'?>';
        var pos = '<?php echo $options['sf_sidebar_pos']?>';
        var iconbar = '<?php echo $options['sf_sidebar_style'] == 'toolbar'; ?>';
        var classes = SFM_is_mobile ? (mob_bar ? 'sfm-mobile sfm-mob-nav' : 'sfm-mobile'): 'sfm-no-mobile';
        classes += ' sfm-pos-' + pos;
        classes += iconbar ? ' sfm-bar' : '';
		classes += SFM_skew_disabled ? ' sfm-skew-disabled' : '';
        document.getElementsByTagName('html')[0].className = document.getElementsByTagName('html')[0].className + ' ' + classes;
    })()
</script>
<style id="superfly-dynamic-styles">

	#sfm-sidebar.sfm-vertical-nav .sfm-has-child-menu .sfm-sm-indicator i:after {
		content: '\e610';
		-webkit-transition: all 0.3s cubic-bezier(0.215, 0.061, 0.355, 1);
		-moz-transition: all 0.3s cubic-bezier(0.215, 0.061, 0.355, 1);
		-o-transition: all 0.3s cubic-bezier(0.215, 0.061, 0.355, 1);
		transition: all 0.3s cubic-bezier(0.215, 0.061, 0.355, 1);
		-webkit-backface-visibility: hidden;
		display: inline-block;
	}

    .sfm-navicon, .sfm-navicon:after, .sfm-navicon:before, .sfm-sidebar-close:before, .sfm-sidebar-close:after {
       height: <?php echo $line; ?>px !important;
    }
    .sfm-label-square .sfm-navicon-button, .sfm-label-rsquare .sfm-navicon-button, .sfm-label-circle .sfm-navicon-button {
        border-width: <?php echo $line; ?>px !important;
    }

    .sfm-vertical-nav .sfm-submenu-visible > a .sfm-sm-indicator i:after {
		-webkit-transform: rotate(180deg);
		-moz-transform: rotate(180deg);
		-ms-transform: rotate(180deg);
		-o-transform: rotate(180deg);
		transform: rotate(180deg);
	}

	#sfm-mob-navbar .sfm-navicon-button:after {
		/*width: 30px;*/
	}

	.sfm-pos-right .sfm-vertical-nav .sfm-has-child-menu > a:before {
		display: none;
	}

	#sfm-sidebar.sfm-vertical-nav .sfm-menu .sfm-sm-indicator {
		background: rgba(255,255,255,0.085);
	}

	.sfm-pos-right #sfm-sidebar.sfm-vertical-nav .sfm-menu li a {
		/*padding-left: 10px !important;*/
	}

	.sfm-pos-right #sfm-sidebar.sfm-vertical-nav .sfm-sm-indicator {
		left: auto;
		right: 0;
	}



.sfm-mobile #sfm-sidebar.sfm-compact-header .sfm-logo img {
	max-height: 75px;
}

#sfm-sidebar.sfm-compact .sfm-nav {
	min-height: 50vh;
	height: auto;
	max-height: none;
	margin-top: 0px;
}

#sfm-sidebar.sfm-compact-footer .sfm-social {
	margin-top: 30px;
	margin-bottom: 10px;
	position: relative;
}

#sfm-sidebar.sfm-compact .sfm-sidebar-bg {
	min-height: 150%;
}

#sfm-sidebar.sfm-compact  input[type=search] {
	font-size: 16px;
}
/*}*/

<?php if(isset($options['sf_bg_color_panel_1'])): ?>
#sfm-sidebar .sfm-sidebar-bg, #sfm-sidebar .sfm-social {
	background-color: <?php echo $options['sf_bg_color_panel_1']; ?> !important;
}
<?php endif; ?>

<?php if(isset($options['sf_transparent_panel']) && $options['sf_transparent_panel'] != 'none'): ?>
#sfm-sidebar .sfm-sidebar-bg  {
	background-color: <?php echo $options['sf_transparent_panel'] == 'dark' ? 'rgba(0,0,0,0.7)' : 'rgba(255,255,255, 0.7)'; ?> !important;
}

#sfm-sidebar .sfm-social {
	background-color: transparent !important;
}
.sfm-sidebar-slide.sfm-body-pushed .sfm-rollback {
   opacity: 0;
}
<?php endif; ?>

#sfm-sidebar, .sfm-sidebar-bg, #sfm-sidebar .sfm-nav, #sfm-sidebar .sfm-logo, #sfm-sidebar .sfm-social, .sfm-style-toolbar .sfm-copy {
	width: <?php echo $width_panel_1; ?>px;
}
<?php if(intval($options['sf_font_size']) > 25): ?>
	#sfm-sidebar .sfm-menu li > a span {
		vertical-align: baseline;
	}
<?php endif; ?>
#sfm-sidebar:not(.sfm-iconbar) .sfm-menu li > a span{
<?php
$shift = ((int) $options['sf_icon_size']) + 28 + 12;
?>
	max-width: <?php echo "calc({$width_panel_1}px - {$shift}px);"; ?>
}
#sfm-sidebar .sfm-social {
	background-color: transparent !important;
}

    <?php if(!empty($options['sf_image_bg'])): ?>
    .sfm-sidebar-bg {
        background-image: url(<?php echo $options['sf_image_bg'] ; ?>);
        background-repeat: no-repeat;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        background-size: cover;
        background-position: 0 0;
    }
    #sfm-sidebar .sfm-social {
        background-color: transparent !important;
    }
    <?php endif; ?>
<?php if($options['sf_highlight'] == 'semi-dark'): ?>
    #sfm-sidebar .sfm-menu li > a:before {
    background: rgba(0, 0, 0, 0.05) !important;
}
    <?php endif; ?>

<?php if($options['sf_highlight'] == 'solid'): ?>
.sfm-no-mobile #sfm-sidebar .sfm-menu-level-0 a:before,
.sfm-no-mobile #sfm-sidebar .sfm-menu-level-0 .sfm-active-item a:before,
.sfm-no-mobile #sfm-sidebar .sfm-menu-level-0 .sfm-active-smooth a:before {
	background-color: <?php echo $bg_color_panel_2; ?>;
}

#sfm-sidebar .sfm-view-level-1 .sfm-menu a:before,
#sfm-sidebar .sfm-view-level-1 .sfm-menu .sfm-active-item a:before {
	background-color: <?php echo $bg_color_panel_3; ?>;
}

#sfm-sidebar .sfm-view-level-2 .sfm-menu a:before,
#sfm-sidebar .sfm-view-level-2 .sfm-menu .sfm-active-item a:before {
	background-color: <?php echo $bg_color_panel_4; ?>;
}

#sfm-sidebar .sfm-view-level-3 .sfm-menu a:before,
#sfm-sidebar .sfm-view-level-3 .sfm-menu .sfm-active-item a:before {
	background-color: <?php echo $bg_color_panel_1; ?>;
}
<?php endif; ?>


<?php if($options['sf_highlight'] == 'line'): ?>

.sfm-no-mobile #sfm-sidebar .sfm-menu-level-0 li:hover:before,
.sfm-no-mobile #sfm-sidebar .sfm-menu-level-0 li.sfm-active-item:before,
.sfm-no-mobile #sfm-sidebar .sfm-menu-level-0 li.sfm-active-smooth:before {
		background-color: <?php echo $color_panel_1; ?>;
}

#sfm-sidebar .sfm-view-level-1 .sfm-menu li:hover:before,
#sfm-sidebar .sfm-view-level-1 .sfm-menu li.sfm-active-item:before {
	background-color: <?php echo $color_panel_2; ?>;
}

#sfm-sidebar .sfm-view-level-2 .sfm-menu li:hover:before,
#sfm-sidebar .sfm-view-level-2 .sfm-menu li.sfm-active-item:before {
	background-color: <?php echo $color_panel_3; ?>;
}

#sfm-sidebar .sfm-view-level-3 .sfm-menu li:hover:before,
#sfm-sidebar .sfm-view-level-3 .sfm-menu li.sfm-active-item:before {
	background-color: <?php echo $color_panel_4; ?>;
}
<?php endif; ?>
<?php if ($options['sf_alignment'] != 'center'): ?>
#sfm-sidebar .sfm-menu li a,
#sfm-sidebar .sfm-chapter,
#sfm-sidebar .widget-area,
.sfm-search-form input {
	padding-<?php echo $options['sf_alignment']; ?>: 28px !important;
}

#sfm-sidebar.sfm-compact  .sfm-social li {
	text-align: <?php echo $options['sf_alignment']; ?>;
}

#sfm-sidebar.sfm-compact  .sfm-social:before {
	right: auto;
	left: auto;
<?php echo $options['sf_alignment']; ?>: 10%;
}

<?php endif; ?>

#sfm-sidebar:after {
	display: none !important;
}

<?php if ($options['sf_search'] == 'hidden'): ?>
#sfm-sidebar .search-form {
	display: none !important;
}
<?php endif; ?>

<?php if($options['sf_sidebar_behaviour'] == 'push' && ($options['sf_sidebar_style'] == 'side' || $options['sf_sidebar_style'] == 'skew')): ?>
body.sfm-body-pushed > * {
	-webkit-transform: translate(<?php echo $width_panel_23; ?>px,0);
	-moz-transform: translate(<?php echo $width_panel_23; ?>px,0);
	-ms-transform: translate(<?php echo $width_panel_23; ?>px,0);
	-o-transform: translate(<?php echo $width_panel_23; ?>px,0);
	transform: translate(<?php echo $width_panel_23; ?>px,0);
	-webkit-transform: translate3d(<?php echo $width_panel_23; ?>px,0,0);
	-moz-transform: translate3d(<?php echo $width_panel_23; ?>px,0,0);
	-ms-transform: translate3d(<?php echo $width_panel_23; ?>px,0,0);
	-o-transform: translate3d(<?php echo $width_panel_23; ?>px,0,0);
	transform: translate3d(<?php echo $width_panel_23; ?>px,0,0);
}

.sfm-pos-right .sfm-body-pushed > * {
	-webkit-transform: translate(-<?php echo $width_panel_23; ?>px,0);
	-moz-transform: translate(-<?php echo $width_panel_23; ?>px,0);
	-ms-transform: translate(-<?php echo $width_panel_23; ?>px,0);
	-o-transform: translate(-<?php echo $width_panel_23; ?>px,0);
	transform: translate(-<?php echo $width_panel_23; ?>px,0);
	-webkit-transform: translate3d(-<?php echo $width_panel_23; ?>px,0,0);
	-moz-transform: translate3d(-<?php echo $width_panel_23; ?>px,0,0);
	-ms-transform: translate3d(-<?php echo $width_panel_23; ?>px,0,0);
	-o-transform: translate3d(-<?php echo $width_panel_23; ?>px,0,0);
	transform: translate3d(-<?php echo $width_panel_23; ?>px,0,0);
}
<?php endif; ?>


<?php if($options['sf_sidebar_behaviour'] == 'always'): ?>
.sfm-no-mobile #sfm-sidebar {
	-webkit-transform: translate(0,0);
	-moz-transform: translate(0,0);
	-ms-transform: translate(0,0);
	-o-transform: translate(0,0);
	transform: translate(0,0);
	-webkit-transform: translate3d(0,0,0);
	-moz-transform: translate3d(0,0,0);
	-ms-transform: translate3d(0,0,0);
	-o-transform: translate3d(0,0,0);
	transform: translate3d(0,0,0);
}


.sfm-no-mobile.sfm-pos-left body, .sfm-no-mobile.sfm-pos-left #wpadminbar {
	padding-left: <?php echo $panel1_adjusted; ?>px !important;
	box-sizing: border-box !important;
}


.sfm-no-mobile #sfm-sidebar{
	display:block !important
}


.sfm-no-mobile.sfm-pos-right body, .sfm-no-mobile.sfm-pos-right #wpadminbar {
	padding-right: <?php echo $panel1_adjusted; ?>px !important;
	box-sizing: border-box !important;
}

#sfm-sidebar .sfm-rollback {
	/*display: none !important;*/
}

body.sfm-body-pushed > *body.sfm-body-pushed > * {
    -webkit-transform: translate(<?php echo $width_panel_23; ?>px,0);
    -moz-transform: translate(<?php echo $width_panel_23; ?>px,0);
    -ms-transform: translate(<?php echo $width_panel_23; ?>px,0);
    -o-transform: translate(<?php echo $width_panel_23; ?>px,0);
    transform: translate(<?php echo $width_panel_23; ?>px,0);
    -webkit-transform: translate3d(<?php echo $width_panel_23; ?>px,0,0);
    -moz-transform: translate3d(<?php echo $width_panel_23; ?>px,0,0);
    -ms-transform: translate3d(<?php echo $width_panel_23; ?>px,0,0);
    -o-transform: translate3d(<?php echo $width_panel_23; ?>px,0,0);
    transform: translate3d(<?php echo $width_panel_23; ?>px,0,0);
}

    .sfm-pos-right .sfm-body-pushed > * {
        -webkit-transform: translate(-<?php echo $width_panel_23; ?>px,0);
        -moz-transform: translate(-<?php echo $width_panel_23; ?>px,0);
        -ms-transform: translate(-<?php echo $width_panel_23; ?>px,0);
        -o-transform: translate(-<?php echo $width_panel_23; ?>px,0);
        transform: translate(-<?php echo $width_panel_23; ?>px,0);
        -webkit-transform: translate3d(-<?php echo $width_panel_23; ?>px,0,0);
        -moz-transform: translate3d(-<?php echo $width_panel_23; ?>px,0,0);
        -ms-transform: translate3d(-<?php echo $width_panel_23; ?>px,0,0);
        -o-transform: translate3d(-<?php echo $width_panel_23; ?>px,0,0);
        transform: translate3d(-<?php echo $width_panel_23; ?>px,0,0);
    }

<?php endif; ?>

#sfm-sidebar,
.sfm-pos-right .sfm-sidebar-slide.sfm-body-pushed #sfm-mob-navbar {
	-webkit-transform: translate(-<?php echo $panel1_adjusted; ?>px,0);
	-moz-transform: translate(-<?php echo $panel1_adjusted; ?>px,0);
	-ms-transform: translate(-<?php echo $panel1_adjusted; ?>px,0);
	-o-transform: translate(-<?php echo $panel1_adjusted; ?>px,0);
	transform: translate(-<?php echo $panel1_adjusted; ?>px,0);
	-webkit-transform: translate3d(-<?php echo $panel1_adjusted; ?>px,0,0);
	-moz-transform: translate3d(-<?php echo $panel1_adjusted; ?>px,0,0);
	-ms-transform: translate3d(-<?php echo $panel1_adjusted; ?>px,0,0);
	-o-transform: translate3d(-<?php echo $panel1_adjusted; ?>px,0,0);
	transform: translate3d(-<?php echo $panel1_adjusted; ?>px,0,0);
}

.sfm-pos-right #sfm-sidebar, .sfm-sidebar-slide.sfm-body-pushed #sfm-mob-navbar {
	-webkit-transform: translate(<?php echo $panel1_adjusted; ?>px,0);
	-moz-transform: translate(<?php echo $panel1_adjusted; ?>px,0);
	-ms-transform: translate(<?php echo $panel1_adjusted; ?>px,0);
	-o-transform: translate(<?php echo $panel1_adjusted; ?>px,0);
	transform: translate(<?php echo $panel1_adjusted; ?>px,0);
	-webkit-transform: translate3d(<?php echo $panel1_adjusted; ?>px,0,0);
	-moz-transform: translate3d(<?php echo $panel1_adjusted; ?>px,0,0);
	-ms-transform: translate3d(<?php echo $panel1_adjusted; ?>px,0,0);
	-o-transform: translate3d(<?php echo $panel1_adjusted; ?>px,0,0);
	transform: translate3d(<?php echo $panel1_adjusted; ?>px,0,0);
}

.sfm-pos-left #sfm-sidebar .sfm-view-level-1 {
	left: <?php echo $width_panel_1; ?>px;
	width: <?php echo $width_panel_2; ?>px;
	-webkit-transform: translate(-<?php echo $width_panel_2; ?>px,0);
	-moz-transform: translate(-<?php echo $width_panel_2; ?>px,0);
	-ms-transform: translate(-<?php echo $width_panel_2; ?>px,0);
	-o-transform: translate(-<?php echo $width_panel_2; ?>px,0);
	transform: translate(-<?php echo $width_panel_2; ?>px,0);
	-webkit-transform: translate3d(-<?php echo $width_panel_2; ?>px,0,0);
	-moz-transform: translate3d(-<?php echo $width_panel_2; ?>px,0,0);
	-ms-transform: translate3d(-<?php echo $width_panel_2; ?>px,0,0);
	-o-transform: translate3d(-<?php echo $width_panel_2; ?>px,0,0);
	transform: translate3d(-<?php echo $width_panel_2; ?>px,0,0);
}

.sfm-pos-right #sfm-sidebar .sfm-view-level-1 {
	left: auto;
	right: <?php echo $width_panel_1; ?>px;
	width: <?php echo $width_panel_2; ?>px;
	-webkit-transform: translate(<?php echo $width_panel_2; ?>px,0);
	-moz-transform: translate(<?php echo $width_panel_2; ?>px,0);
	-ms-transform: translate(<?php echo $width_panel_2; ?>px,0);
	-o-transform: translate(<?php echo $width_panel_2; ?>px,0);
	transform: translate(<?php echo $width_panel_2; ?>px,0);
	-webkit-transform: translate3d(<?php echo $width_panel_2; ?>px,0,0);
	-moz-transform: translate3d(<?php echo $width_panel_2; ?>px,0,0);
	-ms-transform: translate3d(<?php echo $width_panel_2; ?>px,0,0);
	-o-transform: translate3d(<?php echo $width_panel_2; ?>px,0,0);
	transform: translate3d(<?php echo $width_panel_2; ?>px,0,0);
}

.sfm-pos-left #sfm-sidebar .sfm-view-level-2 {
	left: <?php echo $sum1; ?>px;
	width: <?php echo $width_panel_3; ?>px;
	-webkit-transform: translate(-<?php echo $sum2; ?>px,0);
	-moz-transform: translate(-<?php echo $sum2; ?>px,0);
	-ms-transform: translate(-<?php echo $sum2; ?>px,0);
	-o-transform: translate(-<?php echo $sum2; ?>px,0);
	transform: translate(-<?php echo $sum2; ?>px,0);
	-webkit-transform: translate3d(-<?php echo $sum2; ?>px,0,0);
	-moz-transform: translate3d(-<?php echo $sum2; ?>px,0,0);
	-ms-transform: translate3d(-<?php echo $sum2; ?>px,0,0);
	-o-transform: translate3d(-<?php echo $sum2; ?>px,0,0);
	transform: translate3d(-<?php echo $sum2; ?>px,0,0);
}

.sfm-pos-right #sfm-sidebar .sfm-view-level-2
{
	left: auto;
	right: <?php echo $sum1; ?>px;
	width: <?php echo $width_panel_3; ?>px;
	-webkit-transform: translate(<?php echo $sum2; ?>px,0);
	-moz-transform: translate(<?php echo $sum2; ?>px,0);
	-ms-transform: translate(<?php echo $sum2; ?>px,0);
	-o-transform: translate(<?php echo $sum2; ?>px,0);
	transform: translate(<?php echo $sum2; ?>px,0);
	-webkit-transform: translate3d(<?php echo $sum2; ?>px,0,0);
	-moz-transform: translate3d(<?php echo $sum2; ?>px,0,0);
	-ms-transform: translate3d(<?php echo $sum2; ?>px,0,0);
	-o-transform: translate3d(<?php echo $sum2; ?>px,0,0);
	transform: translate3d(<?php echo $sum2; ?>px,0,0);
}

.sfm-pos-left #sfm-sidebar .sfm-view-level-3 {
	left: <?php echo $sum2; ?>px;
	width: <?php echo $width_panel_4; ?>px;
	-webkit-transform: translate(-<?php echo $sum2; ?>px,0);
	-moz-transform: translate(-<?php echo $sum2; ?>px,0);
	-ms-transform: translate(-<?php echo $sum2; ?>px,0);
	-o-transform: translate(-<?php echo $sum2; ?>px,0);
	transform: translate(-<?php echo $sum2; ?>px,0);
	-webkit-transform: translate3d(-<?php echo $sum2; ?>px,0,0);
	-moz-transform: translate3d(-<?php echo $sum2; ?>px,0,0);
	-ms-transform: translate3d(-<?php echo $sum2; ?>px,0,0);
	-o-transform: translate3d(-<?php echo $sum2; ?>px,0,0);
	transform: translate3d(-<?php echo $sum2; ?>px,0,0);
}

.sfm-pos-right #sfm-sidebar .sfm-view-level-3 {
	left: auto;
	right: <?php echo $sum2; ?>px;
	width: <?php echo $width_panel_4; ?>px;
	-webkit-transform: translate(<?php echo $sum2; ?>px,0);
	-moz-transform: translate(<?php echo $sum2; ?>px,0);
	-ms-transform: translate(<?php echo $sum2; ?>px,0);
	-o-transform: translate(<?php echo $sum2; ?>px,0);
	transform: translate(<?php echo $sum2; ?>px,0);
	-webkit-transform: translate3d(<?php echo $sum2; ?>px,0,0);
	-moz-transform: translate3d(<?php echo $sum2; ?>px,0,0);
	-ms-transform: translate3d(<?php echo $sum2; ?>px,0,0);
	-o-transform: translate3d(<?php echo $sum2; ?>px,0,0);
	transform: translate3d(<?php echo $sum2; ?>px,0,0);
}

.sfm-view-pushed-1 #sfm-sidebar .sfm-view-level-2 {
	-webkit-transform: translate(-<?php echo $width_panel_3; ?>px,0);
	-moz-transform: translate(-<?php echo $width_panel_3; ?>px,0);
	-ms-transform: translate(-<?php echo $width_panel_3; ?>px,0);
	-o-transform: translate(-<?php echo $width_panel_3; ?>px,0);
	transform: translate(-<?php echo $width_panel_3; ?>px,0);
	-webkit-transform: translate3d(-<?php echo $width_panel_3; ?>px,0,0);
	-moz-transform: translate3d(-<?php echo $width_panel_3; ?>px,0,0);
	-ms-transform: translate3d(-<?php echo $width_panel_3; ?>px,0,0);
	-o-transform: translate3d(-<?php echo $width_panel_3; ?>px,0,0);
	transform: translate3d(-<?php echo $width_panel_3; ?>px,0,0);
}

.sfm-pos-right .sfm-view-pushed-1 #sfm-sidebar .sfm-view-level-2 {
	-webkit-transform: translate(<?php echo $width_panel_3; ?>px,0);
	-moz-transform: translate(<?php echo $width_panel_3; ?>px,0);
	-ms-transform: translate(<?php echo $width_panel_3; ?>px,0);
	-o-transform: translate(<?php echo $width_panel_3; ?>px,0);
	transform: translate(<?php echo $width_panel_3; ?>px,0);
	-webkit-transform: translate3d(<?php echo $width_panel_3; ?>px,0,0);
	-moz-transform: translate3d(<?php echo $width_panel_3; ?>px,0,0);
	-ms-transform: translate3d(<?php echo $width_panel_3; ?>px,0,0);
	-o-transform: translate3d(<?php echo $width_panel_3; ?>px,0,0);
	transform: translate3d(<?php echo $width_panel_3; ?>px,0,0);
}

.sfm-view-pushed-2 #sfm-sidebar .sfm-view-level-3 {
	-webkit-transform: translate(-<?php echo $width_panel_4; ?>px,0);
	-moz-transform: translate(-<?php echo $width_panel_4; ?>px,0);
	-ms-transform: translate(-<?php echo $width_panel_4; ?>px,0);
	-o-transform: translate(-<?php echo $width_panel_4; ?>px,0);
	transform: translate(-<?php echo $width_panel_4; ?>px,0);
	-webkit-transform: translate3d(-<?php echo $width_panel_4; ?>px,0,0);
	-moz-transform: translate3d(-<?php echo $width_panel_4; ?>px,0,0);
	-ms-transform: translate3d(-<?php echo $width_panel_4; ?>px,0,0);
	-o-transform: translate3d(-<?php echo $width_panel_4; ?>px,0,0);
	transform: translate3d(-<?php echo $width_panel_4; ?>px,0,0);
}

.sfm-pos-right .sfm-view-pushed-2 #sfm-sidebar .sfm-view-level-3 {
	-webkit-transform: translate(<?php echo $width_panel_4; ?>px,0);
	-moz-transform: translate(<?php echo $width_panel_4; ?>px,0);
	-ms-transform: translate(<?php echo $width_panel_4; ?>px,0);
	-o-transform: translate(<?php echo $width_panel_4; ?>px,0);
	transform: translate(<?php echo $width_panel_4; ?>px,0);
	-webkit-transform: translate3d(<?php echo $width_panel_4; ?>px,0,0);
	-moz-transform: translate3d(<?php echo $width_panel_4; ?>px,0,0);
	-ms-transform: translate3d(<?php echo $width_panel_4; ?>px,0,0);
	-o-transform: translate3d(<?php echo $width_panel_4; ?>px,0,0);
	transform: translate3d(<?php echo $width_panel_4; ?>px,0,0);
}

#sfm-sidebar .sfm-view-level-1, #sfm-sidebar ul.sfm-menu-level-1 {
	background: <?php echo $bg_color_panel_2; ?>;
}

<?php if($options['sf_sidebar_style'] == 'full'): ?>
#sfm-sidebar {
	/*z-index: 1;*/
}


#sfm-sidebar .sfm-sidebar-bg, #sfm-sidebar .sfm-scroll-wrapper {
	opacity: 0 !important;
}
#sfm-sidebar.sfm-sidebar-exposed .sfm-scroll-wrapper {
	opacity: 1 !important;
}

#sfm-sidebar .sfm-social {
	background-color: transparent !important;
}

#sfm-sidebar .sfm-widget,
.sfm-menu li a{
	text-align: center !important;
}


.sfm-nav form, .sfm-menu li img, #sfm-sidebar .sfm-chapter, .sfm-menu li br,#sfm-sidebar .sfm-menu li:after  {
	display: none !important;
}

#sfm-sidebar.sfm-sidebar-exposed, .sfm-sidebar-bg, #sfm-sidebar .sfm-nav,  #sfm-sidebar .sfm-logo, #sfm-sidebar .sfm-social {
	width: 100%;
}
.sfm-rollback {
	/*z-index: 3000002;*/
}
    .sfm-sidebar-close:before, .sfm-sidebar-close:after {
        background-color: <?php echo $color_panel_1; ?>;
    }

#sfm-sidebar {
	opacity: 0 !important;
    visibility: hidden;
    width: 100% !important;
}

.sfm-body-pushed #sfm-sidebar {
    opacity: 1 !important;
    visibility: visible;
}

.sfm-pos-left .sfm-rollback {
	left: 0;
	right: auto;
}

.sfm-pos-right .sfm-rollback {
	left: auto;
	right: 0;
}

.sfm-body-pushed #sfm-overlay, body[class*="sfm-view-pushed"] #sfm-overlay {
    opacity: 1 !important;
    background: <?php echo $options['sf_fade_full']?> !important;
}

#sfm-sidebar .sfm-menu li > a:before {
	-webkit-transition: none;
	-moz-transition: none;
	-o-transition: none;
	transition: none;
	background: <?php echo $color_panel_1?>;
}

#sfm-sidebar .sfm-menu li a {
	padding-left: 5px !important;
	padding-right: 5px !important;
}
#sfm-sidebar .sfm-menu li:hover > a {
	color: #fff !important;
}

<?php endif; ?>

#sfm-sidebar .sfm-menu a img{
	max-width: <?php echo $options['sf_icon_size']; ?>px;
	max-height: <?php echo $options['sf_icon_size']; ?>px;
}
#sfm-sidebar .sfm-menu .la_icon{
	font-size: <?php echo $options['sf_icon_size']; ?>px;
	min-width: <?php echo $options['sf_icon_size']; ?>px;
	min-height: <?php echo $options['sf_icon_size']; ?>px;
}

<?php if($options['sf_highlight_active'] == 'yes'): ?>
#sfm-sidebar li.sfm-active-class > a {
	background: <?php echo $bg_color_panel_2; ?>;
}
<?php endif; ?>

#sfm-sidebar .sfm-view-level-2, #sfm-sidebar ul.sfm-menu-level-2 {
	background: <?php echo $bg_color_panel_3; ?>;
}

#sfm-sidebar .sfm-view-level-3, #sfm-sidebar ul.sfm-menu-level-3 {
	background: <?php echo $bg_color_panel_4; ?>;
}

#sfm-sidebar .sfm-menu-level-0 li, #sfm-sidebar .sfm-menu-level-0 li a, .sfm-title h3 {
	color: <?php echo $color_panel_1; ?>;
}

#sfm-sidebar .sfm-menu li a, #sfm-sidebar .sfm-chapter, #sfm-sidebar .sfm-search-form {
	padding: <?php echo $options['sf_padding']; ?>px 0;
	text-transform: <?php echo $transform; ?>;
}

#sfm-sidebar .sfm-search-form span {
	top: <?php echo intval($options['sf_padding']) + (intval($options['sf_font_size']) - 16) / 2 + 2; ?>px;
}

#sfm-sidebar {
	font-family: <?php echo $font; ?>;
}

#sfm-sidebar .sfm-sm-indicator {
	line-height: <?php echo $options['sf_font_size'];?>px;
}

#sfm-sidebar .sfm-search-form input {
	font-size: <?php echo $options['sf_font_size']; ?>px;
}

#sfm-sidebar .sfm-menu li a, #sfm-sidebar .sfm-menu .sfm-chapter {
	font-family: <?php echo $font; ?>;
	font-weight: <?php echo $weight; ?>;
	font-size: <?php echo $options['sf_font_size']; ?>px;
	text-align: <?php echo $options['sf_alignment']; ?>;
	-moz-font-smoothing: antialiased;
	-webkit-font-smoothing: antialiased;
	font-smoothing: antialiased;
	text-rendering: optimizeLegibility;
}
#sfm-sidebar .sfm-widget,
#sfm-sidebar .widget-area{
	text-align: <?php echo $options['sf_alignment']; ?>;
}
#sfm-sidebar .sfm-menu .sfm-chapter {
	font-size: <?php echo $options['sf_c_fs']; ?>px;
	margin-top: <?php echo $options['sf_padding']; ?>px;
}
#sfm-sidebar .sfm-menu .sfm-chapter div{
	font-family: <?php echo $font_c; ?>;
	font-size: <?php echo $options['sf_c_fs']; ?>px;
}
<?php if($options['sf_alignment'] == 'center'): ?>
	.sfm-has-child-menu > a {
		padding-right: 0 !important;
	}
    .sfm-pos-right .sfm-has-child-menu > a {
        padding-left: 0 !important;
    }
<?php endif; ?>
.sfm-rollback a {
	font-family: <?php echo $font; ?>;
}
#sfm-sidebar .sfm-menu .la_icon{
	color: <?php echo $icon_color; ?>;
}

#sfm-sidebar .sfm-menu-level-0 li .sfm-sm-indicator i,#sfm-sidebar .sfm-menu-level-0 .sfm-sl, .sfm-title h2, .sfm-social:after {
	color: <?php echo $options['sf_scolor_panel_1']; ?>;
}#sfm-sidebar .sfm-menu-level-1 li .sfm-sm-indicator i,#sfm-sidebar .sfm-menu-level-1 .sfm-sl {
	 color: <?php echo $options['sf_scolor_panel_2']; ?>;
 }#sfm-sidebar .sfm-menu-level-2 li .sfm-sm-indicator i,#sfm-sidebar .sfm-menu-level-2 .sfm-sl {
	  color: <?php echo $options['sf_scolor_panel_3']; ?>;
  }#sfm-sidebar .sfm-menu-level-3 li .sfm-sm-indicator i,#sfm-sidebar .sfm-menu-level-3 .sfm-sl {
	   color: <?php echo $options['sf_scolor_panel_4']; ?>;
   }
    .sfm-menu-level-0 .sfm-chapter {
        color: <?php echo $options['sf_chapter_1']; ?> !important;
    }    .sfm-menu-level-1 .sfm-chapter {
        color: <?php echo $options['sf_chapter_2']; ?> !important;
    }    .sfm-menu-level-2 .sfm-chapter {
        color: <?php echo $options['sf_chapter_3']; ?> !important;
    }    .sfm-menu-level-3 .sfm-chapter {
        color: <?php echo $options['sf_chapter_4']; ?> !important;
    }
#sfm-sidebar .sfm-view-level-1 li a,
#sfm-sidebar .sfm-menu-level-1 li a{
	color: <?php echo $color_panel_2; ?>;
	border-color: <?php echo $color_panel_2; ?>;
}

#sfm-sidebar:after {
	background-color: <?php echo $bg_color_panel_1; ?>;
}

#sfm-sidebar .sfm-view-level-2 li a,
#sfm-sidebar .sfm-menu-level-2 li a{
	color: <?php echo $color_panel_3; ?>;
	border-color: <?php echo $color_panel_3; ?>;
}

#sfm-sidebar .sfm-view-level-3 li a,
#sfm-sidebar .sfm-menu-level-3 li a {
	color: <?php echo $color_panel_4; ?>;
	border-color: <?php echo $color_panel_4; ?>;
}

.sfm-navicon-button {
	top: <?php echo $options['sf_label_top'] ?>;
}

.sfm-mobile .sfm-navicon-button {
	top: <?php echo $options['sf_label_top_mobile'] ?>;
}

	<?php if($options['sf_sidebar_pos'] == 'left'): ?>
.sfm-navicon-button {
	left: <?php echo $options['sf_label_shift'] ?> !important;;
}
.sfm-mobile .sfm-navicon-button {
	left: inherit;
}
<?php endif; ?>

<?php if($options['sf_sidebar_pos'] == 'right'): ?>
.sfm-navicon-button {
	right: <?php echo $options['sf_label_shift'] ?> !important;
}
.sfm-mobile .sfm-navicon-button {
	right: inherit;
}
<?php endif; ?>

.sfm-navicon:after,
.sfm-label-text .sfm-navicon:after,
.sfm-label-none .sfm-navicon:after {
	top: -<?php echo $options['sf_label_gaps'] ?>px;
}
.sfm-navicon:before,
.sfm-label-text .sfm-navicon:before,
.sfm-label-none .sfm-navicon:before {
	top: <?php echo $options['sf_label_gaps'] ?>px;
}

.sfm-body-pushed #sfm-overlay, body[class*="sfm-view-pushed"] #sfm-overlay {
	opacity: <?php echo $opacityLevel; ?>;
}
<?php if($opacityLevel != 0): ?>
.sfm-body-pushed #sfm-overlay, body[class*="sfm-view-pushed"] #sfm-overlay {
	cursor: url("<?php echo plugins_url('/img/', __FILE__);?>close3.png") 16 16,pointer;
}
<?php endif; ?>

<?php if($options['sf_label_text'] == 'yes'): ?>

.sfm-navicon-button:after {
	display: block;
	content: "<?php echo esc_html($options['sf_label_text_field']); ?>";
	line-height: 18px;
	font-size: 12px;
	font-weight: <?php echo $weight; ?>;
	text-align: center;
	text-decoration: none !important;
    position: absolute;
    left: -50%;
    top: 100%;
    width: 200%;
    margin: 5px 0 0 0;
	color: <?php echo $options['sf_label_text_color']; ?>;
}

.sfm-label-none .sfm-navicon-button:after {
    /*margin: -8px 0 0 -5px;*/
    /*left: 100%;*/
    /*top: 50%;*/
    /*width: auto;*/
}

<?php endif; ?>

<?php if($options['sf_separators'] == 'yes'): ?>

#sfm-sidebar .sfm-menu li:after {
	content: '';
	display: block;
	width: <?php echo $options['sf_separators_width'] ?>%;
	box-sizing: border-box;
	position: absolute;
	bottom: 0px;
	left: 0;
	right: 0;
	height: 1px;
	background: <?php echo $options['sf_separators_color']; ?>;
	margin: 0 auto;
}

#sfm-sidebar .sfm-menu li:last-child:after {
	display: none;
}

<?php endif; ?>

.sfm-style-skew #sfm-sidebar .sfm-social{
	height: auto;
	/*min-height: 75px;*/
}
.sfm-style-skew #sfm-sidebar .sfm-menu-level-0 li:hover,
.sfm-style-skew #sfm-sidebar .sfm-menu-level-0 li a:hover{
	color: <?php echo $options['sf_chapter_1']; ?>;
}
.sfm-theme-top .sfm-sidebar-bg,
.sfm-theme-bottom .sfm-sidebar-bg{
	width: <?php echo $width_panel_skew; ?>px;
}
/* Pos left */
.sfm-theme-top .sfm-sidebar-bg{
	-webkit-transform: translate3d(-<?php echo $width_panel_1 - $width_panel_skew_stroke; ?>px,0,0) skewX(-20.65deg);
	-moz-transform: translate3d(-<?php echo $width_panel_1 - $width_panel_skew_stroke; ?>px,0,0) skewX(-20.65deg);
	-ms-transform: translate3d(-<?php echo $width_panel_1 - $width_panel_skew_stroke; ?>px,0,0) skewX(-20.65deg);
	-o-transform: translate3d(-<?php echo $width_panel_1 - $width_panel_skew_stroke; ?>px,0,0) skewX(-20.65deg);
	transform: translate3d(-<?php echo $width_panel_1 - $width_panel_skew_stroke; ?>px,0,0) skewX(-20.65deg);
}
.sfm-theme-bottom .sfm-sidebar-bg{
	-webkit-transform: translate3d(-<?php echo $width_panel_skew - $width_panel_skew_stroke; ?>px,0,0) skewX(20.65deg);
	-moz-transform: translate3d(-<?php echo $width_panel_skew - $width_panel_skew_stroke; ?>px,0,0) skewX(20.65deg);
	-ms-transform: translate3d(-<?php echo $width_panel_skew - $width_panel_skew_stroke; ?>px,0,0) skewX(20.65deg);
	-o-transform: translate3d(-<?php echo $width_panel_skew - $width_panel_skew_stroke; ?>px,0,0) skewX(20.65deg);
	transform: translate3d(-<?php echo $width_panel_1 - $width_panel_skew_stroke; ?>px,0,0) skewX(20.65deg);
}
/* Pos right */
.sfm-pos-right .sfm-theme-top .sfm-sidebar-bg{
	-webkit-transform: translate3d(-<?php echo $width_panel_skew_stroke;  ?>px,0,0) skewX(20.65deg);
	-moz-transform: translate3d(-<?php echo $width_panel_skew_stroke;  ?>px,0,0) skewX(20.65deg);
	-ms-transform: translate3d(-<?php echo $width_panel_skew_stroke;  ?>px,0,0) skewX(20.65deg);
	-o-transform: translate3d(-<?php echo $width_panel_skew_stroke;  ?>px,0,0) skewX(20.65deg);
	transform: translate3d(-<?php echo $width_panel_skew_stroke;  ?>px,0,0) skewX(20.65deg);
}
.sfm-pos-right .sfm-theme-bottom .sfm-sidebar-bg{
	-webkit-transform: translate3d(-<?php echo $width_panel_skew_stroke; ?>px,0,0) skewX(-20.65deg);
	-moz-transform: translate3d(-<?php echo $width_panel_skew_stroke;  ?>px,0,0) skewX(-20.65deg);
	-ms-transform: translate3d(-<?php echo $width_panel_skew_stroke;  ?>px,0,0) skewX(-20.65deg);
	-o-transform: translate3d(-<?php echo $width_panel_skew_stroke;  ?>px,0,0) skewX(-20.65deg);
	transform: translate3d(-<?php echo $width_panel_skew_stroke;  ?>px,0,0) skewX(-20.65deg);
}
.sfm-sidebar-exposed.sfm-theme-top .sfm-sidebar-bg,
.sfm-sidebar-always .sfm-theme-top .sfm-sidebar-bg{
	-webkit-transform: translate3d(-<?php echo $width_panel_1/2 + 4; ?>px,0,0) skewX(-20.65deg);
	-moz-transform: translate3d(-<?php echo $width_panel_1/2 + 4; ?>px,0,0) skewX(-20.65deg);
	-ms-transform: translate3d(-<?php echo $width_panel_1/2 + 4; ?>px,0,0) skewX(-20.65deg);
	-o-transform: translate3d(-<?php echo $width_panel_1/2 + 4; ?>px,0,0) skewX(-20.65deg);
	transform: translate3d(-<?php echo $width_panel_1/2 + 4; ?>px,0,0) skewX(-20.65deg);
}
.sfm-pos-right .sfm-sidebar-exposed.sfm-theme-top .sfm-sidebar-bg,
.sfm-pos-right .sfm-sidebar-always .sfm-theme-top .sfm-sidebar-bg{
	-webkit-transform: translate3d(-<?php echo $width_panel_1; ?>px,0,0) skewX(20.65deg);
	-moz-transform: translate3d(-<?php echo $width_panel_1; ?>px,0,0) skewX(20.65deg);
	-ms-transform: translate3d(-<?php echo $width_panel_1; ?>px,0,0) skewX(20.65deg);
	-o-transform: translate3d(-<?php echo $width_panel_1; ?>px,0,0) skewX(20.65deg);
	transform: translate3d(-<?php echo $width_panel_1*0.5; ?>px,0,0) skewX(20.65deg);
}
.sfm-sidebar-exposed.sfm-theme-bottom .sfm-sidebar-bg,
.sfm-sidebar-always .sfm-theme-bottom .sfm-sidebar-bg{
	-webkit-transform: translate3d(0,0,0) skewX(20.65deg);
	-moz-transform: translate3d(0,0,0) skewX(20.65deg);
	-ms-transform: translate3d(0,0,0) skewX(20.65deg);
	-o-transform: translate3d(0,0,0) skewX(20.65deg);
	transform: translate3d(0,0,0) skewX(20.65deg);
}
.sfm-pos-right .sfm-sidebar-exposed.sfm-theme-bottom .sfm-sidebar-bg,
.sfm-pos-right .sfm-sidebar-always .sfm-theme-bottom .sfm-sidebar-bg{
	-webkit-transform: translate3d(-<?php echo $width_panel_1 + 4; ?>px,0,0) skewX(-20.65deg);
	-moz-transform: translate3d(-<?php echo $width_panel_1 + 4; ?>px,0,0) skewX(-20.65deg);
	-ms-transform: translate3d(-<?php echo $width_panel_1 + 4; ?>px,0,0) skewX(-20.65deg);
	-o-transform: translate3d(-<?php echo $width_panel_1 + 4; ?>px,0,0) skewX(-20.65deg);
	transform: translate3d(-<?php echo $width_panel_1 + 4; ?>px,0,0) skewX(-20.65deg);
}

/* Always visible */
.sfm-sidebar-always.sfm-theme-top .sfm-sidebar-bg{
	-webkit-transform: skewX(-20.65deg);
	-moz-transform: skewX(-20.65deg);
	-ms-transform: skewX(-20.65deg);
	-o-transform: skewX(-20.65deg);
	transform: skewX(-20.65deg);
}
.sfm-pos-right .sfm-sidebar-always.sfm-theme-top .sfm-sidebar-bg{
	-webkit-transform: skewX(20.65deg);
	-moz-transform: skewX(20.65deg);
	-ms-transform: skewX(20.65deg);
	-o-transform: skewX(20.65deg);
	transform: skewX(20.65deg);
}
.sfm-sidebar-always.sfm-theme-bottom .sfm-sidebar-bg{
	-webkit-transform: skewX(-160.65deg);
	-moz-transform: skewX(-160.65deg);
	-ms-transform: skewX(-160.65deg);
	-o-transform: skewX(-160.65deg);
	transform: skewX(-160.65deg);
}
.sfm-pos-right .sfm-sidebar-always.sfm-theme-bottom .sfm-sidebar-bg{
	-webkit-transform: skewX(160.65deg);
	-moz-transform: skewX(160.65deg);
	-ms-transform: skewX(160.65deg);
	-o-transform: skewX(160.65deg);
	transform: skewX(160.65deg);
}

.sfm-navicon,
.sfm-navicon:after,
.sfm-navicon:before,
.sfm-label-metro .sfm-navicon-button,
#sfm-mob-navbar {
	background-color: <?php echo $options['sf_label_color']; ?>;
}

.sfm-label-metro .sfm-navicon,
#sfm-mob-navbar .sfm-navicon,
.sfm-label-metro .sfm-navicon:after,
#sfm-mob-navbar .sfm-navicon:after,
.sfm-label-metro .sfm-navicon:before,
#sfm-mob-navbar .sfm-navicon:before  {
	background-color: <?php echo $options['sf_label_icon_color']; ?>;
}
.sfm-navicon-button .sf_label_icon{
	color: <?php echo $options['sf_label_icon_color']; ?>;
}

.sfm-label-square .sfm-navicon-button,
.sfm-label-rsquare .sfm-navicon-button,
.sfm-label-circle .sfm-navicon-button {
	color: <?php echo $options['sf_label_color']; ?>;
}

.sfm-navicon-button .sf_label_icon{
	width: <?php echo $options['sf_label_size']; ?>;
	height: <?php echo $options['sf_label_size']; ?>;
	font-size: calc(<?php echo $options['sf_label_size']; ?> * .6);
}
.sfm-navicon-button .sf_label_icon.la_icon_manager_custom{
	width: <?php echo $options['sf_label_size']; ?>;
	height: <?php echo $options['sf_label_size']; ?>;
}
.sfm-navicon-button.sf_label_default{
	width: <?php echo $options['sf_label_size']; ?>;
	height: <?php echo $options['sf_label_size']; ?>;
}

#sfm-sidebar [class*="sfm-icon-"] {
	color: <?php echo $options['sf_social_color']; ?>;
}

#sfm-sidebar .sfm-social li {
	border-color: <?php echo $options['sf_social_color']; ?>;
}

#sfm-sidebar .sfm-social a {
	color: <?php echo $options['sf_social_color']; ?>;
}

#sfm-sidebar .sfm-search-form {
	background-color: <?php echo $searchbg; ?>;
}

@font-face {
	font-family: 'sfm-icomoon';
	src:url('<?php echo plugins_url('/img/', __FILE__);?>fonts/icomoon.eot?wehgh4');
	src: url('<?php echo plugins_url('/img/', __FILE__);?>fonts/icomoon.svg?wehgh4#icomoon') format('svg'),
	url('<?php echo plugins_url('/img/', __FILE__);?>fonts/icomoon.eot?#iefixwehgh4') format('embedded-opentype'),
	url('<?php echo plugins_url('/img/', __FILE__);?>fonts/icomoon.woff?wehgh4') format('woff'),
	url('<?php echo plugins_url('/img/', __FILE__);?>fonts/icomoon.ttf?wehgh4') format('truetype');
	font-weight: normal;
	font-style: normal;
}

	#sfm-sidebar li:hover span[class*='fa-'] {
		opacity: 1 !important;
	}
<?php if( $options['sf_mob_nav'] === 'yes' ): ?>
	@media screen and (max-width: <?php echo $options['sf_threshold_point'];?>px) {
		#sfm-mob-navbar  {
			display: none;
			height: 62px;
			width: 100%;
			-webkit-backface-visibility: hidden;
			position: fixed;
			top: 0;
			left: 0;
			z-index: 999999;
			text-align: center;
			-webkit-transition: all 0.4s cubic-bezier(0.215, 0.061, 0.355, 1);
			-moz-transition:all 0.4s cubic-bezier(0.215, 0.061, 0.355, 1);
			-ms-transition: all 0.4s cubic-bezier(0.215, 0.061, 0.355, 1);
			-o-transition: all 0.4s cubic-bezier(0.215, 0.061, 0.355, 1);
			transition: all 0.4s cubic-bezier(0.215, 0.061, 0.355, 1);
			-webkit-transition-delay: .05s;
			transition-delay: .05s;
		}

		.sfm-rollback {
			display: none !important;
		}

		.superfly-on #sfm-mob-navbar  {
			display: block;
		}

		.sfm-mob-nav .sfm-rollback {
			display: none !important;
		}

		.sfm-mob-nav {
			margin-top: 62px !important;
		}

		#sfm-mob-navbar a {
			display: inline-block;
			min-width: 100px;
		}

		#sfm-mob-navbar img {
			height: 50px;
			display: inline-block;
			margin-top: 6px;
		}

		#sfm-mob-navbar .sfm-navicon-button {
			position: absolute;
			left: 0;
			top:0;
			padding: 30px 24px;
		}

		.sfm-pos-right #sfm-mob-navbar .sfm-navicon-button {
			right: 0;
			left: auto;
		}
		.sfm-navicon-button:after {
			font-size: 18px !important;
		}
	}
	<?php endif; ?>
<?php if(isset($options['sf_css'])): ?>
<?php echo $options['sf_css']; ?>
<?php endif; ?>
</style>


<?php if($options['sf_transition'] == 'yes'): ?>
	<style>
		body {
			display: none;
		}</style>
<?php endif; ?>
<script>

    (function (){
        var insertListener = function(event){
            if (event.animationName == "bodyArrived") {
                afterContentArrived();
            }
        }
        var timer, _timer;
        var transition = '<?php echo $options['sf_transition'] == 'yes'; ?>';

        if (document.addEventListener && false) {
            document.addEventListener("animationstart", insertListener, false); // standard + firefox
            document.addEventListener("MSAnimationStart", insertListener, false); // IE
            document.addEventListener("webkitAnimationStart", insertListener, false); // Chrome + Safari
        } else {
            timer = setInterval(function(){
                if (document.body) { //
                    clearInterval(timer);
                    afterContentArrived();
                }
            },14);
        }

        function afterContentArrived() {
            clearTimeout(_timer);

            if (window.jQuery) {
                jQuery('body').fadeIn();
                jQuery(document).trigger('sfm_doc_body_arrived');
                window.SFM_EVENT_DISPATCHED = true;
            } else {
                _timer = setTimeout(function(){
                    afterContentArrived();
                },14);
            }
        }
    })()
</script>

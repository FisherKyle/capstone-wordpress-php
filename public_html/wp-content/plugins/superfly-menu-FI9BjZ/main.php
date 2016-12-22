<?php
/*
Plugin Name: Superfly Menu
Plugin URI: http://superfly.looks-awesome.com
Description: Off-canvas hamburger menu for WordPress
Version: 4.1.4
Author: Looks Awesome
Author URI: http://looks-awesome.com
License: Commercial License
Text Domain: superfly-menu
Domain Path: /lang
*/

global $sf_options;

$plugin = array(
    'name' => 'superfly',
    'label' => 'Superfly',
    'prefix' => 'sf',
    'demo_site' => 'http://superfly.looks-awesome.com',
    'version' => '4.1.4',
    'require_php' => '5.3.0',
    'require_ext' => array('zip'),
    'namespace' => 'Superfly',
    'base' => '',
    'base_url' => '',
    'mode' => ''
);

require __DIR__ . '/includes/Activator.php';

$activator = new LA_Supefly_App_Activator($plugin);
add_action('admin_init', array('LA_Supefly_App_Activator', 'check'));
if (!LA_Supefly_App_Activator::compatible()) {
    return;
}

register_activation_hook(__FILE__, 'LA_Supefly_App_Activator::activate');

if (!defined('SF_VERSION_KEY')) {
    define('SF_VERSION_KEY', 'SF_version');
}

if (!defined('SF_VERSION_NUM')) {
    define('SF_VERSION_NUM', '4.1.4');
}

if (!defined('SF_ITEM_META')) {
    define('SF_ITEM_META', '_sf_settings');
}

require __DIR__ . '/includes/vendor/looks_awesome/envato_api/Purchase.php';

add_option(SF_VERSION_KEY, SF_VERSION_NUM);

load_plugin_textdomain('superfly-menu', false, basename(dirname(__FILE__)) . '/lang');

include_once(dirname(__FILE__) . '/settings.php');
if (!class_exists('LA_IconManager')) {
    include_once(dirname(__FILE__) . '/includes/vendor/looks_awesome/icon_manager/IconManager.php');
}
if (!class_exists('LA_GoogleFonts')) {
    include_once(dirname(__FILE__) . '/includes/vendor/looks_awesome/google_fonts/GoogleFonts.php');
}
$fonts = trailingslashit(plugin_dir_path(__FILE__) . 'fonts');
$sf_la_icon_manager = LA_IconManager::getInstance($fonts);

register_activation_hook(__FILE__, array($sf_la_icon_manager, 'addDefaultFonts'));
register_deactivation_hook(__FILE__, 'LA_IconManager::deleteOption');

$env = array(
    'mode' => 'dev',
    'google_api_key' => '',
);
if (file_exists(plugin_dir_path(__FILE__) . 'env.json')) {
    $env = json_decode(file_get_contents(plugin_dir_path(__FILE__) . 'env.json'), true);
    if (!defined('SF_MODE')) {
        $mode = $env['mode'] ? $env['mode'] : 'dev';
        define('SF_MODE', $mode);
    }
    if (!defined('SF_GOOGLE_API_KEY') && $env['mode'] == 'dev') {
        $key = $env['google_api_key'] ? $env['google_api_key'] : '';
        define('SF_GOOGLE_API_KEY', $key);
    }
}

add_action('wp_enqueue_scripts', 'sf_scripts');

add_action('admin_menu', 'sf_menu');

function sf_menu()
{
    add_menu_page(
        'Superfly Menu Options',
        'Superfly Menu',
        'manage_options',
        'superfly-menu-options',
        'sf_page',
        'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+PHN2ZyAgIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIgICB4bWxuczpjYz0iaHR0cDovL2NyZWF0aXZlY29tbW9ucy5vcmcvbnMjIiAgIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyIgICB4bWxuczpzdmc9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiAgIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgICB4bWxuczpzb2RpcG9kaT0iaHR0cDovL3NvZGlwb2RpLnNvdXJjZWZvcmdlLm5ldC9EVEQvc29kaXBvZGktMC5kdGQiICAgeG1sbnM6aW5rc2NhcGU9Imh0dHA6Ly93d3cuaW5rc2NhcGUub3JnL25hbWVzcGFjZXMvaW5rc2NhcGUiICAgd2lkdGg9IjI4LjIwMjA2M21tIiAgIGhlaWdodD0iMjEuNDQ4ODg3bW0iICAgdmlld0JveD0iMCAwIDk5LjkyODU2OCA3NS45OTk5OTMiICAgaWQ9InN2ZzIiICAgdmVyc2lvbj0iMS4xIiAgIGlua3NjYXBlOnZlcnNpb249IjAuOTEgcjEzNzI1IiAgIHNvZGlwb2RpOmRvY25hbWU9Im1lbnUtaWNvbi1pbmsuc3ZnIj4gIDxkZWZzICAgICBpZD0iZGVmczQiIC8+ICA8c29kaXBvZGk6bmFtZWR2aWV3ICAgICBpZD0iYmFzZSIgICAgIHBhZ2Vjb2xvcj0iI2ZmZmZmZiIgICAgIGJvcmRlcmNvbG9yPSIjNjY2NjY2IiAgICAgYm9yZGVyb3BhY2l0eT0iMS4wIiAgICAgaW5rc2NhcGU6cGFnZW9wYWNpdHk9IjAuMCIgICAgIGlua3NjYXBlOnBhZ2VzaGFkb3c9IjIiICAgICBpbmtzY2FwZTp6b29tPSIzLjk1OTc5OCIgICAgIGlua3NjYXBlOmN4PSI2MS4wMTgwMzkiICAgICBpbmtzY2FwZTpjeT0iMzMuNzAyMTgiICAgICBpbmtzY2FwZTpkb2N1bWVudC11bml0cz0icHgiICAgICBpbmtzY2FwZTpjdXJyZW50LWxheWVyPSJsYXllcjEiICAgICBzaG93Z3JpZD0idHJ1ZSIgICAgIGlua3NjYXBlOndpbmRvdy13aWR0aD0iMTkyMCIgICAgIGlua3NjYXBlOndpbmRvdy1oZWlnaHQ9IjEwMTciICAgICBpbmtzY2FwZTp3aW5kb3cteD0iLTgiICAgICBpbmtzY2FwZTp3aW5kb3cteT0iLTgiICAgICBpbmtzY2FwZTp3aW5kb3ctbWF4aW1pemVkPSIxIiAgICAgZml0LW1hcmdpbi10b3A9IjAiICAgICBmaXQtbWFyZ2luLWxlZnQ9IjAiICAgICBmaXQtbWFyZ2luLXJpZ2h0PSIwIiAgICAgZml0LW1hcmdpbi1ib3R0b209IjAiPiAgICA8aW5rc2NhcGU6Z3JpZCAgICAgICB0eXBlPSJ4eWdyaWQiICAgICAgIGlkPSJncmlkMzMzOCIgICAgICAgb3JpZ2lueD0iMCIgICAgICAgb3JpZ2lueT0iLTIuMTM0NzI2M2UtMDA1IiAvPiAgPC9zb2RpcG9kaTpuYW1lZHZpZXc+ICA8bWV0YWRhdGEgICAgIGlkPSJtZXRhZGF0YTciPiAgICA8cmRmOlJERj4gICAgICA8Y2M6V29yayAgICAgICAgIHJkZjphYm91dD0iIj4gICAgICAgIDxkYzpmb3JtYXQ+aW1hZ2Uvc3ZnK3htbDwvZGM6Zm9ybWF0PiAgICAgICAgPGRjOnR5cGUgICAgICAgICAgIHJkZjpyZXNvdXJjZT0iaHR0cDovL3B1cmwub3JnL2RjL2RjbWl0eXBlL1N0aWxsSW1hZ2UiIC8+ICAgICAgICA8ZGM6dGl0bGU+PC9kYzp0aXRsZT4gICAgICA8L2NjOldvcms+ICAgIDwvcmRmOlJERj4gIDwvbWV0YWRhdGE+ICA8ZyAgICAgaW5rc2NhcGU6bGFiZWw9IkxheWVyIDEiICAgICBpbmtzY2FwZTpncm91cG1vZGU9ImxheWVyIiAgICAgaWQ9ImxheWVyMSIgICAgIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAsLTk3Ni4zNjIxOSkiPiAgICA8cmVjdCAgICAgICBzdHlsZT0iZmlsbDojMDAwMDAwO2ZpbGwtb3BhY2l0eToxO2ZpbGwtcnVsZTpldmVub2RkO3N0cm9rZTojMDAwMDAwO3N0cm9rZS13aWR0aDowLjIyNzY0MjYzcHg7c3Ryb2tlLWxpbmVjYXA6YnV0dDtzdHJva2UtbGluZWpvaW46bWl0ZXI7c3Ryb2tlLW9wYWNpdHk6MSIgICAgICAgaWQ9InJlY3QzMzQyIiAgICAgICB3aWR0aD0iOTkuNzAwOTI4IiAgICAgICBoZWlnaHQ9IjUuMjM2NjQzMyIgICAgICAgeD0iMC4xMTM4MjEzMSIgICAgICAgeT0iOTc2LjQ3NjAxIiAvPiAgICA8cmVjdCAgICAgICBzdHlsZT0iZmlsbDojMDAwMDAwO2ZpbGwtb3BhY2l0eToxO2ZpbGwtcnVsZTpldmVub2RkO3N0cm9rZTojMDAwMDAwO3N0cm9rZS13aWR0aDowLjIyNzY0MjYzcHg7c3Ryb2tlLWxpbmVjYXA6YnV0dDtzdHJva2UtbGluZWpvaW46bWl0ZXI7c3Ryb2tlLW9wYWNpdHk6MSIgICAgICAgaWQ9InJlY3QzMzQyLTgiICAgICAgIHdpZHRoPSI5OS43MDA5MjgiICAgICAgIGhlaWdodD0iNS4yMzY2NDMzIiAgICAgICB4PSIwLjExMzgyMTMxIiAgICAgICB5PSIxMDExLjY1NDYiIC8+ICAgIDxyZWN0ICAgICAgIHN0eWxlPSJmaWxsOiMwMDAwMDA7ZmlsbC1vcGFjaXR5OjE7ZmlsbC1ydWxlOmV2ZW5vZGQ7c3Ryb2tlOiMwMDAwMDA7c3Ryb2tlLXdpZHRoOjAuMjI3NjQyNjNweDtzdHJva2UtbGluZWNhcDpidXR0O3N0cm9rZS1saW5lam9pbjptaXRlcjtzdHJva2Utb3BhY2l0eToxIiAgICAgICBpZD0icmVjdDMzNDItNSIgICAgICAgd2lkdGg9Ijk5LjcwMDkyOCIgICAgICAgaGVpZ2h0PSI1LjIzNjY0MzMiICAgICAgIHg9IjAuMTEzODIxMzEiICAgICAgIHk9IjEwNDcuMDExNyIgLz4gIDwvZz48L3N2Zz4=',
        22
    );
}

add_action('admin_print_styles-nav-menus.php', 'sf_menus_admin');

/**
 * Appearance/Menus page in the WP Admin
 */
function sf_menus_admin()
{
    if (SF_MODE === 'dev') {
        wp_enqueue_script(
            'sf-menus-admin-js',
            plugins_url('/js/menu-admin.js', __FILE__),
            array('jquery', 'wp-color-picker'),
            SF_VERSION_NUM,
            true
        );
        wp_enqueue_script('awesome-ajax', plugins_url('/js/vendor/looks_awesome/common/ajax.js', __FILE__));
        wp_enqueue_script('awesome-util', plugins_url('/js/vendor/looks_awesome/common/util.js', __FILE__));
        wp_enqueue_style('sf-admin-font-awesome', plugins_url('/css/fa.min.css', __FILE__));
        wp_enqueue_style('sf-menus-admin-css', plugins_url('/css/menu-admin.css', __FILE__));
        $handle = 'awesome-ajax';
    } else {
        wp_enqueue_script(
            'sf-menus-admin-js',
            plugins_url('/js/admin_menu.min.js', __FILE__),
            array('jquery', 'wp-color-picker'),
            SF_VERSION_NUM
        );
        wp_enqueue_style(
            'sf-menus-admin-css',
            plugins_url('/css/admin_menu.min.css', __FILE__),
            array(),
            SF_VERSION_NUM,
            'all'
        );
        $handle = 'sf-menus-admin-js';
    }

    $fonts = trailingslashit(plugin_dir_path(__FILE__) . 'fonts');
    $sf_la_icon_manager = LA_IconManager::getInstance($fonts);
    $sf_la_icon_manager->enqueueAdminScripts();
    wp_enqueue_media();
    wp_enqueue_style('lato-font', '//fonts.googleapis.com/css?family=Lato:300,400,600');
    wp_enqueue_style('wp-color-picker');


    $sf_menu_data = sf_get_menus_data();

    wp_localize_script('sf-menus-admin-js', 'sf_menus_data', $sf_menu_data);
    wp_localize_script(
        $handle,
        'sf_menus_meta',
        array(
            'ajax_url' => admin_url('admin-ajax.php'),
        )
    );

    // Icon Manager
    wp_localize_script(
        $handle,
        'laim_localize',
        array(
            'ajax_nonce' => wp_create_nonce('sf'),
            'ajaxurl' => admin_url('admin-ajax.php'),
        )
    );
}


add_action('wp_ajax_sf_save_item', 'sf_save_item_fn');
add_action('wp_ajax_sf_validate_envato_license', 'sf_validate_envato_license_fn');

function sf_validate_envato_license_fn()
{
    if (isset($_POST['code'])) {
        $json = EnvatoApi\Purchase::verify($_POST['code']);

        if (is_object($json)) {
            wp_send_json($json);
        }
    }
}

function sf_save_item_fn()
{
    global $wpdb;

    $menu_item_id = $_POST['id'];

    $serialized_settings = $_POST['settings'];

    $result = update_post_meta($menu_item_id, SF_ITEM_META, $serialized_settings);

    $response = array();

//	$response['settings'] = $settings;
    $response['settings'] = $serialized_settings;
    $response['menu_item_id'] = $menu_item_id;
    $response['meta'] = $result;

    echo json_encode($response);
    die();
}

function register_sf_widget_area()
{

    if (function_exists('register_sidebar')) {
        register_sidebar(
            array(
                'name' => 'Superfly Widget Area',
                'id' => 'sf_sidebar_widget_area',
                'description' => __(
                    'Widgets in this area will be shown in Superfly sidebar under the menu',
                    'superfly-menu'
                ),
                'before_widget' => '<div class="widget">',
                'after_widget' => '</div>',
                'before_title' => '<h1 class="title">',
                'after_title' => '</h1>',
            )
        );
    }

}

add_action('wp_loaded', 'register_sf_widget_area');

function sf_get_menus_data($menu_id = -1)
{

    if ($menu_id == -1) {
        global $nav_menu_selected_id;
        $menu_id = $nav_menu_selected_id;
    }

    if ($menu_id == 0) {
        return array();
    }

    $sf_menus_data = array();
    $menu_items = wp_get_nav_menu_items($menu_id, array('post_status' => 'any'));

    if (is_array($menu_items)) {
        foreach ($menu_items as $item) {
            $_item_settings = sf_get_menu_item_data($item->ID);
            if ($_item_settings != '') {
                $sf_menus_data[$item->ID] = $_item_settings;
            }
        }
    }

    //shiftp( $sf_menus_data );
    return $sf_menus_data;
}

function sf_get_menu_item_data($item_id)
{
    $meta = get_post_meta($item_id, SF_ITEM_META, true);

    //Add URL for image
    if (!empty($meta['item_image'])) {
        $src = wp_get_attachment_image_src($meta['item_image']);
        if ($src) {
            $meta['item_image_url'] = $src[0];
            $meta['item_image_edit'] = get_edit_post_link($meta['item_image'], 'raw');
        }
    }

    return $meta;
}

/**
 * Settings page in the WP Admin
 */
function sf_page()
{

    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.', 'superfly-menu'));
    }

    if (SF_MODE === 'dev') {
        // load fresh Google Fonts list
        add_filter('styles_google_font_api', create_function('', "return '" . SF_GOOGLE_API_KEY . "';"));
        $google_fonts = new LA_GoogleFonts();

        wp_enqueue_script(
            'sf_admin',
            plugins_url('/js/admin.js', __FILE__),
            array('jquery', 'underscore', 'backbone', 'fontselect'),
            SF_VERSION_NUM
        );
        wp_enqueue_script(
            'backbone-stickit',
            plugins_url('/js/vendor/backbone/backbone.stickit.js', __FILE__),
            array(),
            '0.9.2'
        );
        wp_enqueue_script('sf_admin_bindings', plugins_url('/js/admin-bindings.js', __FILE__), array('backbone-stickit'), SF_VERSION_NUM);
        wp_enqueue_script('tinycolor', plugins_url('/js/tinycolor.js', __FILE__), array(), SF_VERSION_NUM);
        wp_enqueue_script(
            'sf_colorpickersliders',
            plugins_url('/js/jquery.colorpickersliders.js', __FILE__),
            array(),
            SF_VERSION_NUM
        );
        wp_enqueue_script('awesome-ajax', plugins_url('/js/vendor/looks_awesome/common/ajax.js', __FILE__));
        wp_enqueue_script('awesome-util', plugins_url('/js/vendor/looks_awesome/common/util.js', __FILE__));
        wp_enqueue_script(
            'fontselect',
            plugins_url('/js/vendor/tommoor/fontselect-jquery-plugin/jquery.fontselect.js', __FILE__)
        );
        $handle = 'awesome-ajax';

        wp_enqueue_style('colorpickersliders-ui-css', plugins_url('/css/jquery.colorpickersliders.css', __FILE__));
        wp_enqueue_style(
            'fontselect-css',
            plugins_url('/js/vendor/tommoor/fontselect-jquery-plugin/fontselect.css', __FILE__)
        );
        wp_enqueue_style('sf-admin-css', plugins_url('/css/admin.css', __FILE__));
    } else {
        wp_enqueue_script('sf-admin-js', plugins_url('/js/admin.min.js', __FILE__), array(), SF_VERSION_NUM);
        wp_enqueue_style('sf-admin-css', plugins_url('/css/admin.min.css', __FILE__), array(), SF_VERSION_NUM, 'all');
        $handle = 'sf-admin-js';
    }

    wp_enqueue_media();
    wp_enqueue_script('wp-ajax-response');
    wp_enqueue_style('lato-font', '//fonts.googleapis.com/css?family=Lato:300,400');

    $fonts = trailingslashit(plugin_dir_path(__FILE__) . 'fonts');
    $sf_la_icon_manager = LA_IconManager::getInstance($fonts);
    $sf_la_icon_manager->enqueueAdminScripts();

    wp_localize_script(
        $handle,
        'laim_localize',
        array(
            'ajax_nonce' => wp_create_nonce('sf'),
            'ajaxurl' => admin_url('admin-ajax.php'),
        )
    );

    include_once(dirname(__FILE__) . '/options-page.php');
}


add_filter('plugin_action_links_superfly-menu/main.php', 'sf_plugin_action_links', 10, 1);

function sf_plugin_action_links($links)
{
    $settings_page = add_query_arg(array('page' => 'superfly-menu-options'), admin_url('options-general.php'));
    $settings_link = '<a href="' . esc_url($settings_page) . '">' . __('Settings', 'sf') . '</a>';
    array_unshift($links, $settings_link);

    return $links;
}

add_action('wp_head', 'sf_dynamic_styles', 10);
add_action('wp_head', 'sf_main_html_template', 10);

function sf_main_html_template()
{
    global $sf_show;

    $options = sf_get_options();
    if ($options['sf_test_mode'] === 'yes' && !current_user_can('manage_options')) {
        return;
    }

    if (isset($sf_show) && $sf_show && empty($_GET["cornerstone"])) {

        ob_start();
        include_once(dirname(__FILE__) . '/superfly-menu.php');
        $superflyHTML = ob_get_contents();
        ob_end_clean();

        echo "<script type='text/javascript'>var SFM_template =" . json_encode($superflyHTML) . "</script>";
    }
}

function sf_main_html2($content)
{
    global $sf_show;
    global $sf_menu_data;

    $options = sf_get_options();
    if ($options['sf_test_mode'] === 'yes' && !current_user_can('manage_options')) {
        return;
    }
    $superflyHTML = '';

    if (isset($sf_show) && $sf_show && empty($_GET["cornerstone"])) {
        ob_start();
        include_once(dirname(__FILE__) . '/superfly-menu.php');
        $superflyHTML = ob_get_contents();
        ob_end_clean();
    }

    return $superflyHTML . $content;
}

function sf_dynamic_styles()
{
    global $sf_show;
    global $current_page_sf_menu;

    $options = sf_get_options();
    if ($options['sf_test_mode'] === 'yes' && !current_user_can('manage_options')) {
        return;
    }

    if (isset($sf_show) && $sf_show && isset($current_page_sf_menu)) {

        include_once(dirname(__FILE__) . '/superfly-dynamic-styles.php');
    }
}

function sf_scripts()
{
    global $sf_show;
    global $sf_menu_data;
    global $current_page_sf_menu;

    $options = sf_get_options();
    if ($options['sf_test_mode'] === 'yes' && !current_user_can('manage_options')) {
        return;
    }

    $post_id = get_queried_object_id();
    $ismobile = wp_is_mobile();
    $sf_show = sf_check_display_rule(json_decode($options['sf_display']), $ismobile, $post_id);

    //sf_debug_to_console($sf_show);

    if ($sf_show && isset($current_page_sf_menu)) {
        if (SF_MODE === 'dev' || $options['sf_dev']) {
            wp_enqueue_script(
                'sf_main',
                plugins_url('/js/superfly-menu.js', __FILE__),
                array('jquery'),
                SF_VERSION_NUM,
                false
            );
            wp_enqueue_style('sf_styles', plugins_url('/css/superfly-menu.css', __FILE__));
        } else {
            wp_enqueue_script(
                'sf_main',
                plugins_url('/js/public.min.js', __FILE__),
                array('jquery'),
                SF_VERSION_NUM,
                false
            );
            wp_enqueue_style('sf_styles', plugins_url('/css/public.min.css', __FILE__));
        }

        $social = array();
        $networks = array(
            'facebook',
            'twitter',
            'linkedin',
            'gplus',
            'instagram',
            'pinterest',
            'flickr',
            'dribbble',
            'youtube',
            'vimeo',
            'soundcloud',
            'email',
            'skype',
            'rss',
        );

        foreach ($networks as $network) {
            if (!empty($options['sf_' . $network])) {
                $social[$network] = $options['sf_' . $network];
            }
        }

        $sf_menu_data = sf_get_menus_data($current_page_sf_menu);

        $fonts = trailingslashit(plugin_dir_path(__FILE__) . 'fonts');
        $sf_la_icon_manager = LA_IconManager::getInstance($fonts);
        $sf_la_icon_manager->enqueuePublicScripts();

        wp_localize_script(
            'sf_main',
            'SF_Opts',
            array(
                'social' => $social,
                'search' => $options['sf_search'],
                'blur' => $options['sf_blur_content'],
                'fade' => $options['sf_transition'],
                'test_mode' => $options['sf_test_mode'],
                'hide_def' => $options['sf_hide_def'],
                'mob_nav' => $options['sf_mob_nav'],
                'sidebar_style' => $options['sf_sidebar_style'],
                'sidebar_behaviour' => $options['sf_sidebar_behaviour'],
                'alt_menu' => $options['sf_alternative_menu'],
                'sidebar_pos' => $options['sf_sidebar_pos'],
                'width_panel_1' => ($options['sf_sidebar_style'] == 'toolbar' ? 100 : $options['sf_width_panel_1']),
                'width_panel_2' => $options['sf_width_panel_2'],
                'width_panel_3' => $options['sf_width_panel_3'],
                'width_panel_4' => $options['sf_width_panel_4'],
                'base_color' => $options['sf_bg_color_panel_1'],
                'opening_type' => $options['sf_opening_type'],
                'sub_type' => $options['sf_sub_type'],
                'sub_opening_type' => $options['sf_sub_opening_type'],
                'label' => $options['sf_label_style'],
                'label_top' => $options['sf_label_top'],
                'label_size' => $options['sf_label_size'],
                'label_vis' => $options['sf_label_vis'],
                'item_padding' => $options['sf_padding'],
                'bg' => $options['sf_image_bg'],
                'path' => plugins_url('/img/', __FILE__),
                'menu' => $options['sf_active_menu'],
                'togglers' => $options['sf_togglers'],
                'subMenuSupport' => ($ismobile && $options['sf_submenu_mob'] === 'no' || $options['sf_sidebar_style'] === 'skew' ? false : $options['sf_submenu_support']),
                'subMenuSelector' => $options['sf_submenu_classes'],
                'activeClassSelector' => 'current-menu-item',
                'allowedTags' => 'DIV, NAV, UL, OL, LI, A, P, H1, H2, H3, H4, SPAN',
                'menuData' => $sf_menu_data,
                'siteBase' => site_url(),
                'plugin_ver' => SF_VERSION_NUM,
            )
        );

        if ($options['sf_font'] !== 'inherit') {
            wp_register_style('sf-google-font', '//fonts.googleapis.com/css?family=' . $options['sf_font']);
            wp_enqueue_style('sf-google-font');
        }

        if ($options['sf_c_font'] !== 'inherit') {
            wp_register_style('sf-google-font-subheader', '//fonts.googleapis.com/css?family=' . $options['sf_c_font']);
            wp_enqueue_style('sf-google-font-subheader');
        }
    }
}

function sf_is_mobile()
{
    return preg_match(
        "/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i",
        $_SERVER["HTTP_USER_AGENT"]
    );
}

function sf_get_lang_id($id, $type = 'page')
{
    if (function_exists('icl_object_id')) {
        $id = icl_object_id($id, $type, true);
    }

    return $id;
}

function sf_check_location($opt, $post_id)
{

    global $current_page_sf_menu;

    if (is_home()) {


        $show = isset($opt->location->wp_pages->home);
        $current_page_sf_menu = check_against_rule('wp_pages', 'home', $post_id);
        if (!$show && $post_id) {
            $show = isset($opt->location->pages->$post_id);
            $current_page_sf_menu = check_against_rule('pages', $post_id, $post_id);
        }

        // check if blog page is front page too
        if (!$show && is_front_page() /*&& isset($opt['page-front'])*/) {
            $show = isset($opt->location->wp_pages->front);
            $current_page_sf_menu = check_against_rule('wp_pages', 'front', $post_id);
        }

    } else {
        if (is_front_page()) {

            $show = isset($opt->location->wp_pages->front);
            $current_page_sf_menu = check_against_rule('wp_pages', 'front', $post_id);

            if (!$show && $post_id) {
                $show = isset($opt->location->pages->$post_id);
                $current_page_sf_menu = check_against_rule('pages', $post_id, $post_id);
            }
        } else {
            if (is_category()) {
                //browser()->log  ( 'cat' );
                //browser()->log  ( get_query_var('cat') );
                $catid = get_query_var('cat');
                $show = isset($opt->location->cats->$catid);
                $current_page_sf_menu = check_against_rule('cats', $catid, $post_id);

            } /*else if ( is_tax() ) {
				$term = get_queried_object();
				$tax = $term->taxonomy;
				$show = isset($opt->location->cats->$tax);
				unset($term);
				unset($tax);
			} else if ( is_post_type_archive() ) {
				$type = get_post_type();
				$show = isset($opt['type-'. $type .'-archive']) ? $opt['type-'. $type .'-archive'] : false;
			}*/ else {
                if (is_archive()) {
                    //browser()->log  ( 'archive' );

                    $show = isset($opt->location->wp_pages->archive);
                    $current_page_sf_menu = check_against_rule('wp_pages', 'archive', $post_id);

                } else {
                    if (is_single()) {
                        //browser()->log  ( 'single' );

                        $type = get_post_type();
                        $show = isset($opt->location->wp_pages->single);
                        $current_page_sf_menu = check_against_rule('wp_pages', 'single', $post_id);
                        if (!$show && $type != 'page' && $type != 'post') {
                            $show = isset($opt->location->cposts->$type);
                            $current_page_sf_menu = check_against_rule('cposts', $type, $post_id);
                        }

                        if (!$show) {
                            $cats = get_the_category();
                            foreach ($cats as $cat) {
                                if ($show) {
                                    break;
                                }
                                $c_id = sf_get_lang_id($cat->cat_ID, 'category');
                                $show = isset($opt->location->cats->$c_id);
                                $current_page_sf_menu = check_against_rule('cats', $c_id, $post_id);
                                unset($c_id);
                                unset($cat);
                            }
                        }

                    } else {
                        if (is_404()) {
                            $show = isset($opt->location->wp_pages->forbidden);
                            $current_page_sf_menu = check_against_rule('wp_pages', 'forbidden', $post_id);

                        } else {
                            if (is_search()) {
                                $show = isset($opt->location->wp_pages->search);
                                $current_page_sf_menu = check_against_rule('wp_pages', 'search', $post_id);

                            } else {
                                if ($post_id) {
                                    $show = isset($opt->location->pages->$post_id);
                                    $current_page_sf_menu = check_against_rule('pages', $post_id, $post_id);

                                } else {
                                    $show = false;
                                    $current_page_sf_menu = check_against_rule('pages', '', $post_id);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    if ($post_id && !$show && isset($opt->location->ids) && !empty($opt->location->ids)) {

        $other_ids = $opt->location->ids;

        foreach ($other_ids as $other_id) {
            if ($post_id == (int)$other_id) {
                $show = true;
            }
        }
    }

    if (!$show && defined('ICL_LANGUAGE_CODE')) {
        // check for WPML widgets
        $lang = ICL_LANGUAGE_CODE;
        $show = isset($opt->location->langs->$lang);
//        $current_page_sf_menu = check_against_rule('langs', $lang);

    }


    if (!isset($show)) {
        //browser()->log  ( '!isset($show)' );
        $show = false;
    }


    return $show;
}

function check_against_rule($first_level, $sec_level, $post_id = false)
{
    $options = sf_get_options();
    $locations = json_decode($options['sf_active_menu']);

    $isDef = false;

    if (gettype($locations) == 'object') { /* migration */
        foreach ($locations as $menu_object) {


            $current_loc = $menu_object->loc;


            if (isset($current_loc->$first_level->$sec_level)) {
                return $menu_object->term_id;
            }
            if (isset($menu_object->isDef) && $menu_object->isDef) {
                $isDef = $menu_object->term_id;
            }
            if (isset($post_id) && isset($current_loc->ids) && !empty($current_loc->ids)) {
                $other_ids = $current_loc->ids;

                foreach ($other_ids as $other_id) {
                    if ($post_id == (int)$other_id) {
                        return $menu_object->term_id;
                    }
                }
            }
        }
    }

    if ($isDef) {
        return $isDef;
    }
}

function sf_check_display_rule($opt, $isMobile, $post_id)
{

    $show = sf_check_location($opt, $post_id);

    if ($show && $opt->rule->exclude || !$show && $opt->rule->include) {
        $show = false;
    } else {
        $show = true;
    }

    $user_ID = is_user_logged_in();

    if (($opt->user->loggedout && $user_ID) || ($opt->user->loggedin && !$user_ID)) {
        $show = false;
    }

    if ($opt->mobile->no && $isMobile) {
        $show = false;
    }

    if ($opt->desktop->no && !$isMobile) {
        $show = false;
    }

    return $show;
}

function sf_debug_to_console($data)
{
    if (is_array($data) || is_object($data)) {
        echo("<script>console.log('PHP: " . json_encode($data) . "');</script>");
    } else {
        echo("<script>console.log('PHP: " . $data . "');</script>");
    }
}

function sf_deparam($query)
{
    $map = array();

    if (!empty($query)) {
        $pairs = explode('&', $query);
        $len = sizeof($pairs);
        for ($i = 0; $i < $len; $i += 1) {
            $keyValuePair = explode('=', $pairs[$i]);
            $key = $keyValuePair[0];
            $value = (sizeof($keyValuePair) > 1) ? $keyValuePair[1] : null;
            $map[$key] = $value;
        }
    }

    return $map;
}

function sf_convert_symbols_custom($text)
{
    $current_user = wp_get_current_user();

    // Logic {{username}}
    if (strpos($text, '{{username}}') !== false) {
        $username = $current_user->display_name;
        $text = str_replace('{{username}}', $username, $text);
    }

    return $text;
}

add_filter('sf_convert_symbols', 'sf_convert_symbols_custom', 10, 1);
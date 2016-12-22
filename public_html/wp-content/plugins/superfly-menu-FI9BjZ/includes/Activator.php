<?php

class LA_Supefly_App_Activator
{
    protected static $plugin;
    protected static $errors = array();

    public function __construct($plugin)
    {
        $this->define_static_vars($plugin);
    }

    /**
     * Define static variables
     *
     * @param $plugin
     */
    protected function define_static_vars($plugin)
    {
        foreach ($plugin as $key => $value) {
            self::$plugin[$key] = $value;
        }
    }

    /**
     * Display error message after activation fail
     */
    public static function admin_message()
    {
        foreach (self::$errors as $k => $v){
            echo "<div class='error'> <p>{$v}</p></div>";
        }
    }

    /**
     * Activate plugin if PHP version compatible
     */
    public static function activate()
    {
        if (!self::compatible()) {
            add_action('admin_notices', 'LA_Supefly_App_Activator::admin_message');
            deactivate_plugins(self::$plugin['name']);
        }
    }

    /**
     * Compare PHP versions
     *
     * @return bool
     */
    public static function compatible_version()
    {
        if (version_compare(PHP_VERSION, self::$plugin['require_php']) == -1) {
            self::$errors['php'] = sprintf(
                __('Plugin <b>%1$s</b> require PHP version %2$s or higher.'),
                self::$plugin['label'],
                self::$plugin['require_php']
            );
        }
    }

    /**
     * Check PHP extensions
     *
     * @return bool
     */
    public static function compatible_extensions()
    {
        foreach (self::$plugin['require_ext'] as $ext) {
            if (!extension_loaded($ext)) {
                self::$errors[$ext] = sprintf(
                    __('Plugin <b>%1$s</b> require PHP %2$s extesnion enabled.'),
                    self::$plugin['label'],
                    strtoupper($ext)
                );
            }
        }
    }

    public static function compatible()
    {
        self::compatible_version();
        self::compatible_extensions();
        if(count(self::$errors) > 0){
            return false;
        }
        return true;
    }

    /**
     * If plugin already enabled in some way
     *
     * Check PHP version to prevent WP admin crash and deactivate plugin
     */
    public static function check()
    {
        if (!self::compatible()) {
            $plugin = self::$plugin['name'] . '/main.php';
            if (is_plugin_active($plugin)) {
                deactivate_plugins($plugin);
            }
            add_action('admin_notices', 'LA_Supefly_App_Activator::admin_message');
            if (isset($_GET['activate'])) {
                unset($_GET['activate']);
            }
        }
    }
}
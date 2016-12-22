<?php

include_once ABSPATH.'/wp-admin/includes/class-wp-filesystem-base.php';

/**
 * Class LA_IconManager
 */
class LA_IconManager
{
    protected $font_name;
    protected $svg_font;
    protected $paths;
    protected $svg_file;
    protected $json_file;
    protected $response;
    protected $error;
    protected $version = '1.0,0';

    private static $instance = null;
    private static $dir;
    private static $option = 'la_icon_fonts';
    private static $filters = array('\.eot', '\.svg', '\.ttf', '\.woff', '\.json', 'style\.css');

    /**
     * LA_IconManager constructor.
     * @param string $fonts
     */
    public function __construct($fonts = '')
    {
        $default_fonts = $fonts !== '' && is_dir($fonts) ? $fonts : trailingslashit(plugin_dir_path(__FILE__).'default_fonts');
        $this->response = new \WP_Ajax_Response;
        $this->paths = wp_upload_dir();
        $this->paths['default_fonts'] = $default_fonts;
        $this->paths['icon_sets'] = trailingslashit($this->paths['basedir']).'la_icon_sets';
        $this->paths['fonts_styles'] = content_url('uploads/la_icon_sets/style.min.css');
        $this->paths['config'] = 'charmap.php';
        self::$dir = plugin_dir_url(__FILE__);

        if (!is_dir($this->paths['icon_sets'])) {
            wp_mkdir_p($this->paths['icon_sets']);
        }
        if (!file_exists($this->paths['icon_sets'].'/style.min.css')) {
            touch($this->paths['icon_sets'].'/style.min.css');
        }

        add_action('admin_enqueue_scripts', array($this, 'enqueueAdminScripts'), 9);
        add_action('wp_enqueue_scripts', array($this, 'enqueuePublicScripts'), 9);

        add_action('wp_ajax_laim_upload_icons', array($this, 'ajax_handle_upload_icons'));
        add_action('wp_ajax_laim_delete_icons', array($this, 'ajax_handle_delete_icons'));

        add_action('admin_footer', array($this, 'loadCollection'));
    }

    /**
     * Enqueue public scripts & styles
     */
    public function enqueuePublicScripts()
    {
        wp_enqueue_style('la-icon-maneger-style', $this->paths['fonts_styles']);

        wp_register_script('la-icon-manager-md5', self::$dir.'js/md5.js', array(), $this->version, false);
        wp_register_script('la-icon-manager-util', self::$dir.'js/util.js', array(), $this->version, false);

        wp_enqueue_script('la-icon-manager-md5');
        wp_enqueue_script('la-icon-manager-util');
    }

    /**
     * Enqueue admin scripts & styles
     */
    public function enqueueAdminScripts()
    {
        wp_enqueue_style('la-icon-manager', self::$dir.'css/style.css');
        wp_enqueue_style('la-icon-maneger-style', $this->paths['fonts_styles']);

        wp_register_script('la-icon-manager-md5', self::$dir.'js/md5.js', array(), $this->version, true);
        wp_register_script(
            'la-icon-manager-templates',
            self::$dir.'js/templates.js',
            array('underscore', 'backbone', 'jquery'),
            $this->version,
            true
        );
        wp_register_script(
            'la-icon-manager-model',
            self::$dir.'js/model.js',
            array('underscore', 'backbone', 'jquery'),
            $this->version,
            true
        );
        wp_register_script(
            'la-icon-manager-view',
            self::$dir.'js/view.js',
            array('underscore', 'backbone', 'jquery'),
            $this->version,
            true
        );
        wp_register_script(
            'la-icon-manager-app',
            self::$dir.'js/app.js',
            array('la-icon-manager-model', 'la-icon-manager-view', 'la-icon-manager-templates', 'la-icon-manager-md5'),
            $this->version,
            true
        );

        wp_enqueue_script('la-icon-manager-md5');
        wp_enqueue_script('la-icon-manager-templates');
        wp_enqueue_script('la-icon-manager-model');
        wp_enqueue_script('la-icon-manager-view');
        wp_enqueue_script('la-icon-manager-app');
    }

    /**
     * Load fonts Backbone collection
     */
    public function loadCollection()
    {
        $fonts = get_option(self::$option);

        $html = '<script type="text/javascript">';
        $html .= 'jQuery(document).ready(function($) {';

        if (!$fonts) {
            $html .= 'var collection = {};';
        }else {
            $html .= 'var collection = new LAIconManagerCollection();';
            foreach ($fonts as $font => $info) {
                $icon_set = array();
                $file = $info['include'].'/'.$info['config'];

                if(!$file){
                    return;
                }

                $json = file_get_contents($file);
                $icons = json_decode($json, true);

                if ($icons) {
                    $icon_set = array_merge($icon_set, $icons);
                }
                $n = 0;
                foreach ($icon_set as $icons) {
                    $html .= 'var model_'.$n.' = new LAIconManagerModel();';
                    $html .= 'var icons = [';
                    foreach ($icons as $icon) {
                        $html .= '{class: "'.$icon['class'].'", tags: "'.$icon['tags'].'"},';
                    }
                    $html .= '];';
                    $html .= 'model_'.$n.'.set("icons", icons);';
                    $html .= 'model_'.$n.'.set("name", "'.$font.'");';
                    $html .= 'collection.add(model_'.$n.');';
                    $n++;
                }
            }
        }

        $html .= 'window["la_icon_manager_collection"] = collection;';
        $html .= 'setTimeout(function () {jQuery(document).trigger("iconManagerCollectionLoaded");}, 14);';
        $html .= '});';
        $html .= '</script>';

        echo $html;
    }

    /**
     * Upload & register default fonts
     *
     * @return bool
     */
    public function addDefaultFonts()
    {
        $fonts = get_option(self::$option);

        $files = scandir($this->paths['default_fonts']);
        foreach($files as $file){
            $name = $this->getName($file);
            if ($file !== '.' && $file != '..' && !isset($fonts[$name])) {
                $this->uploadFont($this->paths['default_fonts'].$file);
            }
        }

    }

    /**
     * Chack user capabilities
     */
    protected function checkCapabilities()
    {
        $cap = apply_filters('avf_file_upload_capability', 'update_plugins');
        if (!current_user_can($cap)) {
            $response = $this->getResponce(
                $this->response,
                'Using this feature is reserved for Super Admins. You unfortunately don\'t have the necessary permissions.',
                'error'
            );
            $response->send();
        }
    }

    /**
     * Upload font from archive
     *
     * @param $path
     * @return bool
     */
    protected function uploadFont($path)
    {
        $unzip = $this->unZip($path, self::$filters);
        $config = $this->createConfig();

        return $unzip && $config;
    }

    /**
     * AJAX handle for font upload
     */
    public function ajax_handle_upload_icons()
    {
        $this->checkCapabilities();

        $url = $_POST['data']['url'];
        $success = $this->uploadFont($url);

        if (strlen($this->error) > 0) {
            $errors = new WP_Error();
            $errors->add('upload_icons', $this->error);
            $response = $this->getResponce($this->response, $errors, 'errors');
            $response->send();
        }

        if ($success) {
            $response = $this->getResponce($this->response, true, 'upload_icons');
            $response->send();
        }

        die();
    }

    /**
     * AJAX handle for font delete
     */
    public function ajax_handle_delete_icons()
    {
        $this->checkCapabilities();

        $this->font_name = $_POST['data']['font'];
        $path = trailingslashit($this->paths['icon_sets']).$this->font_name;
        if (!is_dir($path)) {
            $errors = new WP_Error();
            $errors->add('upload_icons', 'Icon Font set already deleted or doesn\'t exist');
            $response = $this->getResponce($this->response, $errors, 'errors');
            $response->send();
        }

        $this->deleteFolder($path);
        $this->deleteFont();
        $this->minifyCSS();

        $response = $this->getResponce($this->response, true, 'delete_icons');
        $response->send();

        die();
    }

    /**
     * unZIP archive with font
     *
     * @param $path
     * @param $filter
     * @return bool
     */
    protected function unZip($path, $filter)
    {
        $zip = new ZipArchive();
        $tmp = 'tmp.zip';

        if (!copy($path, $tmp)) {
            $this->error = 'Failed to copy original ZIP file to temporary file';

            return false;
        }

        $res = $zip->open($tmp);
        $dest = $this->paths['icon_sets'];
        if ($res === true) {
            // create root directory
            if (!is_dir($dest)) {
                wp_mkdir_p($dest);
            }

            $this->setName($path);

            // check if icon set already exists
            if (is_dir($dest.'/'.$this->font_name)) {
                $this->error = 'It seems that the font with the same name is already exists! Please upload the font with different name.';

                return false;
            }

            // create icon set directory
            wp_mkdir_p(trailingslashit($dest).$this->font_name);

            // remove demo files and copy fonts & styles from ZIP to set dir
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $entry = $zip->getNameIndex($i);
                $delete = true;
                if (count($filter) > 0) {

                    $matches = array();
                    foreach ($filter as $regex) {
                        preg_match('/^._/', $entry, $mac);
                        if (!empty($mac)) {
                            break;
                        }

                        preg_match('!'.$regex.'!', $entry, $matches);
                        if (!empty($matches)) {
                            $delete = false;
                            break;
                        }

                    }
                }

                if ($delete || substr($entry, -1) == '/') {
                    continue;
                } // skip directories and non matching files

                $fp = $zip->getStream($entry);
                $ofp = fopen($dest.'/'.$this->font_name.'/'.basename($entry), 'w');
                if (!$fp) {
                    $this->error = 'Unable to extract the file.';

                    return false;
                }
                while (!feof($fp)) {
                    fwrite($ofp, fread($fp, 8192));
                }
                fclose($fp);
                fclose($ofp);
            }

            $zip->close();

            return true;
        } else {
            return false;
        }
    }

    /**
     * Create font config file
     *
     * @return bool
     */
    protected function createConfig()
    {
        $this->json_file = $this->findFile('json');
        $this->svg_file = $this->findFile('svg');
        if (!$this->json_file || !$this->svg_file) {
            $this->error = 'selection.json or SVG file not found. Was not able to create the necessary config files';

            return false;
        }

        $set_path = trailingslashit($this->paths['icon_sets']).$this->font_name;
        $response = wp_remote_fopen(trailingslashit($set_path).$this->svg_file);
        $json = file_get_contents(trailingslashit($set_path).$this->json_file);

        if (!$response) {
            $response = file_get_contents(trailingslashit($set_path).$this->svg_file);
        }
        if (!is_wp_error($json) && $json) {
            $xml = simplexml_load_string($response);
            $font_attr = $xml->defs->font->attributes();
            $glyphs = $xml->defs->font->children();

            $this->svg_font = $font_attr['id'];

            $unicodes = array();
            foreach ($glyphs as $item => $glyph) {
                if ($item == 'glyph') {
                    $attributes = $glyph->attributes();
                    $unicode = (string)$attributes['unicode'];
                    array_push($unicodes, $unicode);
                }
            }
            $file_contents = json_decode($json);
            if (!isset($file_contents->IcoMoonType)) {
                $this->error = 'Uploaded font is not from IcoMoon. Please upload fonts created with the IcoMoon App Only.';

                return false;
            }
            $icons = $file_contents->icons;
            unset($unicodes[0]);
            $n = 1;
            foreach ($icons as $icon) {
                $icon_name = $icon->properties->name;
                $icon_class = str_replace(' ', '', $icon_name);
                $icon_class = str_replace(',', ' ', $icon_class);
                $tags = implode(',', $icon->icon->tags);
                $this->json_config[$this->font_name][$icon_name] = array(
                    'class' => $icon_class,
                    'tags' => $tags,
                    'unicode' => $unicodes[$n],
                );
                $n++;
            }
            if ($this->json_config && $this->font_name != 'unknown') {
                $this->writeConfig();
                $this->rewriteCSS();
                $this->rewriteFonts();
                $this->addFont();
                $this->minifyCSS();

                return true;
            }
        }

        return false;
    }

    /**
     * Write font config file
     *
     * @return bool
     */
    protected function writeConfig()
    {
        $charmap = $this->paths['current'].'/'.$this->paths['config'];
        $handle = @fopen($charmap, 'w');
        $config = array();
        if ($handle) {
            $config[$this->font_name] = array();
            foreach ($this->json_config[$this->font_name] as $icon => $info) {
                if ($info) {
                    $config[$this->font_name][$icon] = array();
                    $config[$this->font_name][$icon]['class'] = $info['class'];
                    $config[$this->font_name][$icon]['tags'] = $info['tags'];
                    $config[$this->font_name][$icon]['unicode'] = $info['unicode'];
                } else {
                    $this->error = 'Was not able to write a config file';

                    return false;
                }
            }
            fwrite($handle, json_encode($config));
            fclose($handle);
        } else {
            $this->error = 'Was not able to write a config file';

            return false;
        }
    }

    /**
     * Rewrite font-family name
     *
     * @return bool
     */
    protected function rewriteFonts()
    {
        $files = array('.eot', '.ttf', '.woff', '.svg');
        foreach ($files as $ext) {
            $file = $this->paths['current'].'/'.$this->svg_font.$ext;
            $file = @file_get_contents($file);
            if ($file) {
                $str = str_replace($this->svg_font, $this->font_name, $file);
                @file_put_contents($file, $str);
            } else {
                $this->error = 'Was not able to rewrite fonts';

                return false;
            }
        }
    }

    /**
     * Rewrite CSS with new font-family name
     *
     * @return bool
     */
    protected function rewriteCSS()
    {
        $style = $this->paths['current'].'/style.css';
        $file = @file_get_contents($style);
        if ($file) {
            //$str = str_replace('fonts/', '', $file);
            $str = str_replace('icon-', 'la'.md5($this->font_name).'-', $file);
            $str = str_replace(
                '.icon {',
                '[class^="'.$this->font_name.'-"], [class*="la'.md5($this->font_name).'-"] {',
                $str
            );
            $str = str_replace(
                'i {',
                '[class^="'.$this->font_name.'-"], [class*="la'.md5($this->font_name).'-"] {',
                $str
            );
            $str = preg_replace('/font-family: \'[^\']*\'/', 'font-family: \''.$this->font_name.'\'', $str);

            /* remove comments */
            $str = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $str);

            /* remove tabs, spaces, newlines, etc. */
            //$str = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $str);

            @file_put_contents($style, $str);
        } else {
            $this->error = 'Was not able to rewrite CSS';

            return false;
        }
    }

    /**
     * Minify & concatenate all fonts CSS files into one
     */
    protected function minifyCSS()
    {
        $fonts = get_option(self::$option);
        if (is_array($fonts)) {
            $file = '';
            foreach ($fonts as $font => $info) {
                $tmp = @file_get_contents($info['folder'].'/style.css');
                $str = str_replace('fonts/', $font.'/', $tmp);
                $file .= $str;
            }
            @file_put_contents($this->paths['icon_sets'].'/style.min.css', $file);
        }
    }

    /**
     * Add font to WP option
     */
    protected function addFont()
    {
        $fonts = get_option(self::$option);
        if (!$fonts) {
            $fonts = array();
        }
        $fonts[$this->font_name] = array(
            'include' => $this->paths['current'],
            'folder' => $this->paths['current'],
            'style' => $this->font_name.'/style.css',
            'config' => $this->paths['config'],
        );
        update_option(self::$option, $fonts);
    }

    /**
     * Delete folder and contents if they already exist
     *
     * @param $path
     */
    protected function deleteFolder($path)
    {
        if (is_dir($path)) {
            $files = scandir($path);
            foreach ($files as $file) {
                if ($file != '.' && $file != '..') {
                    unlink($path.'/'.$file);
                }
            }
            reset($files);
            rmdir($path);
        }
    }

    /**
     * Delete font from WP option
     */
    protected function deleteFont()
    {
        $fonts = get_option(self::$option);
        if (!$fonts) {
            $fonts = array();
        }
        unset($fonts[$this->font_name]);
        update_option(self::$option, $fonts);
    }

    /**
     * Find file by extension
     *
     * @param $ext
     * @return mixed
     */
    protected function findFile($ext)
    {
        $files = scandir($this->paths['current']);
        foreach ($files as $file) {
            if ($file[0] !== '.' && strpos(strtolower($file), '.'.$ext) !== false) {
                return $file;
            }
        }
    }

    protected function getName($file)
    {
        $name = substr($file, 0, strpos($file, '.'));
        return str_replace('+', '', $name);
    }

    /**
     * Set font name
     *
     * @param $path
     */
    protected function setName($path)
    {
        $file = basename($path);
        $this->font_name = $this->getName($file);
        $this->paths['current'] = trailingslashit($this->paths['icon_sets']).$this->font_name;
    }

    /**
     * Get AJAX response
     *
     * @param WP_Ajax_Response $response
     * @param $data
     * @param $type
     * @return WP_Ajax_Response
     */
    public function getResponce(\WP_Ajax_Response $response, $data, $type)
    {
        if ($type == 'errors') {
            $response->add(
                array(
                    'what' => $type,
                    'id' => $data,
                )
            );
        } else {
            $response->add(
                array(
                    'what' => $type,
                    'action' => $type,
                    'id' => '1',
                    'data' => $data,
                    'supplemental' => '',
                )
            );
        }

        return $response;
    }

    /**
     * Get module instance
     *
     * @param $fonts
     * @return LA_IconManager
     */
    public static function getInstance($fonts = '')
    {
        if (self::$instance === null) {
            return self::$instance = new LA_IconManager($fonts);
        }
        return self::$instance;
    }

    /**
     * Get icon set name
     *
     * @param $string
     * @param string $delimeter
     * @return bool
     */
    public static function getSet($string, $delimeter = '_####_')
    {
        $info = explode($delimeter, $string);
        if(count($info) > 1){
            return $info[0];
        }
        return false;
    }

    /**
     * Get icon name
     *
     * @param $string
     * @param string $delimeter
     * @return bool
     */
    public static function getIcon($string, $delimeter = '_####_')
    {
        $info = explode($delimeter, $string);
        if(count($info) > 1){
            return $info[1];
        }
        return false;
    }

    /**
     * Get icon CSS class
     *
     * @param $string
     * @param string $delimeter
     * @param string $prefix
     * @return bool|string
     */
    public static function getIconClass($string, $delimeter = '_####_', $prefix = 'la')
    {
        $info = explode($delimeter, $string);
        if($info){
            $set = $info[0];
            return $prefix.md5($set).'-'.$info[1];
        }
        return false;
    }

    /**
     * Optional method for delete option (e.g. on plugin deactivation)
     */
    public static function deleteOption()
    {
        if(gettype(get_option(self::$option)) == 'array' && count(get_option(self::$option)) === 0){
            delete_option(self::$option);
        }
    }


    private function __clone()
    {
        // prevent clonning
    }

    private function __wakeup()
    {
        // prevent unserialize
    }
}
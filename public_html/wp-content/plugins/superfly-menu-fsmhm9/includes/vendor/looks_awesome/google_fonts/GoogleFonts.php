<?php

/**
 * Created by PhpStorm.
 * User: whitesunset
 * Date: 22.02.2016
 * Time: 4:18
 */
class LA_GoogleFonts
{
    const font_api_url = 'https://www.googleapis.com/webfonts/v1/webfonts';

    protected $font_data;
    protected $fonts;
    protected $api_fallback_file;

    public function __construct($file = '')
    {
        $this->cache_interval = apply_filters('styles_google_fonts_cache_interval', 60 * 60 * 24 * 15); // 15 days
        $this->api_fallback_file = $file !== '' ? $file : plugin_dir_path(__FILE__).'google-fonts-fallback.json';
        $this->get_font_data();
    }

    public function get_font_data()
    {
        // If we already processed fonts, return them.
        if (!empty($this->font_data)) {
            return $this->font_data;
        }

        // If fonts are cached in the transient, return them.
        //$this->font_data = get_transient( 'la_google_font_data' );
        if (null !== $this->font_data) {
            return $this->font_data;
        }

        /**
         * If no cache, try connecting to Google API
         * Requires API key be set:
         *
         * @example
         *  add_filter( 'styles_google_font_api', create_function('', "return 'XXXXXXXX';" ) );
         */
        $this->font_data = $this->remote_get_google_api();

        // If Google API failed, use the fallback file.
        if (!is_array($this->font_data)) {
            $this->font_data = $this->get_api_fallback();
        }

        // API returned some good data. Cache it to the transient
        // and update the fallback file.
        $this->set_api_fallback();

        return $this->font_data;
    }

    /**
     * Connect to the remote Google API. Fall back to get_api_fallback on failure.
     */
    public function remote_get_google_api()
    {
        // API key must be set with this filter
        $api_key = apply_filters('styles_google_font_api', false);

        // no API key is set
        if (false === $api_key) {
            return $this->get_api_fallback();
        }

        // Construct request
        $url = add_query_arg('sort', apply_filters('styles_google_font_sort', 'alpha'), self::font_api_url);
        $url = add_query_arg('key', $api_key, $url);
        $response = wp_remote_get($url);

        // If response is an error, use the fallback file
        if (is_a($response, 'WP_Error')) {
            return $this->get_api_fallback();
        }
        $json = json_decode($response['body']);
        $json = array_map(array($this, 'json_map'), $json->items);

        return $json;
    }

    public function json_map($item)
    {
        return $item->family;
    }

    public function get_api_fallback()
    {
        if (!file_exists($this->api_fallback_file)) {
            return $this->fonts;
        }
        $this->fonts = json_decode(file_get_contents($this->api_fallback_file));

        return $this->fonts;
    }

    public function set_api_fallback()
    {
        if (!empty($this->font_data) && is_writable($this->api_fallback_file)) {
            file_put_contents($this->api_fallback_file, json_encode($this->font_data));
        }
    }
}
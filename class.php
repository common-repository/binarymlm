<?php

if (!defined('ABSPATH'))
    die();
if (!class_exists('Letscms_BMP')) {

    class Letscms_BMP {

        const ActivationURLs = 'wishlistactivation.com';
        const ActivationMaxRetries = 5;

        // -----------------------------------------
        // Constructor
        function BMP() { 
            global $wpdb;
            require_once(ABSPATH . '/wp-admin/includes/plugin.php');

            $this->AllCategories = get_terms('category', array('fields' => 'ids', 'get' => 'all'));


            $this->PluginOptionName = 'BMP_Options';
            $this->TablePrefix = $wpdb->prefix . 'bmp_';
            $this->OptionsTable = $this->TablePrefix . 'options';

            // character encoding
            $this->BlogCharset = get_option('blog_charset');
            $pluginfile = WP_PLUGIN_DIR . '/' . MLM_PLUGIN_NAME . '/binarymlm.php';
            $this->PluginInfo = (object) get_plugin_data($pluginfile);
            $this->Version = $this->PluginInfo->Version;
            $this->WPVersion = $GLOBALS['wp_version'] + 0;

            $this->pluginPath = $pluginfile;
            $this->pluginDir = dirname($this->pluginPath);
            $this->PluginFile = basename(dirname($pluginfile)) . '/' . basename($pluginfile);
            $this->PluginSlug = sanitize_title_with_dashes($this->PluginInfo->Name);
            $this->pluginBasename = plugin_basename($this->pluginPath);

            $this->pluginURL = plugins_url('', '/') . basename($this->pluginDir);
        }

        
        function Plugin_Download_Url() {
            static $url;
            if (!$url) {
                $url = Get_Download_url($this->Plugin_Latest_Version(), $this->Plugin_Latest_Version_key());
            }
            return $url;
        }

        function Plugin_mlm_Mass_Pay_Download_Url() {
            static $url;
            if (!$url) {
                $url = Get_mlm_Mass_Pay_Download_url(plugin_Latest_Version('mlm-paypal-mass-pay'), plugin_Latest_Version_key('mlm-paypal-mass-pay'));
            }
            return $url;
        }


        function Plugin_Latest_Version() {
            static $latest_bmp_ver;
            $varname = 'myplugin_version';
            if (empty($latest_bmp_ver) OR isset($_GET['checkversion'])) {
                //   $latest_bmp_ver = get_transient($varname);
                if (empty($latest_bmp_ver) OR isset($_GET['checkversion'])) {
                    $latest_bmp_ver = $this->plugin_Latest_Version();
                    if (empty($latest_bmp_ver)) {
                        //we failed, set the latest version to this one so that we won't keep checking again for today
                        $latest_bmp_ver = $this->Version;
                    }
                    //even if we fail never try again for this day
                    set_transient($varname, $latest_bmp_ver, 60 * 60 * 24);
                }
            }
            return $latest_bmp_ver;
        }

        function Plugin_Latest_Version_key() {
            static $latest_bmp_ver;
            $varname = 'myplugin_version';
            if (empty($latest_bmp_ver) OR isset($_GET['checkversion'])) {
                //   $latest_bmp_ver = get_transient($varname);
                if (empty($latest_bmp_ver) OR isset($_GET['checkversion'])) {
                    $latest_bmp_ver = plugin_Latest_Version_key();
                    if (empty($latest_bmp_ver)) {
                        //we failed, set the latest version to this one so that we won't keep checking again for today
                        $latest_bmp_ver = $this->Version;
                    }
                    //even if we fail never try again for this day
                    set_transient($varname, $latest_bmp_ver, 60 * 60 * 24);
                }
            }
            return $latest_bmp_ver;
        }

        function Plugin_Is_Latest() {
            $latest_ver = $this->Plugin_Latest_Version();
            $ver = $this->Version;
            if (preg_match('/^(\d+\.\d+)\.{' . 'GLOBALREV}/', $this->Version, $match)) {
                $ver = $match[1];
                preg_match('/^(\d+\.\d+)\.[^\.]*/', $latest_ver, $match);
                $latest_ver = $match[1];
            }
            return version_compare($latest_ver, $ver, '<=');
        }


        /**
         * Checks whether a url is exempted from licensing
         * @param string $url the url to test
         * @return boolean
         */
        function isURLExempted($url) {
            $patterns = array(
                '/^[^\.]+$/',
                '/^.+\.loc$/',
                '/^.+\.dev$/',
                '/^.+\.staging\.wpengine\.com$/'
            );
            $res = trim(parse_url($url, PHP_URL_HOST));
            foreach ($patterns AS $pattern) {
                if (preg_match($pattern, $res)) {
                    return true;
                }
            }
            return false;
        }

        
        /**
         * Retrieves a menu object.  Also displays an HTML version of the menu if the $html parameter is set to true
         * @param string $key The index/key of the menu to retrieve
         * @param boolean $html If true, it echoes the url in as an HTML link
         * @return object|false Returns the menu object if successful or false on failure
         */
        function GetMenu($name, $slug, $html = false) {
            $objHTML = '';
            if ($slug) {
                $objURL = '?page=' . $slug;
                $objName = $name;
                $objHTML = '<a href="' . $objURL . '">' . $objName . '</a>';
                if ($html) {
                    echo $objHTML;
                }
                return $objHTML;
            }
            else {
                return false;
            }
        }

    }

}
?>
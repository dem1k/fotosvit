<?php

class MY_Config extends CI_Config {

    var $config = array();
    var $is_loaded = array();
    var $configs_that_change = array();

    public function __construct() {
        $this->config = & get_config();
        log_message('debug', "Config Class Initialized");
        $this->configs_that_change = array(
            'config' => true,
            'database' => true,
            'domains' => true,
            'global_var' => true,
        );
    }

    /**
     * Load Config File
     *
     * @param string $file
     * @param bool $use_sections
     * @param bool $fail_gracefully
     * @return	boolean	if the file was loaded correctly
     */
    function load($file = '', $use_sections = FALSE, $fail_gracefully = FALSE) {
        if (array_key_exists($file, $this->configs_that_change)) {
            if (in_array($file, $this->is_loaded, TRUE)) {
                return TRUE;
            }
            if (file_exists('return_config_folder.php')) {
                include 'return_config_folder.php';
            }
            if (isset($folder)) {
                if (!file_exists(APPPATH . 'config/' . $folder . '/' . $file . EXT)) {
                    if ($fail_gracefully === TRUE) {
                        return FALSE;
                    }
                    show_error('The configuration file ' . $file . EXT . ' does not exist.');
                }
                include(APPPATH . 'config/' . $folder . '/' . $file . EXT);
                if (isset($config)) {
                    return $this->processingConfig($config, $use_sections, $fail_gracefully, $file);
                }
            } else {
                show_error('Error upload config file. Check file exits.');
            }
        } else {
            $file = ($file == '') ? 'config' : str_replace(EXT, '', $file);

            if (in_array($file, $this->is_loaded, TRUE)) {
                return TRUE;
            }

            if (!file_exists(APPPATH . 'config/' . $file . EXT)) {
                if ($fail_gracefully === TRUE) {
                    return FALSE;
                }
                show_error('The configuration file ' . $file . EXT . ' does not exist.');
            }

            include(APPPATH . 'config/' . $file . EXT);
            if (isset($config)) {
                return $this->processingConfig($config, $use_sections, $fail_gracefully, $file);
            }
        }
    }

    function processingConfig($config, $use_sections, $fail_gracefully, $file) {
        if (!isset($config) OR !is_array($config)) {
            if ($fail_gracefully === TRUE) {
                return FALSE;
            }
            show_error('Your ' . $file . EXT . ' file does not appear to contain a valid configuration array.');
        }

        if ($use_sections === TRUE) {
            if (isset($this->config[$file])) {
                $this->config[$file] = array_merge($this->config[$file], $config);
            } else {
                $this->config[$file] = $config;
            }
        } else {
            $this->config = array_merge($this->config, $config);
        }

        $this->is_loaded[] = $file;
        unset($config);

        log_message('debug', 'Config file loaded: config/' . $file . EXT);
        return TRUE;
    }

}

?>
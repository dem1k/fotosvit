<?php

/**
 * Logging messages class.
 */
class Logger {

	/**
	 * Array of internal errors
	 *
	 * @var array
	 * @access private
	 */
	private $_internal_errors = array();
	/**
	 * Array of errors
	 *
	 * @var array
	 * @access private
	 */
	private $_general_errors = array();
	/**
	 * Array of messages
	 *
	 * @var array
	 * @access private
	 */
	private $_messages = array();
	/**
	 * Array of success messages
	 *
	 * @var array
	 * @access private
	 */
	private $_success = array();
	/**
	 * Codeigniter superobject
	 *
	 * @var array
	 * @access private
	 */
	private $ci;

	/**
	 * FirePHP object
	 * 
	 * @var object
	 * @access public
	 */
	public $fphp;
	
	/**
	 * Constructor
	 *
	 * @return object
	 */
	function __construct () {
		 
		$this->ci = & get_instance();
		$this->load_firePHP();
		$this->ci->lang->load('logger');
	}

	/**
	 * Just init firePHP tool
	 * 
	 * @return void
	 */
	private function load_firePHP () {
        require_once(BASEPATH.'application/libraries/firePHP/FirePHP.class.php');
        $this->fphp = FirePHP::getInstance(true);
	}
	
	/**
	 * Adds a general error
	 *
	 * @param string $arg[0] Slug of the error
	 * @param mixed (optional)$args Other argument that will be displayed in formating string.
	 */
	function add_error () {

		$args = func_get_args();
		$code = array_shift($args);
		$this->_general_errors[] = vsprintf($this->_description($code), $args);
	}

	/**
	 * Adds a critical error
	 *
	 * @param string $arg[0] Slug of the error
	 * @param mixed (optional)$args Other argument that will be displayed in formating string.
	 */
	function add_critical () {

		$args = func_get_args();
		$code = array_shift($args);
		show_error(vsprintf($this->_description($code), $args));
	}

	/**
	 * Adds an internal error
	 *
	 * @param string $arg[0] Slug of the error
	 * @param mixed (optional)$args Other argument that will be displayed in formating string.
	 */
	function add_internal () {

		$args = func_get_args();
		$code = array_shift($args);
		$this->_internal_errors[] = vsprintf($this->_description($code), $args);
	}

	/**
	 * Adds a message
	 *
	 * @param string $arg[0] Slug of the error
	 * @param mixed (optional)$args Other argument that will be displayed in formating string.
	 */
	function add_message () {

		$args = func_get_args();
		$code = array_shift($args);
		$this->_messages[] = vsprintf($this->_description($code), $args);
	}

	/**
	 * Adds a message on successfull finishing of some opera
	 *
	 * @param string $arg[0] Slug of the error
	 * @param mixed (optional)$args Other argument that will be displayed in formating string.
	 */
	function add_success () {

		$args = func_get_args();
		$code = array_shift($args);
		$this->_success[] = vsprintf($this->_description($code), $args);
	}

	/**
	 * Returns the description of message on proper language by its slug
	 *
	 * @param string $id
	 * @access private
	 * @return string
	 */
	private function _description ($id) {

		return lang($id);
	}

	/**
	 * Returns the array of critical errors occures or null
	 *
	 * @return string Descriptoin of the error OR null if nothing happened
	 */
	function get_internal () {

		$garb = unserialize($this->ci->session->userdata('ses_internal'));
		if ( $garb ) {
			$this->_general_errors = array_merge($this->_general_errors, $garb);
			$this->_clear_session('internal');
		}
		return ( !empty($this->_internal_errors) ) ? $this->_internal_errors : null;
	}

	/**
	 * Returns the array of general errors occures or null
	 *
	 * @return string Descriptoin of the error OR null if nothing happened
	 */
	function get_error () {

		$garb = unserialize($this->ci->session->userdata('ses_error'));
		if ( $garb ) {
			$this->_general_errors = array_merge($this->_general_errors, $garb);
			$this->_clear_session('error');
		}
		return ( !empty($this->_general_errors) ) ? $this->_general_errors : null;
	}

	/**
	 * Returns the array of messages to display or null
	 *
	 * @return string Message text OR null if nothing happened
	 */
	function get_messages () {

		$garb = unserialize($this->ci->session->userdata('ses_message'));
		if ( $garb ) {
			$this->_messages = array_merge($this->_messages, $garb);
			$this->_clear_session('message');
		}
		return ( !empty($this->_messages) ) ? $this->_messages : null;
	}

	/**
	 * Returns the array of successfull messages to display or null
	 *
	 * @return string Success message text OR null if nothing good happened...
	 */
	function get_success () {

		$garb = unserialize($this->ci->session->userdata('ses_success'));
		if ( $garb ) {
			$this->_success = array_merge($this->_success, $garb);
			$this->_clear_session('success');
		}
		return ( !empty($this->_success) ) ? $this->_success : null;
	}

	/**
	 * Returns the array of validation errors to display or null
	 *
	 * @return string
	 */
	function get_validation_errors () {
		$errors = validation_errors();
		return ( !empty($errors) ) ? $errors : null;
	}

	/**
	 * Save message in session.
	 * It allows get information from previous scenario.
	 *
	 * @param string $type Type of error. Allow - error, message, success, internal
	 * @param string $code Error\message code
	 * @param string $params (optional)Parameters that would be passed to the formatted string
	 * @param string $settings (optional)Additional settings
	 * @return void
	 */
	function add_in_session ($type, $code, $params = array(), $settings = array()) {

		$types = array('error', 'message', 'success', 'internal');
		if ( in_array($type, $types) ) {
			$ses_array = unserialize($this->ci->session->userdata('ses_' . $type));
			$ses_array = !empty($ses_array) ? $ses_array : array();
			$ses_array[] = vsprintf($this->_description($code), $params);
			$this->ci->session->set_userdata('ses_' . $type, serialize($ses_array));
		}
	}

	/**
	 * Clears session errors or messages
	 *
	 * @access private
	 * @param string $type Which messages to remove. Could be 'errors', 'messages' or 'success'
	 */
	private function _clear_session ( $type ) {

		$types = array('error', 'message', 'success', 'internal');
		if ( in_array($type, $types) ) {
			$this->ci->session->set_userdata('ses_' . $type, serialize(array()));
		}
	}

	/**
	 * EXAMPLE. DO NOT USES ANYWHERE. AND DO NOT USE IT.
	 * Default method.
	 *
	 * @param string $function_name Called function name
	 * @param string $params Array of params
	 * @return unknown_type
	 */
	function __call ($function_name, $params) {
		/*
		 * Realisation for "in_session" errors.
		 */
		preg_match("~^(.+)_in_session$~", $function_name, $matches);
		if (!empty($matches) && method_exists($this, $matches[1])) {
			call_user_func_array(array(&$this,$matches[1]), $params);
		}
	}

}

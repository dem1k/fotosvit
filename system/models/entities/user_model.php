<?php
include_once(CLASSES_PATH . 'db_model.php');
//include_once('../classes/db_model.php');
/**
 * Model, which operates with users in DB
 *
 * @author Maxim Peshkov
 *
 */
class User_Model extends DB_Model
{
                       
    protected $ci;

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
		$this->_table_name = db_table('users');
		$this->_primary_key = 'id';
		$this->_fields_list = array('id', 'username', 'login', 'password', 'open_password', 'status', 'email', 'type', 'created_at');

        $this->ci = & get_instance();
	}

	// General functions
    /**
     * 
     * @param $login username,email,openid
     * @return unknown_type
     */
    function get_by_login ($login) {

        return $this->get_slice(1, NULL, array('where' => "`username`='{$login}' OR `email`='{$login}'"));
    }	
	
	/**
	 * Implementation of abstract method DB_Model::validate_on_insert()
	 *
	 * @param array $data (optional) Assoc array of data except $_POST
	 * @see www/system/application/models/DB_Model#validate_on_insert($data)
	 */
	function validate_on_insert ($data) {

		$val = $this->form_validation;
		$val->set_rules('username', 'Login', 'trim|required|alpha_dash|min_length[4]');
		$val->set_rules('password', 'Password', 'trim|required');
		$val->set_rules('open_password', 'Open Password', '');
		$val->set_rules('type', 'User Type', 'trim|required');
		$val->set_rules('email', 'EMail', 'trim|required|valid_email');
		$val->set_rules('created_at', 'Time', 'required');
		$val->set_rules('status', 'Status', '');

		return $this->run_validation($data);
	}

	/**
	 * Implementation of abstract method DB_Model::validate_on_update()
	 *
	 * @param array $data (optional) Assoc array of data except $_POST
	 * @param array $fields (optional) Fields to update
	 * @see www/system/application/models/DB_Model#validate_on_update($data)
	 */
	function validate_on_update ($data) {

		$val = $this->form_validation;
		
		if (isset($data['username'])) {
			$val->set_rules('username', 'Login', 'trim|required|alpha_dash|min_length[4]');
		}
		if (isset($data['password'])) {
			$val->set_rules('password', 'Password', 'trim|required');
		}
		if (isset($data['open_password'])) {
			$val->set_rules('open_password', 'Open Password', '');
		}
		if (isset($data['email'])) {
			$val->set_rules('email', 'EMail', 'trim|required|valid_email');
		}
		if (isset($data['status'])) {
			$val->set_rules('status', 'Status', '');
		}

		return $this->run_validation($data);
	}	

}
?>
<?php

class Auth extends Controller {

	function Auth()
	{
		parent::Controller();
                $this->load->library('authorization');
	}
	
	
	function login() {
  
        if(!empty($_SESSION['message'])){
            $data['message'] = $_SESSION['message'];
            unset($_SESSION['message']);
        }        
        else{
            $data['message'] = false;
        }
        if ($this->authorization->is_logged_in()) {
			$this->logger->add_in_session('success', 'auth_already_logged_in');
			redirect('/admin/general/');
			die();
		}
		if ($this->input->post('submit_login')) {
			if ($this->_do_login()) {
				$this->logger->add_in_session('success', 'auth_logged_in');
				redirect('/admin/general');
				die();
			}
		}
		$this->load->view('/admin/login', $data);
	}
	
    
	function logout($message = false)
	{
	    //setcookie('session_id', '', mktime(0,0,0,19,9,2010), site_url);
		$this->authorization->logout();

		redirect('/admin/auth/login');
	}
	

	private function _do_login() {
		$this->load->library('Form_validation', 'form_validation');
		$val = $this->form_validation;
		$val->set_rules('login', 'Login', 'trim|required|alpha_dash|min_length[4]');
		$val->set_rules('password', 'Password', 'trim|required|min_length[4]');

		if ($val->run()) {
			try {
				return $this->authorization->login($val->set_value('login'), $val->set_value('password'), $this->input->post('remember_me'));
			} catch (Exception $e) {
				$this->logger->add_in_session('error', $e->getMessage());
			}
		}
		return FALSE;
	}	
	
}

?>
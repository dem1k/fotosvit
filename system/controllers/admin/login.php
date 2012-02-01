<?php

class Login extends Controller {
    
    function Login() {
        parent::Controller();
    }
    
    function index() {
        $this->load->library('authorization');
        $this->load->view('admin/login/view');
    }
}

?>

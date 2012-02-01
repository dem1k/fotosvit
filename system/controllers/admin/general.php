<?php

class General extends Controller {
    
    function General() {
        parent::Controller();
    }
    
    function index() {
        $data = array();
        $data['template'] = 'admin/general/view';
        $data['res'] = $this->router->fetch_class();

        $this->load->view('admin/main', $data);
    }
    
    function _remap($method) {
        $this->load->library('authorization');
        $params = null;

        if ($this->uri->segment(5)) {
            $params = $this->uri->segment(5);
        }
//        echo $user_type;
        if (!$this->authorization->is_logged_in()) {
            redirect("auth/login");
        } else {
            check_perms();
            $this->$method($params);
        }
    }
}

?>

<?php

class Messages extends Controller {

    function Messages() {
        parent::Controller();
        $this->load->model('admin/messages_model', '', true);
    }

    function index() {
        $data['res'] = $this->router->fetch_class();

        $messages= $this->messages_model->getMessages();
        $data['messages']=$messages;
        //var_dump($messages);exit;
        $data['template'] = 'admin/messages/view';
        $this->load->view('admin/main', $data);
    }
     function _remap($method) {
        $this->load->library('authorization');
        $params = null;
        if ($this->uri->segment(5)) {
            $params = $this->uri->segment(5);
        }

        if (!$this->authorization->is_logged_in()) {
            redirect("auth/login");
        } else {
            check_perms();
            $this->$method($params);
        }
    }
}

?>

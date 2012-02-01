<?php
Class Impressum extends Controller {

    function Impressum() {
        parent::Controller();
    }
    function index() {
        $data['template']='admin/impressum/view';
        $data['res'] = $this->router->fetch_class();
        $this->load->model('admin/impressum_model','',true);

        $this->load->library('form_validation');
        $this->form_validation->set_rules('text', 'Текст статьи', 'trim|required|min_length[2]|xss_clean');

        $impressum=$this->impressum_model->getImpressum();
        $data['impressum']=$impressum[0]->text;
        if ($this->input->post('action', '') == 'save') {
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('admin/main',$data);
            } else {
                $save['text']=$this->input->post('text');
                $this->load->view('admin/main',$data);
                $this->impressum_model->updateImpressum($save);
            }
        }else {
            $this->load->view('admin/main',$data);
        }


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
<?php
Class Startpage extends Controller {

    function Startpage() {
        parent::Controller();
    }
    function index() {
        $data['template']='admin/startpage/view';
        $data['res'] = $this->router->fetch_class();
        $this->load->model('admin/startpage_model','',true);

        $this->load->library('form_validation');
        $this->form_validation->set_rules('title', 'Название статьи', 'trim|required|min_length[2]|xss_clean');
        $this->form_validation->set_rules('text', 'Текст статьи', 'trim|required|min_length[2]|xss_clean');

        $startpage=$this->startpage_model->getStartpage();
        $data['startpage']=$startpage[0]->text;
        $data['title_page']=$startpage[0]->title;
        if ($this->input->post('action', '') == 'save') {
            if ($this->form_validation->run() == FALSE) {
                $data['startpage']=$this->input->post('text');
                $save['title']=set_value('text');
                $this->load->view('admin/main',$data);
            } else {
                $save['text']=$this->input->post('text');
                $save['title']=set_value('text');
                $this->load->view('admin/main',$data);
                $this->startpage_model->updateStartpage($save);
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
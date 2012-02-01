<?php
Class Seo extends Controller {

    function Seo() {
        parent::Controller();
        $this->load->model('admin/seo_model', '', true);
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
    }
    function index() {
        $data['template'] = 'admin/seo/view';
        $data['res'] = $this->router->fetch_class();
        $this->form_validation->set_rules('title', 'Tel', 'trim|xss_clean');
        $this->form_validation->set_rules('keywords', 'Mobil', 'trim|xss_clean');
        $this->form_validation->set_rules('description', 'Email', 'trim|xss_clean');
        $seo = $this->seo_model->getSeo();
        $data['seo']=$seo[0];
        if ($this->input->post('action', '') == 'save') {
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('admin/main',$data);
            } else {

                $info = array(
                        'title'=>set_value('title'),
                        'keywords'=>set_value('keywords'),
                        'description'=>set_value('description'),

                );
                $this->seo_model->updateSeo($info);
                redirect('/admin/seo');
                
                $this->load->view('admin/main',$data);
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
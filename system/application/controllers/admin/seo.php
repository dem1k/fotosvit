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
        $this->form_validation->set_rules('title', 'title', 'trim|xss_clean');
        $this->form_validation->set_rules('keywords', 'keywords', 'trim|xss_clean');
        $this->form_validation->set_rules('description', 'description', 'trim|xss_clean');
        $this->form_validation->set_rules('kurs', 'Курс $', 'trim|xss_clean');
        $this->form_validation->set_rules('site_url', 'Адрес сайта', 'trim|xss_clean');
        $this->form_validation->set_rules('site_name', 'Название', 'trim|xss_clean');
        $this->form_validation->set_rules('phone', 'Телефон', 'trim|xss_clean');
        $this->form_validation->set_rules('email', 'email', 'trim|xss_clean');
        $this->form_validation->set_rules('slogan', 'Слоган', 'trim|xss_clean');
        $seo = $this->seo_model->getSeo();
        $data['seo']=$seo;
        if ($this->input->post('action', '') == 'save') {
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('admin/main',$data);
            } else {

                $info = array(
                        'title'=>set_value('title'),
                        'keywords'=>set_value('keywords'),
                        'description'=>set_value('description'),
                    'kurs'=>set_value('kurs'),
                    'site_url'=>set_value('site_url'),
                    'site_name'=>set_value('site_name'),
                    'phone'=>set_value('phone'),
                    'email'=>set_value('email'),
                    'slogan'=>set_value('slogan'),


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
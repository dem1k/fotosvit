<?php

class Contacts extends Controller {

    function Contacts() {
        parent::Controller();
        $this->load->library('form_validation');
        $this->load->model('admin/personal_model', '', true);
    }

    function index() {

        $personal= $this->personal_model->getPersonal();
        $data['personal']=$personal[0];
        //var_dump($data);exit;
        $this->load->model('admin/seo_model','',true);
        $seo=$this->seo_model->getseo();
        $data['seo']=$seo[0];
        $data['template'] = 'client/contacts/view';
        $this->load->view('client/main', $data);
    }
    function message() {
        $this->load->model('admin/seo_model','',true);
        $seo=$this->seo_model->getseo();
        $data['seo']=$seo[0];
        $data['template']='client/contacts/view';
        $data['title']='Contacts';
        $this->form_validation->set_rules('betreff', 'betreff', 'trim|required|email|xss_clean');
        $this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|xss_clean');
        $this->form_validation->set_rules('komentar', 'komentar', 'trim|required|xss_clean');
        $this->form_validation->set_rules('name', 'name', 'trim|required|xss_clean');
        if ($this->input->post('action', '') == 'send') {
            if ($this->form_validation->run() == FALSE) {
                $personal= $this->personal_model->getPersonal();
                $data['personal']=$personal[0];
                $this->load->view('client/main',$data);
            } else {

                $save=array(
                        'email'=>set_value('email'),
                        'message'=>set_value('komentar'),
                        'name'=>set_value('name'),
                        'title'=>set_value('betreff'),
                        'date'=>date('Y-m-d')
                );

                $this->personal_model->saveMessage($save);
                $data['template']='client/contacts/messagesent';
                $data['title']='Message sent succesfuly';
                $this->load->view('client/main',$data);
            }


        }




    }


}


?>

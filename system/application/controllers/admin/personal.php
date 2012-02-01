<?php
Class Personal extends Controller {

    function Personal() {
        parent::Controller();
        $this->load->model('admin/personal_model', '', true);
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
    }
    function index() {
        $data['template'] = 'admin/personal/view';
        $data['res'] = $this->router->fetch_class();
        $this->form_validation->set_rules('tel', 'Tel', 'trim|required|xss_clean');
        $this->form_validation->set_rules('mobil', 'Mobil', 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|email|required|xss_clean');
        $this->form_validation->set_rules('site', 'Site', 'trim|required|xss_clean');
        $personal = $this->personal_model->getPersonal();
        //var_dump($personal);exit;
        $data['personal']=$personal[0];
        if ($this->input->post('action', '') == 'save') {
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('admin/main',$data);
            } else {
                $config['upload_path'] = './uploads/products';
                $config['allowed_types'] = 'gif|jpg|png|bmp';
                $config['max_size']	= '10000';
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('image')) {
                    $info = array(
                            'tel'=>set_value('tel'),
                            'mobil'=>set_value('mobil'),
                            'email'=>set_value('email'),
                            'site'=>set_value('site'),


                    );
                    $this->personal_model->updatePesonal($info);
                    redirect('/admin/personal');
                }
                else {
                    $type = $this->upload->file_ext;
                    srand((double) microtime( ) * 1000000);
                    $uniq_id = uniqid(rand( ));
                    rename('./uploads/products/' . $this->upload->file_name, './uploads/products/' . $uniq_id . $type);
                   $info = array(
                            'tel'=>set_value('tel'),
                            'mobil'=>set_value('mobil'),
                            'email'=>set_value('email'),
                            'image'=>$uniq_id . $type,
                    );
                    $this->personal_model->updatePesonal($info);
                    redirect('/admin/personal');
                }

                $this->load->view('admin/main',$data);
            }
        }else {
            $this->load->view('admin/main',$data);
        }

    }

}
?>
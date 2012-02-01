<?php

class Shiping extends Controller {

    function Shiping() {
        parent::Controller();
        $this->load->model('admin/shiping_model', '', true);
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
    }

    function index() {
        $data['template'] = 'admin/shiping/view';
        $data['res'] = $this->router->fetch_class();
        $data['shipings'] = $this->shiping_model->getAll();
        $this->load->view('admin/main', $data);
    }

    function create() {
        $data['template'] = 'admin/shiping/create';
        $data['res'] = $this->router->fetch_class();

        $this->form_validation->set_rules('name', 'Название', 'trim|required|min_length[1]|max_length[32]|xss_clean');
        if ($this->input->post('action', '') == 'save') {
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('admin/main', $data);
            }else {
                $shiping=array(
                        'name'=>set_value('name'),
                );
                $this->shiping_model->save($shiping);
                redirect('/admin/shiping/');
            }
        }else {
            $this->load->view('admin/main', $data);
        }
    }

    function edit() {
        if(!$id=$this->uri->segment(4)) {
            redirect('/admin/shiping');
        }else {
            $obj=$this->shiping_model->getById($id);
           
            $data['template'] = 'admin/shiping/edit';
            $data['res'] = $this->router->fetch_class();
            $data['name'] = $obj[0]->name;
            $data['id'] = $id;
            $this->form_validation->set_rules('name', 'Название', 'trim|required|min_length[1]|max_length[32]|xss_clean');


            if ($this->input->post('action', '') == 'save') {
                if ($this->form_validation->run() == FALSE) {
                    $this->load->view('admin/main', $data);
                }else {
                    $shiping=array(
                            'name'=>set_value('name'),
                    );
                    $this->shiping_model->updateById($shiping,$id);
                    redirect('/admin/shiping');
                }
            }else {
                $this->load->view('admin/main', $data);
            }
        }

    }

    function view() {
        $data['res'] = $this->router->fetch_class();
        if(!$id=$this->uri->segment(4))
            redirect('/admin/shiping');
        else {
            $data['template'] = 'admin/shiping/view';
//            $data['products']=$this->shiping_model->getProductsByCollectionId($id);
        }
        $this->load->view('admin/main', $data);
    }


    function delete() {
        $id= $this->uri->segment(4);
        if(!$id) {
            show_404();
        }
        else {
            $data['template'] = 'admin/shiping/';
            $data['res'] = $this->router->fetch_class();
            $this->shiping_model->deleteById($id);
            redirect('/admin/shiping/');

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

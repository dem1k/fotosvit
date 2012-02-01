<?php

class Payment extends Controller {

    function Payment() {
        parent::Controller();
        $this->load->model('admin/payment_model', '', true);
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
    }

    function index() {
        $data['template'] = 'admin/payment/view';
        $data['res'] = $this->router->fetch_class();
        $data['payments'] = $this->payment_model->getAll();
        $this->load->view('admin/main', $data);
    }

    function create() {
        $data['template'] = 'admin/payment/create';
        $data['res'] = $this->router->fetch_class();

        $this->form_validation->set_rules('name', 'Название', 'trim|required|min_length[1]|max_length[32]|xss_clean');
        if ($this->input->post('action', '') == 'save') {
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('admin/main', $data);
            }else {
                $payment=array(
                        'name'=>set_value('name'),
                );
                $this->payment_model->save($payment);
                redirect('/admin/payment/');
            }
        }else {
            $this->load->view('admin/main', $data);
        }
    }

    function edit() {
        if(!$id=$this->uri->segment(4)) {
            redirect('/admin/payment');
        }else {
            $obj=$this->payment_model->getById($id);
           
            $data['template'] = 'admin/payment/edit';
            $data['res'] = $this->router->fetch_class();
            $data['name'] = $obj[0]->name;
            $data['id'] = $id;
            $this->form_validation->set_rules('name', 'Название', 'trim|required|min_length[1]|max_length[32]|xss_clean');


            if ($this->input->post('action', '') == 'save') {
                if ($this->form_validation->run() == FALSE) {
                    $this->load->view('admin/main', $data);
                }else {
                    $payment=array(
                            'name'=>set_value('name'),
                    );
                    $this->payment_model->updateById($payment,$id);
                    redirect('/admin/payment');
                }
            }else {
                $this->load->view('admin/main', $data);
            }
        }

    }

    function view() {
        $data['res'] = $this->router->fetch_class();
        if(!$id=$this->uri->segment(4))
            redirect('/admin/payment');
        else {
            $data['template'] = 'admin/payment/view';
//            $data['products']=$this->payment_model->getProductsByCollectionId($id);
        }
        $this->load->view('admin/main', $data);
    }


    function delete() {
        $id= $this->uri->segment(4);
        if(!$id) {
            show_404();
        }
        else {
            $data['template'] = 'admin/payment/';
            $data['res'] = $this->router->fetch_class();
            $this->payment_model->deleteById($id);
            redirect('/admin/payment/');

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

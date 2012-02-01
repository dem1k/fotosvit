<?php

class Currency extends Controller {
    
    function Currency() {
        parent::Controller();
    }
    
    function index() {
        $data = array();
        $data['template'] = 'admin/currency/view';
        $data['res'] = $this->router->fetch_class();
        $this->load->model('admin/currency_model', 'currency', true);
        if ($message = $this->session->userdata('message')) {
            $data['message'] = $message;
            $this->session->unset_userdata('message');
        }
        $data['currency'] = $this->currency->get_all();
        $this->load->view('admin/main', $data);
    }
    
    function create() {
        $data['template'] = 'admin/currency/create';
        $data['res'] = $this->router->fetch_class();
        $this->load->model('admin/currency_model', 'currency', true);
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Название', 'trim|required|min_length[3]|xss_clean');
        $this->form_validation->set_rules('description', 'Описание', 'trim|xss_clean');
        if ($this->input->post('action', '') == 'save') {
            if ($this->form_validation->run() == FALSE) {
                $data['error'] = ' ';
                $this->load->view('admin/main', $data);//загружаем вьювер
            }
            else {
                $result = array(
                    'name'=>$this->input->post('name'),
                    'description'=>$this->input->post('description')
                );
                $id = $this->currency->save($result);
                $this->session->set_userdata('message', 'Добавлена новая валюта');
                //Добавление 0 цены уже существующим продуктам
                $this->load->model('admin/product_model', 'product', true);//загружаем модель продуктов
                $all_porduct = $this->product->get_all();
                foreach ($all_porduct as $key => $value) {
                    $result = array(
                        'product_id' =>$value->id,
                        'currency_id'=> $id,
                        'price'=>0,
                        'price'=>0,
                    );
                    $this->currency->SavePrice($result);
                }
                //Добавление 0 цены уже существующим продуктам
                redirect('/admin/currency');
            }
        }
        else {
            $this->load->view('/admin/main', $data);//загружаем вьювер
        }
    }
    
    function edit() {
        $data['template'] = 'admin/currency/edit';
        $data['res'] = $this->router->fetch_class();
        $this->load->model('admin/currency_model', 'currency', true);
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Название', 'trim|required|min_length[3]|xss_clean');
        $this->form_validation->set_rules('description', 'Описание', 'trim|xss_clean');
        $id = $this->uri->segment(4, '');
        if (!empty($id)) {
            $currency = $this->currency->getById($id);
            if (!empty($currency)) {
                $data['currency'] = $currency;
                if ($this->input->post('action', '') == 'save') {
                    if ($this->form_validation->run() == FALSE) {
                        $data['error'] = ' ';
                        $this->load->view('admin/main', $data);//загружаем вьювер
                    }
                    else {
                        $result = array(
                            'name'=>$this->input->post('name'),
                            'description'=>$this->input->post('description'),
                            'id'=>$id
                        );
                        $save = $this->currency->update($result);
                        $this->session->set_userdata('message', 'Добавлена новая валюта');
                        redirect('/admin/currency');
                    }
                }
                else {
                    $this->load->view('/admin/main', $data);//загружаем вьювер
                }
            }
            else {
                redirect('/admin/currency');
            }
        } else {
            redirect('/admin/currency');
        }
    }

    function delete() {
        $id = $this->uri->segment(4, '');
        if (!empty($id)) {
            $this->load->model('admin/currency_model', 'currency', true);
            $this->currency->delete($id);
            $this->session->set_userdata('message', 'Валюта успешно удалена!');
        }
        redirect('/admin/currency');
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

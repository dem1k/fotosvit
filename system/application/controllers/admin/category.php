<?php

class Category extends Controller {

    function Category() {
        parent::Controller();
        $this->load->model('admin/category_model', '', true);
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
    }

    function index() {
        $data['template'] = 'admin/category/view';
        $data['res'] = $this->router->fetch_class();
        $data['categories'] = $this->category_model->getAll();
        $this->load->view('admin/main', $data);
    }

    function create() {
        $data['template'] = 'admin/category/create';
        $data['res'] = $this->router->fetch_class();

        $this->form_validation->set_rules('name', 'Название', 'trim|required|min_length[1]|max_length[32]|xss_clean');
        if ($this->input->post('action', '') == 'save') {
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('admin/main', $data);
            }else {
                $category=array(
                        'name'=>set_value('name'),
                );
                $this->category_model->save($category);
                redirect('/admin/category/');
            }
        }else {
            $this->load->view('admin/main', $data);
        }
    }

    function edit() {
        if(!$id=$this->uri->segment(4)) {
            redirect('/admin/category');
        }else {
            $obj=$this->category_model->getById($id);
           
            $data['template'] = 'admin/category/edit';
            $data['res'] = $this->router->fetch_class();
            $data['name'] = $obj[0]->name;
            $data['id'] = $id;
            $this->form_validation->set_rules('name', 'Название', 'trim|required|min_length[1]|max_length[32]|xss_clean');


            if ($this->input->post('action', '') == 'save') {
                if ($this->form_validation->run() == FALSE) {
                    $this->load->view('admin/main', $data);
                }else {
                    $category=array(
                            'name'=>set_value('name'),
                    );
                    $this->category_model->updateById($category,$id);
                    redirect('/admin/category');
                }
            }else {
                $this->load->view('admin/main', $data);
            }
        }

    }

    function view() {
        $data['res'] = $this->router->fetch_class();
        if(!$id=$this->uri->segment(4))
            redirect('/admin/category');
        else {
            $data['template'] = 'admin/category/view';
//            $data['products']=$this->category_model->getProductsByCollectionId($id);
        }
        $this->load->view('admin/main', $data);
    }


    function delete() {
        $id= $this->uri->segment(4);
        if(!$id) {
            show_404();
        }
        else {
            $data['template'] = 'admin/category/';
            $data['res'] = $this->router->fetch_class();
            $this->category_model->deleteById($id);
            redirect('/admin/category/');

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

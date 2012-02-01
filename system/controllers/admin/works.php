<?php

class Works extends Controller {
    
    function Works() {
        parent::Controller();
    }
    
    function index() {
        $data = array();                            // Создается новый пустой массив
        $data['template'] = 'admin/works/view';//Подключается шаблон
        $data['res'] = $this->router->fetch_class();//Узнаем какой контроллер задействован и передаем его в шаблон
        $this->load->model('admin/works_model', 'works', true);//загружаем модель
        $works = $this->works->get_all();//получаем список статичных страниц в массиве
        $data['works'] = $works;//передаем во вьювер

        if ($message = $this->session->userdata('message')) {
            $data['message'] = $message;
            $this->session->unset_userdata('message');
        }
        $this->load->view('/admin/main', $data);
    }
    
    function create() {
        $data = array();                            // Создается новый пустой массив
        $data['template'] = 'admin/works/create';//Подключается шаблон
        $data['res'] = $this->router->fetch_class();//Узнаем какой контроллер задействован и передаем его в шаблон
        $this->load->model('admin/works_model', 'works', true);//загружаем модель
        $this->load->library('form_validation');
        $this->form_validation->set_rules('description', 'Описание принципа', 'trim|required|min_length[10]|xss_clean');
        $this->form_validation->set_rules('sort', 'Сортировка', 'trim|required|min_length[1]|xss_clean');
        if ($this->input->post('action', '') == 'save') {
            if ($this->form_validation->run() == FALSE) {
                $data['error'] = ' ';
                $this->load->view('admin/main', $data);//загружаем вьювер
            }
            else {

                $config['upload_path'] = './uploads/works';
                $config['allowed_types'] = 'flv';
                $config['max_size']	= '200000';

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload()) {
                    $data['error'] = $this->upload->display_errors();
                    $this->load->view('admin/main', $data);//загружаем вьювер
                }
                else {
                    $file_options = array('upload_data' => $this->upload->data());
                    $result = array(
                        'sort'=>$this->input->post('sort'),
                        'description'=>$this->input->post('description'),
                        'path'=>$file_options["upload_data"]['file_name'],
                    );
                    $save = $this->works->save($result);
                    $this->session->set_userdata('message', 'Добавлен новый принцип работы');
                    redirect('admin/works');
                }
            }
        }
        else {
            $this->load->view('admin/main', $data);//загружаем вьювер
        }
    }
    
    function edit() {
        $data = array();                            // Создается новый пустой массив
        $data['template'] = 'admin/works/edit';//Подключается шаблон
        $data['res'] = $this->router->fetch_class();//Узнаем какой контроллер задействован и передаем его в шаблон
        $this->load->model('admin/works_model', 'works', true);//загружаем модель
        $this->load->library('form_validation');
        $this->form_validation->set_rules('description', 'Описание принципа', 'trim|required|min_length[10]|xss_clean');
        $this->form_validation->set_rules('sort', 'Сортировка', 'trim|required|min_length[1]|xss_clean');
        $id = $this->uri->segment(4, '');
        $data['id'] = $id;
        if (!empty($id)) {
            $works = $this->works->get_By_id($id);
            if (!empty($works[0])) {
                $data['works'] = $works[0];
                if ($this->input->post('action', '') == 'save') {
                    if ($this->form_validation->run() == FALSE) {
                        $data['error'] = ' ';
                        $this->load->view('admin/main', $data);//загружаем вьювер
                    }
                    else {
                        $result = array(
                            'sort'=>$this->input->post('sort'),
                            'description'=>$this->input->post('description'),
                            'path'=>$works[0]->path,
                            'id'=>$id
                        );
                        $save = $this->works->update($result);
                        $this->session->set_userdata('message', 'Описание принципа успешно отредактировано');
                        redirect('admin/works');
                    }
                }
                else {
                    $this->load->view('admin/main', $data);//загружаем вьювер
                }
            }
            else {
                redirect('admin/works');
            }
        }
        else {
            redirect('admin/works');
        }
    }
    
    function delete() {
        $id = $this->uri->segment(4, '');
        if (!empty($id)) {
            $data = array();                                        // Создается новый пустой массив
            $this->load->model('admin/works_model', 'works', true);   //загружаем модель
            $works = $this->works->get_By_id($id);
            foreach ($works as $item) {
                if (file_exists('./uploads/works/' . $item->path)) {
                    unlink('./uploads/works/' . $item->path);
                }
            }
            $this->works->delete($id);                       //получаем список статичных страниц в массиве
            $this->session->set_userdata('message', 'Принцип удален');
            redirect('admin/works');
        }
        else {
            $this->session->set_userdata('message', 'Принцип не удален');
            redirect('/admin/works');
        }
    }
    
    function view() {
        $id = $this->uri->segment(4, '');
        if (!empty($id)) {
            $data = array();                            // Создается новый пустой массив
            $data['template'] = 'admin/works/display';//Подключается шаблон
            $data['res'] = $this->router->fetch_class();//Узнаем какой контроллер задействован и передаем его в шаблон
            $this->load->model('admin/works_model', 'works', true);//загружаем модель
            $works = $this->works->get_By_id($id);//получаем список статичных страниц в массиве
            if (!empty($works[0])) {
                $data['works'] = $works[0];//передаем во вьювер
                if ($message = $this->session->userdata('message')) {
                    $data['message'] = $message;
                    $this->session->unset_userdata('message');
                }
                $this->load->view('/admin/main', $data);
            }
            else {
                redirect('admin/works');
            }
        }
        else {
            redirect('/admin/works');
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

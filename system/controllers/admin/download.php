<?php

class Download extends Controller {
    
    function Download() {
        parent::Controller();
    }
    
    function index() {
        $data = array();                            // Создается новый пустой массив
        $data['template'] = 'admin/download/view';//Подключается шаблон
        $data['res'] = $this->router->fetch_class();//Узнаем какой контроллер задействован и передаем его в шаблон
        $this->load->model('admin/download_model', 'download', true);//загружаем модель
        $download = $this->download->get_all();//получаем список статичных страниц в массиве
        $data['download'] = $download;//передаем во вьювер

        if ($message = $this->session->userdata('message')) {
            $data['message'] = $message;
            $this->session->unset_userdata('message');
        }
        $this->load->view('/admin/main', $data);
    }
    
    function create() {
        $data = array();                            // Создается новый пустой массив
        $data['error_upload'] = '';
        $data['template'] = 'admin/download/create';//Подключается шаблон
        $data['res'] = $this->router->fetch_class();//Узнаем какой контроллер задействован и передаем его в шаблон
        $this->load->model('admin/download_model', 'download', true);//загружаем модель
        $this->load->library('form_validation');
        $this->form_validation->set_rules('description', 'Описание', 'trim|required|min_length[2]|xss_clean');
        $this->form_validation->set_rules('path', 'Путь', 'trim|required|min_length[2]|xss_clean');
        if ($this->input->post('action', '') == 'save') {
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('admin/main', $data);//загружаем вьювер
            }
            else {
                $files = $this->input->post('files');
                $path = $this->input->post('path');
                $name = end(explode('/', $path));
                $result = array(
                    'name'=>$name,
                    'description'=>$this->input->post('description'),
                    'path'=>$path,
                );
                $save = $this->download->save($result);
                $this->session->set_userdata('message', 'Файл успешно добавлен');
                redirect('/admin/download');
            }

        }
        else {
            $this->load->view('admin/main', $data);//загружаем вьювер
        }
    }
    
    function edit() {
        $id = $this->uri->segment(4, '0');
        if ($id != '0') {
            $data = array();                            // Создается новый пустой массив
            $data['error_upload'] = '';
            $data['template'] = 'admin/download/edit';//Подключается шаблон
            $data['res'] = $this->router->fetch_class();//Узнаем какой контроллер задействован и передаем его в шаблон
            $this->load->model('admin/download_model', 'download', true);//загружаем модель
            $download = $this->download->get_By_id($id);
            if (!empty($download)) {
                $data['download'] = $download[0];
                $this->load->library('form_validation');
                $this->form_validation->set_rules('description', 'Описание', 'trim|required|min_length[2]|xss_clean');
                $this->form_validation->set_rules('path', 'Путь', 'trim|required|min_length[2]|xss_clean');
                if ($this->input->post('action', '') == 'save') {
                    if ($this->form_validation->run() == FALSE) {
                        $this->load->view('admin/main', $data);//загружаем вьювер
                    }
                    else {
                        $files = $this->input->post('files');
                        $path = $this->input->post('path');
                        $name = end(explode('/', $path));
                        $result = array(
                            'name'=>$name,
                            'description'=>$this->input->post('description', ''),
                            'path'=>$path,
                            'id'=>$id
                        );
                        $save = $this->download->update($result);
                        $this->session->set_userdata('message', 'Файл успешно отредактирован');
                        redirect('/admin/download');
                    }
                }
                else {
                    $this->load->view('admin/main', $data);//загружаем вьювер
                }
            }
            else {
                redirect('admin/download');//загружаем вьювер
            }
        }
        else {
            redirect('admin/download');
        }
    }
    
    function view() {
        $id = $this->uri->segment(4, '0');
        $data = array();
        $data['template'] = 'admin/download/display';//Подключается шаблон
        $data['res'] = $this->router->fetch_class();//Узнаем какой контроллер задействован и передаем его в шаблон
        $this->load->model('admin/download_model', 'download', true);//загружаем модель
        if ($id != '0') {
            $download = $this->download->get_By_id($id);
            if (!empty($download)) {
                $data['download'] = $download[0];
                $this->load->view('admin/main', $data);
            }
            else {
                redirect('admin/download');
            }
        }
        else {
            redirect('admin/download');
        }
    }
    
    function delete() {
        $id = $this->uri->segment(4, '0');
        $this->load->model('admin/download_model', 'download', true); //загружаем модель
        if ($id != '0') {
            $download = $this->download->get_By_id($id);
            if (!empty($download)) {
                if (file_exists('./uploads/files/' . $download[0]->name)) {
                    unlink('./uploads/files/' . $download[0]->name);
                }
                $this->download->delete($id);
                $this->session->set_userdata('message', 'Файл успешно удален');
                redirect('admin/download');
            }
            else {
                redirect('admin/download');
            }
        }
        else {
            redirect('admin/download');
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

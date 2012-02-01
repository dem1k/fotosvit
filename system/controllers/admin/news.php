<?php

class News extends Controller {
    
    function News() {
        parent::Controller();
    }
    
    function index() {
        $data = array();                            // Создается новый пустой массив
        $data['template'] = 'admin/news/view';//Подключается шаблон
        $data['res'] = $this->router->fetch_class();//Узнаем какой контроллер задействован и передаем его в шаблон
        $this->load->model('admin/news_model', 'news', true);//загружаем модель
        $news = $this->news->get_all();//получаем список статичных страниц в массиве
        $data['news'] = $news;//передаем во вьювер
        if ($message = $this->session->userdata('message')) {
            $data['message'] = $message;
            $this->session->unset_userdata('message');
        }
        $this->load->view('/admin/main', $data);
    }
    
    function view() {
        $id = $this->uri->segment(4, '');
        if (!empty($id)) {
            $data = array();                            // Создается новый пустой массив
            $data['template'] = 'admin/news/display';//Подключается шаблон
            $data['res'] = $this->router->fetch_class();//Узнаем какой контроллер задействован и передаем его в шаблон
            $this->load->model('admin/news_model', 'news', true);//загружаем модель
            $news = $this->news->get_one($id);//получаем список статичных страниц в массиве
            if (!empty($news[0])) {
                $data['news'] = $news;//передаем во вьювер

                if ($message = $this->session->userdata('message')) {
                    $data['message'] = $message;
                    $this->session->unset_userdata('message');
                }
                $this->load->view('/admin/main', $data);
            }
            else {
                redirect('admin/news');
            }
        }
        else {
            redirect('/admin/news');
        }
    }
    
    function delete() {
        $id = $this->uri->segment(4, '');
        if (!empty($id)) {
            $data = array();                                        // Создается новый пустой массив
            $this->load->model('admin/news_model', 'news', true);   //загружаем модель
            $news = $this->news->delete($id);                       //получаем список статичных страниц в массиве
            $this->session->set_userdata('message', 'Новость успешно удалена');
            redirect('admin/news');
        }
        else {
            $this->session->set_userdata('message', 'Новость НЕ удалена');
            redirect('/admin/news');
        }
    }
    
    function create() {
        $data = array();                            // Создается новый пустой массив
        $data['template'] = 'admin/news/create';//Подключается шаблон
        $data['res'] = $this->router->fetch_class();//Узнаем какой контроллер задействован и передаем его в шаблон
        $this->load->model('admin/news_model', 'news', true);//загружаем модель
        $this->load->library('form_validation');
        $this->form_validation->set_rules('title', 'Заголовок', 'trim|required|min_length[2]|max_length[400]|xss_clean');
        $this->form_validation->set_rules('des', 'Описание', 'trim|required|min_length[2]|xss_clean');
        $this->form_validation->set_rules('text', 'Текст', 'trim|required|min_length[2]|xss_clean');
        if ($this->input->post('action', '') == 'save') {
            if ($this->form_validation->run() == FALSE) {
                $data['error'] = ' ';
                $this->load->view('admin/main', $data);//загружаем вьювер
            }
            else {
                $result = array(
                    'title'=>$this->input->post('title'),
                    'des'=>$this->input->post('des'),
                    'text'=>$this->input->post('text'),
                    'date'=>date("Y-m-d")
                );
                $save = $this->news->save($result);
                $this->session->set_userdata('message', 'Новость успешно добавлена');
                redirect('admin/news');
            }
        }
        else {
            $this->load->view('admin/main', $data);//загружаем вьювер
        }
    }
    
    function edit() {
        $data = array();                            // Создается новый пустой массив
        $data['template'] = 'admin/news/edit';//Подключается шаблон
        $data['res'] = $this->router->fetch_class();//Узнаем какой контроллер задействован и передаем его в шаблон
        $this->load->model('admin/news_model', 'news', true);//загружаем модель
        $this->load->library('form_validation');
        $this->form_validation->set_rules('title', 'Заголовок', 'trim|required|min_length[2]|max_length[400]|xss_clean');
        $this->form_validation->set_rules('des', 'Описание', 'trim|required|min_length[2]|xss_clean');
        $this->form_validation->set_rules('text', 'Текст', 'trim|required|min_length[2]|xss_clean');
        $id = $this->uri->segment(4, '');
        $data['id'] = $id;
        if (!empty($id)) {
            $reviews = $this->news->get_one($id);
            $data['news'] =$reviews;
            if (!empty($reviews[0])) {
                $data['reviews'] = $reviews[0];
                if ($this->input->post('action', '') == 'save') {
                    if ($this->form_validation->run() == FALSE) {
                        $data['error'] = ' ';
                        $this->load->view('admin/main', $data);//загружаем вьювер
                    }
                    else {
                        $result = array(
                            'title'=>$this->input->post('title'),
                            'des'=>$this->input->post('des'),
                            'text'=>$this->input->post('text'),
                            'id'=>$id
                        );
                        $save = $this->news->update($result);
                        $this->session->set_userdata('message', 'Новость успешно отредактирована');
                        redirect('admin/news');
                    }
                }
                else {

                    $this->load->view('admin/main', $data);//загружаем вьювер
                }
            }
            else {
                redirect('admin/news');
            }
        }
        else {
            redirect('admin/news');
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

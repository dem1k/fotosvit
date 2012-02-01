<?php

/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/
class Reviews extends Controller {
    
    function Reviews() {
        parent::Controller();
    }
    
    function index() {
        $data = array();                            // Создается новый пустой массив
        $data['template'] = 'admin/reviews/view';//Подключается шаблон
        $data['res'] = $this->router->fetch_class();//Узнаем какой контроллер задействован и передаем его в шаблон
        $this->load->model('admin/reviews_model', 'reviews', true);//загружаем модель
        $reviews = $this->reviews->get_all();//получаем список статичных страниц в массиве
        $data['reviews'] = $reviews;//передаем во вьювер

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
            $data['template'] = 'admin/reviews/display';//Подключается шаблон
            $data['res'] = $this->router->fetch_class();//Узнаем какой контроллер задействован и передаем его в шаблон
            $this->load->model('admin/reviews_model', 'reviews', true);//загружаем модель
            $reviews = $this->reviews->get_By_id($id);//получаем список статичных страниц в массиве
            if (!empty($reviews[0])) {
                $data['reviews'] = $reviews;//передаем во вьювер

                if ($message = $this->session->userdata('message')) {
                    $data['message'] = $message;
                    $this->session->unset_userdata('message');
                }
                $this->load->view('/admin/main', $data);
            }
            else {
                redirect('admin/reviews');
            }
        }
        else {
            redirect('/admin/reviews');
        }
    }
    
    function delete() {
        $id = $this->uri->segment(4, '');
        if (!empty($id)) {
            $data = array();                                        // Создается новый пустой массив
            $this->load->model('admin/reviews_model', 'reviews', true);   //загружаем модель
            $news = $this->reviews->delete($id);                       //получаем список статичных страниц в массиве
            $this->session->set_userdata('message', 'Отзыв успешно удален');
            redirect('admin/reviews');
        }
        else {
            $this->session->set_userdata('message', 'Отзыв НЕ удален');
            redirect('/admin/reviews');
        }
    }
    
    function create() {
        $data = array();                            // Создается новый пустой массив
        $data['template'] = 'admin/reviews/create';//Подключается шаблон
        $data['res'] = $this->router->fetch_class();//Узнаем какой контроллер задействован и передаем его в шаблон
        $this->load->model('admin/reviews_model', 'reviews', true);//загружаем модель
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Имя', 'trim|required|min_length[2]|max_length[400]|xss_clean');
        $this->form_validation->set_rules('mail', 'Mail', 'trim|required|valid_email|xss_clean');
        $this->form_validation->set_rules('description', 'Отзыв', 'trim|required|min_length[2]|xss_clean');
        $this->form_validation->set_rules('status', 'Отзыв', 'trim|required|xss_clean');
        if ($this->input->post('action', '') == 'save') {
            if ($this->form_validation->run() == FALSE) {
                $data['error'] = ' ';
                $this->load->view('admin/main', $data);//загружаем вьювер
            }
            else {
                $result = array(
                    'name'=>$this->input->post('name'),
                    'description'=>$this->input->post('description'),
                    'mail'=>$this->input->post('mail'),
                    'status'=>$this->input->post('status'),
                    'date_create'=>date("Y-m-d"),
                );
                $save = $this->reviews->save($result);
                $this->session->set_userdata('message', 'Отзыв успешно добавлен');
                redirect('admin/reviews');
            }
        }
        else {
            $this->load->view('admin/main', $data);//загружаем вьювер
        }
    }
    
    function edit() {
        $data = array();                            // Создается новый пустой массив
        $data['template'] = 'admin/reviews/edit';//Подключается шаблон
        $data['res'] = $this->router->fetch_class();//Узнаем какой контроллер задействован и передаем его в шаблон
        $this->load->model('admin/reviews_model', 'reviews', true);//загружаем модель
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Имя', 'trim|required|min_length[2]|max_length[400]|xss_clean');
        $this->form_validation->set_rules('mail', 'Mail', 'trim|required|valid_email|xss_clean');
        $this->form_validation->set_rules('description', 'Отзыв', 'trim|required|min_length[2]|xss_clean');
        $this->form_validation->set_rules('status', 'Отзыв', 'trim|required|xss_clean');
        $id = $this->uri->segment(4, '');
        $data['id'] = $id;
        if (!empty($id)) {
            $reviews = $this->reviews->get_By_id($id);
            if (!empty($reviews[0])) {
                $data['reviews'] = $reviews[0];
                if ($this->input->post('action', '') == 'save') {
                    if ($this->form_validation->run() == FALSE) {
                        $data['error'] = ' ';
                        $this->load->view('admin/main', $data);
                    }
                    else {
                        $result = array(
                            'name'=>$this->input->post('name'),
                            'description'=>$this->input->post('description'),
                            'mail'=>$this->input->post('mail'),
                            'status'=>$this->input->post('status'),
                            'id'=>$id
                        );
                        $save = $this->reviews->update($result);
                        $this->session->set_userdata('message', 'Отзыв успешно отредактирован');
                        redirect('admin/reviews');
                    }
                }
                else {
                    $this->load->view('admin/main', $data);//загружаем вьювер
                }
            }
            else {
                redirect('admin/reviews');
            }
        }
        else {
            redirect('admin/reviews');
        }
    }
    
    function _remap($method) {
        $this->load->library('authorization');
        $params = null;
        $user_type = $this->session->userdata('user_type');
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

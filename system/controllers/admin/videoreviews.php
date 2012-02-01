<?php

class Videoreviews extends Controller {
    
    function Videoreviews() {
        parent::Controller();
    }
    
    function index() {
        $data = array();                            // Создается новый пустой массив
        $data['template'] = 'admin/videoreviews/view';//Подключается шаблон
        $data['res'] = $this->router->fetch_class();//Узнаем какой контроллер задействован и передаем его в шаблон
        $this->load->model('admin/videoreviews_model', 'videoreviews', true);//загружаем модель
        $reviews = $this->videoreviews->get_all();//получаем список статичных страниц в массиве
        $data['reviews'] = $reviews;//передаем во вьювер

        if ($message = $this->session->userdata('message')) {
            $data['message'] = $message;
            $this->session->unset_userdata('message');
        }
        $this->load->view('/admin/main', $data);
    }
    
    function create() {
        $data = array();                            // Создается новый пустой массив
        $data['template'] = 'admin/videoreviews/create';//Подключается шаблон
        $data['res'] = $this->router->fetch_class();//Узнаем какой контроллер задействован и передаем его в шаблон
        $this->load->model('admin/videoreviews_model', 'videoreviews', true);//загружаем модель
        $this->load->library('form_validation');
        $this->form_validation->set_rules('description', 'Описание ролика', 'trim|required|min_length[10]|xss_clean');
        if ($this->input->post('action', '') == 'save') {
            if ($this->form_validation->run() == FALSE) {
                $data['error'] = ' ';
                $this->load->view('admin/main', $data);//загружаем вьювер
            }
            else {

                $config['upload_path'] = './uploads/video';
                $config['allowed_types'] = 'flv';
                $config['max_size']	= '200000';

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload()) {
                    $data['error'] = $this->upload->display_errors();
//                    echo '!!!!!!!!!!!!!!!!!!';
                    $this->load->view('admin/main', $data);//загружаем вьювер
                }
                else {
                    $file_options = array('upload_data' => $this->upload->data());
                    $result = array(
                        'description'=>$this->input->post('description'),
                        'path'=>$file_options["upload_data"]['file_name'],
                    );
                    $save = $this->videoreviews->save($result);
                    $this->session->set_userdata('message', 'Отзыв успешно добавлен');
                    redirect('admin/videoreviews');
                }
            }
        }
        else {
            $this->load->view('admin/main', $data);//загружаем вьювер
        }
    }
    
    function edit() {
        $data = array();                            // Создается новый пустой массив
        $data['template'] = 'admin/videoreviews/edit';//Подключается шаблон
        $data['res'] = $this->router->fetch_class();//Узнаем какой контроллер задействован и передаем его в шаблон
        $this->load->model('admin/videoreviews_model', 'videoreviews', true);//загружаем модель
        $this->load->library('form_validation');
        $this->form_validation->set_rules('description', 'Описание ролика', 'trim|required|min_length[10]|xss_clean');
        $id = $this->uri->segment(4, '');
        $data['id'] = $id;
        if (!empty($id)) {
            $reviews = $this->videoreviews->get_By_id($id);
            if (!empty($reviews[0])) {
                $data['reviews'] = $reviews[0];
                if ($this->input->post('action', '') == 'save') {
                    if ($this->form_validation->run() == FALSE) {
                        $data['error'] = ' ';
                        $this->load->view('admin/main', $data);//загружаем вьювер
                    }
                    else {
                        $result = array(
                            'description'=>$this->input->post('description'),
                            'path'=>$reviews[0]->path,
                            'id'=>$id
                        );
                        $save = $this->videoreviews->update($result);
                        $this->session->set_userdata('message', 'Отзыв успешно отредактирован');
                        redirect('admin/videoreviews');
                    }
                }
                else {
                    $this->load->view('admin/main', $data);//загружаем вьювер
                }
            }
            else {
                redirect('admin/videoreviews');
            }
        }
        else {
            redirect('admin/videoreviews');
        }
    }
    
    function delete() {
        $id = $this->uri->segment(4, '');
        if (!empty($id)) {
            $data = array();                                        // Создается новый пустой массив
            $this->load->model('admin/videoreviews_model', 'videoreviews', true);   //загружаем модель
            $reviews = $this->videoreviews->get_By_id($id);
            foreach ($reviews as $item) {
                if (file_exists('./uploads/video/' . $item->path)) {
                    unlink('./uploads/video/' . $item->path);
                }
            }
            $news = $this->videoreviews->delete($id);                       //получаем список статичных страниц в массиве
            $this->session->set_userdata('message', 'Отзыв успешно удален');
            redirect('admin/videoreviews');
        }
        else {
            $this->session->set_userdata('message', 'Отзыв НЕ удален');
            redirect('/admin/videoreviews');
        }
    }
    
    function view() {
        $id = $this->uri->segment(4, '');
        if (!empty($id)) {
            $data = array();                            // Создается новый пустой массив
            $data['template'] = 'admin/videoreviews/display';//Подключается шаблон
            $data['res'] = $this->router->fetch_class();//Узнаем какой контроллер задействован и передаем его в шаблон
            $this->load->model('admin/videoreviews_model', 'videoreviews', true);//загружаем модель
            $reviews = $this->videoreviews->get_By_id($id);//получаем список статичных страниц в массиве
            if (!empty($reviews[0])) {
                $data['reviews'] = $reviews[0];//передаем во вьювер
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

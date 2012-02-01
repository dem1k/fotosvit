<?php

class Staticpage extends Controller {
    
    function Staticpage() {
        parent::Controller();
    }
    
    function index() {
        $data = array();                            // Создается новый пустой массив
        $data['template'] = 'admin/staticpage/view';//Подключается шаблон
        $data['res'] = $this->router->fetch_class();//Узнаем какой контроллер задействован и передаем его в шаблон
        $this->load->model('admin/stat_model', 'page', true);//загружаем модель
        $stat = $this->page->get_all();//получаем список статичных страниц в массиве
        $data['stat'] = $stat;//передаем во вьювер

        if ($message = $this->session->userdata('message')) {
            $data['message'] = $message;
            $this->session->unset_userdata('message');
        }

        $this->load->view('admin/main', $data);
//загружаем вьювер
    }
    
    function view() {
        $alias = $this->uri->segment(4, '');
        if (!empty($alias)) {
            $data = array();
            $data['template'] = 'admin/staticpage/display';
            $data['res'] = $this->router->fetch_class();
            $this->load->model('admin/stat_model', 'page', true);
            $stat = $this->page->get_one($alias);
            $data['stat'] = $stat[0];
            $this->load->view('admin/main', $data);
        }
        else {
            redirect('admin/staticpage');
        }
    }
    
    function delete() {
        $alias = $this->uri->segment(4, '');
        if (!empty($alias)) {
            $this->load->model('admin/stat_model', 'page', true);
            $stat = $this->page->delete($alias);
            $this->session->set_userdata('message', 'Статичная страница успешно удалена');
        }
        redirect('admin/staticpage');
    }
    
    function create() {
        $data = array();                            // Создается новый пустой массив
        $data['template'] = 'admin/staticpage/create';//Подключается шаблон
        $data['res'] = $this->router->fetch_class();//Узнаем какой контроллер задействован и передаем его в шаблон
        $this->load->model('admin/stat_model', 'page', true);//загружаем модель
        $this->load->library('form_validation');
        $this->form_validation->set_rules('title', 'Заголовок', 'trim|required|min_length[2]|max_length[400]|xss_clean');
        $this->form_validation->set_rules('text', 'Текст', 'trim|required|min_length[2]|xss_clean');
        $this->form_validation->set_rules('alias', 'Алиас', 'trim|required|min_length[2]|max_length[200]|xss_clean');
        $this->form_validation->set_rules('description', 'Description', 'trim|xss_clean');
        if ($this->input->post('action', '') == 'save') {
            if ($this->form_validation->run() == FALSE) {
                $data['error'] = ' ';
                $this->load->view('admin/main', $data);//загружаем вьювер
            }
            else {
                $alias = $this->input->post('alias');
                $page = $this->page->get_one($alias);
                if (!empty($page)) {
                    $data['error_alias'] = 'Такой алиас уже существует.';
                    $this->load->view('admin/main', $data);//загружаем вьювер
                }
                else {
                    $result = array(
                        'title'=>$this->input->post('title'),
                        'text'=>$this->input->post('text'),
                        'alias'=>$this->input->post('alias'),
                        'description'=>set_value('description'),
                    );
                    $save = $this->page->save($result);
                    $this->session->set_userdata('message', 'Статичная страница успешно создана');
                    redirect('admin/staticpage');
                }
            }

        }
        else {

            $this->load->view('admin/main', $data);//загружаем вьювер
        }
    }
    
    function edit() {
        $data = array();                            // Создается новый пустой массив
        $data['template'] = 'admin/staticpage/edit';//Подключается шаблон
        $data['res'] = $this->router->fetch_class();//Узнаем какой контроллер задействован и передаем его в шаблон
        $this->load->model('admin/stat_model', 'page', true);//загружаем модель
        $this->load->library('form_validation');
        $this->form_validation->set_rules('title', 'Заголовок', 'trim|required|min_length[2]|max_length[400]|xss_clean');
        $this->form_validation->set_rules('text', 'Текст', 'trim|required|min_length[2]|xss_clean');
        $this->form_validation->set_rules('alias', 'Алиас', 'trim|required|min_length[2]|max_length[200]|xss_clean');
        $this->form_validation->set_rules('description', 'Description', 'trim|xss_clean');
        $alias = $this->uri->segment(4, '');
        if (!empty($alias)) {
            $q = $this->page->get_one($alias);//Список
            if (!empty($q[0])) {
                $data['page'] = $q;
                if ($this->input->post('action', '') == 'save') {
                    if ($this->form_validation->run() == FALSE) {
                        $data['error'] = ' ';
                        $this->load->view('admin/main', $data);//загружаем вьювер
                    }
                    else {
                        $result = array(
                            'title'=>set_value('title'),
                            'text'=>$this->input->post('text'),
                            'alias'=>set_value('alias'),
                            'description'=>$this->input->post('description'),
                            'old_alias' =>$alias
                            );
                        $update = $this->page->update($result);
                        $this->session->set_userdata('message', 'Статичная страница успешно отредактирована');
                        redirect('admin/staticpage');
                    }

                }
                else {
                    $this->load->view('admin/main', $data);//загружаем вьювер
                }
            }
            else {
                redirect('admin/staticpage');
            }
        }
        else {
            redirect('admin/staticpage');
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

<?php

class Region extends Controller {
    
    function Region() {
        parent::Controller();
    }
    
    function index() {
        $data = array();
        $data['template'] = 'admin/region/view';
        $data['res'] = $this->router->fetch_class();
        $this->load->model('admin/region_model', 'region', true);
        if ($message = $this->session->userdata('message')) {
            $data['message'] = $message;
            $this->session->unset_userdata('message');
        }
        $data['region'] = $this->region->get_all();
        $this->load->view('admin/main', $data);
    }
    
    function create() {
        $data = array();
        $data['template'] = 'admin/region/create';
        $data['res'] = $this->router->fetch_class();
        $this->load->model('admin/region_model', 'region', true);
        $this->load->model('admin/users_model', 'users', true);
        $data['users'] = $this->users->get_all();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Название региона', 'trim|required|min_length[3]|xss_clean');
        $this->form_validation->set_rules('users_id', 'Пользователь', 'trim|xss_clean');
        $this->form_validation->set_rules('phone', 'Телефон', 'trim|xss_clean');
        $this->form_validation->set_rules('adress', 'Физический адрес', 'trim|xss_clean');
        if ($this->input->post('action', '') == 'save') {
            if ($this->form_validation->run() == FALSE) {
                $data['error'] = ' ';
                $this->load->view('admin/main', $data);//загружаем вьювер
            }
            else {
                $user_id = $this->input->post('users_id');
                if (empty($user_id)) {
                    $user_id = '0';
                }
                $result = array(
                    'name'=>$this->input->post('name'),
                    'users_id'=>$user_id,
                    'phone'=>$this->input->post('phone'),
                    'adress'=>$this->input->post('adress'),
                );
                $save = $this->region->save($result);
                $region_id = $save;
                $id = $this->input->post('users_id');
                $this->users->updateRegion($region_id, $id);
                $this->session->set_userdata('message', 'Добавлен новый регион');
                redirect('admin/region');
            }
        }
        else {
            $this->load->view('admin/main', $data);//загружаем вьювер
        }
    }
    
    function edit() {
        $data = array();
        $data['template'] = 'admin/region/edit';
        $data['res'] = $this->router->fetch_class();
        $this->load->model('admin/region_model', 'region', true);
        $this->load->model('admin/users_model', 'users', true);
        $data['users'] = $this->users->get_all();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Название региона', 'trim|required|min_length[3]|xss_clean');
        $this->form_validation->set_rules('users_id', 'Пользователь', 'trim|xss_clean');
        $this->form_validation->set_rules('phone', 'Телефон', 'trim|xss_clean');
        $this->form_validation->set_rules('adress', 'Физический адрес', 'trim|xss_clean');
        $id = $this->uri->segment(4, '');
        $region = $this->region->getById($id);
        $data['now_region'] = $region;
        if ($this->input->post('action', '') == 'save') {
            if ($this->form_validation->run() == FALSE) {
                $data['error'] = ' ';
                $this->load->view('admin/main', $data);//загружаем вьювер
            }
            else {
                $result = array(
                    'name'=>$this->input->post('name'),
                    'users_id'=>$this->input->post('users_id'),
                    'id'=>$id,
                    'phone'=>$this->input->post('phone'),
                    'adress'=>$this->input->post('adress'),
                );
                $save = $this->region->update($result);
                $region_id = $id;
                $user_id = $this->input->post('users_id');
                $this->users->updateRegion('0',$region[0]['users_id']); //Прошлуму юзверю ставим 0
                $this->users->updateRegion($region_id, $user_id); // Обновляем нового юзверя
                $this->session->set_userdata('message', 'Отредактирован регион');
                redirect('admin/region');
            }
        }
        else {
            $this->load->view('admin/main', $data);//загружаем вьювер
        }
    }
    
    function delete() {
        $id = $this->uri->segment(4, '');
        $this->load->model('admin/region_model', 'region', true);
        $this->load->model('admin/users_model', 'users', true);
        $region = $this->region->getById($id);  //Достаем регион по айдишнику
        $region_id = 0;
        $this->users->updateRegion($region_id, $region[0]['users_id']); // Обновляем юзверя
        $this->session->set_userdata('message', 'Удален регион');
        $this->region->delete($id);
        redirect('admin/region');
    }
    
    function _remap($method) {
        $this->load->library('authorization');
        $params = null;

        if ($this->uri->segment(5)) {
            $params = $this->uri->segment(5);
        }
//        echo $user_type;
        if (!$this->authorization->is_logged_in()) {
            redirect("auth/login");
        } else {
            check_perms();
            $this->$method($params);
        }
    }
}

?>
<?php

class Users extends Controller {
    
    function Users() {
        parent::Controller();
    }
    
    function index() {
        $data = array();                            // Создается новый пустой массив
        $data['template'] = 'admin/users/view';//Подключается шаблон
        $data['res'] = $this->router->fetch_class();//Узнаем какой контроллер задействован и передаем его в шаблон
        $this->load->model('admin/users_model', 'users', true);//загружаем модель
        $users = $this->users->get_all();//получаем список статичных страниц в массиве
        $data['users'] = $users;//передаем во вьювер

        if ($message = $this->session->userdata('message')) {
            $data['message'] = $message;
            $this->session->unset_userdata('message');
        }
//загружаем вьювер
        $this->load->view('admin/main', $data);
    }
    
    function create() {
        $data = array();                            // Создается новый пустой массив
        $data['template'] = 'admin/users/create';//Подключается шаблон
        $data['res'] = $this->router->fetch_class();//Узнаем какой контроллер задействован и передаем его в шаблон
        $this->load->model('admin/users_model', 'users', true);//загружаем модель
        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'Имя пользователя', 'trim|required|min_length[2]|max_length[400]|xss_clean');
        $this->form_validation->set_rules('login', 'Логин пользователя', 'trim|required|min_length[2]|max_length[250]|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|min_length[4]|xss_clean');
        $this->form_validation->set_rules('password', 'Пароль', 'trim|required|min_length[4]|xss_clean');
        $data['permission'] = $this->config->item('permission'); // Использование глобальной переменной;
        foreach ($data['permission'] as $key=>$item) {
            foreach ($item as $value) {
                if (gettype($value) == "array") {
                    foreach ($value as $method=>$description) {
                        $this->form_validation->set_rules($method . '-' . $key, $names, 'trim|xss_clean');
                    }
                }
                else {
                    $names = $value;
                }
            }
        }
        if ($this->input->post('action', '') == 'save') {
            if ($this->form_validation->run() == FALSE) {
                $data['error'] = ' ';
                $this->load->view('admin/main', $data);//загружаем вьювер
            }
            else {
                $result = array(
                    'username'=>$this->input->post('username'),
                    'login'=>$this->input->post('login'),
                    'email'=>$this->input->post('email'),
                    'password'=>$this->input->post('password'),
                    'region_id'=>'0'
                );
                $save = $this->users->save($result);
                $id = $save;
                //Заносим права пользователей в табличку
                foreach ($data['permission'] as $key=>$item) {
                    foreach ($item as $value) {
                        if (gettype($value) == "array") {
                            foreach ($value as $method=>$description) {
                                if ($key == 'status') {
                                    if ($this->input->post($method . '-' . $key)) {
                                        $result = array(
                                            'user_id' => $id,
                                            'status'=>$method,
                                        );
                                        $this->users->SaveOrderStatusPermission($result);
                                    }
                                }
                                else {
                                    if ($this->input->post($method . '-' . $key)) {
                                        $result = array(
                                            'users_id' => $id,
                                            'permissions'=>$key . '/' . $method,
                                        );
                                        $this->users->SavePremmision($result);
                                    }
                                }
                            }
                        }
                    }
                }
                $this->session->set_userdata('message', 'Пользователь успешно создан');
                redirect('admin/users');
            }
        }
        else {
            $this->load->view('admin/main', $data);//загружаем вьювер
        }
    }
    
    function edit() {
        $data = array();                            // Создается новый пустой массив
        $data['template'] = 'admin/users/edit';//Подключается шаблон
        $data['res'] = $this->router->fetch_class();//Узнаем какой контроллер задействован и передаем его в шаблон
        $this->load->model('admin/users_model', 'users', true);//загружаем модель
        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'Имя пользователя', 'trim|required|min_length[2]|max_length[400]|xss_clean');
        $this->form_validation->set_rules('login', 'Логин пользователя', 'trim|required|min_length[2]|max_length[250]|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|min_length[4]|xss_clean');
        $this->form_validation->set_rules('password', 'Пароль', 'trim|required|min_length[4]|xss_clean');
        $data['permission'] = $this->config->item('permission'); // Использование глобальной переменной;
        foreach ($data['permission'] as $key=>$item) {
            foreach ($item as $value) {
                if (gettype($value) == "array") {
                    foreach ($value as $method=>$description) {
                        $this->form_validation->set_rules($method . '-' . $key, $names, 'trim|xss_clean');
                    }
                }
                else {
                    $names = $value;
                }
            }
        }
        $id = $this->uri->segment(4, '');
        $users = $this->users->getById($id);
        $permissions = $this->users->getPermissions($id);
        $permissions_status = $this->users->getOrderStatusPermission($id);
        //Делаем массив с текущими правами
        if ($permissions_status) {
            foreach ($permissions_status as $key=>$value) {
                $new_permissions_status[$value['status']] = 'true';
            }
        }
        else {
            $new_permissions_status['array'] = 'array';
        }
        if ($permissions) {
            foreach ($permissions as $key=>$value) {
                $new_permissions[$value['permissions']] = 'true';
            }
        }
        else {
            $new_permissions['array'] = 'array';
        }
        $data['permissions'] = $new_permissions; //Права
        $data['permission_status'] = $new_permissions_status; // Статусы
        if (!empty($users)) {
            $data['users'] = $users;
            if ($this->input->post('action', '') == 'save') {
                if ($this->form_validation->run() == FALSE) {
                    $data['error'] = ' ';
                    $this->load->view('admin/main', $data);//загружаем вьювер
                }
                else {
                    $result = array(
                        'username'=>$this->input->post('username'),
                        'login'=>$this->input->post('login'),
                        'email'=>$this->input->post('email'),
                        'password'=>$this->input->post('password'),
                        'id'=>$id,
                        'region_id'=> $users[0]['region_id'],
                        'type'=>$users[0]['type']
                    );
                    $update = $this->users->update($result);
                    $this->users->removeOldPermissions($id);
                    $this->users->removeOldOrderStatusPermission($id);
                    foreach ($data['permission'] as $key=>$item) {
                        foreach ($item as $value) {
                            if (gettype($value) == "array") {
                                foreach ($value as $method=>$description) {
                                    if ($key == 'status') {
                                        if ($this->input->post($method . '-' . $key)) {
                                            $result = array(
                                                'user_id' => $id,
                                                'status'=>$method,
                                            );
                                            $this->users->SaveOrderStatusPermission($result);
                                        }
                                    }
                                    else {
                                        if ($this->input->post($method . '-' . $key)) {
                                            $result = array(
                                                'users_id' => $id,
                                                'permissions'=>$key . '/' . $method,
                                            );
                                            $this->users->SavePremmision($result);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $this->session->set_userdata('message', 'Пользователь успешно обновлен');
                    redirect('admin/users');
                }
            }
            else {
                $this->load->view('admin/main', $data);//загружаем вьювер
            }
        }
        else {
            redirect('admin/users');
        }
    }
    
    function delete() {
        $id = $this->uri->segment(4, '');
        if (!empty($id)) {
            $this->load->model('admin/users_model', 'users', true);//загружаем модель
            $this->users->removeOldPermissions($id);
            $this->users->removeOldOrderStatusPermission($id);
            $this->users->removeUser($id);
            $this->session->set_userdata('message', 'Пользователь успешно удален');
            redirect('/admin/users');
        }
        else {
            $this->session->set_userdata('message', 'Пользователь не удален, укажите ID пожалуйста');
            redirect('/admin/users');
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

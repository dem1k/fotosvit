<?php

class Ord extends Controller {
    
    function Ord() {
        parent::Controller();
    }
    
    function index() {
        $data = array();                            // Создается новый пустой массив
        $data['template'] = 'admin/orders/view';//Подключается шаблон
        $data['res'] = $this->router->fetch_class();//Узнаем какой контроллер задействован и передаем его в шаблон
        $this->load->model('admin/orders', 'orders', true);//загружаем модель
        $orders = $this->orders->get_all();//получаем список статичных страниц в массиве
        $data['orders'] = $orders;//передаем во вьювер
        $this->load->model('admin/region_model', 'region', true);
        $region = $this->region->get_all();
        if (!empty($region)) {
            foreach ($region as $key=>$value) {
                $new_region[$value->id] = $value->name;
            }
        }
        else {
            $new_region = '';
        }
        $data['region'] = $new_region;
        if ($message = $this->session->userdata('message')) {
            $data['message'] = $message;
            $this->session->unset_userdata('message');
        }
        $user_region_id = $this->session->userdata('user_region_id');
        $user_id = $this->session->userdata('user_id'); // Айди залогиневшегося юзверя
        $this->load->model('admin/users_model', 'users', true);
        $permission_status = $this->users->getOrderStatusPermission($user_id);
        if (!empty($permission_status)) {
            foreach ($permission_status as $key=>$value) {
                $new_permission_status[$value['status']] = 'true';
            }
        }
        else {
            $new_permission_status['array'] = 'array';
        }
        if (!empty($region)) {
            if (isset($new_region[$user_region_id])) {
                $region_name = $new_region[$user_region_id];
            }
            else{
                $region_name = NULL;
            }
        }
        else {
            $region_name = NULL;
        }
        $data['permission_status'] = $new_permission_status;
        $data['permission'] = $this->config->item('permission'); // Использование глобальной переменной;
        $data['region_name'] = $region_name;
        $data['user_region_id'] = $user_region_id;
        $this->load->view('/admin/main', $data);
    }
    
    function view() {
        $id = $this->uri->segment(4, '');
        if (!empty($id)) {
            $data = array();                            // Создается новый пустой массив
            $data['template'] = 'admin/orders/display';//Подключается шаблон
            $data['res'] = $this->router->fetch_class();//Узнаем какой контроллер задействован и передаем его в шаблон
            $this->load->model('admin/orders', 'orders', true);//загружаем модель
            $orders = $this->orders->get_one($id);
            $name = array();
            if (!empty($orders[0])) {
                $orders_item = $this->orders->get_item($id);
                $this->load->model('admin/product_model', 'product', true);
                foreach ($orders_item as $value) {
                    $prod = $this->product->get_one($value->product_id);
                    if (isset($prod[0])) {
                        $prod = $prod[0];
                        $name[][$value->order_id] = $prod->name;
                        $product_id_in_price[$value->product_id] = $prod->id_in_price;
                    }
                }
                $data['name'] = $name;
                $data['orders'] = $orders;//передаем во вьювер
                $data['orders_item'] = $orders_item;
                //Забираем баллы
                $maindomain = $this->config->item('domains');
                $this->load->library('curl');
                $points = '';
                foreach ($product_id_in_price as $key => $value) {
                    $this->curl->open();
                    $url_service = $maindomain['service'] . "/goods/" . $value . "/show";
                    $content = $this->curl->http_get($url_service, $headers = array('Accept : application/json'));
                    $headers = $this->curl->get_http_code();
                    $this->curl->close();
                    $content = json_decode($content);
                    if ($headers == '200') {
                        $points[$key] = $content;    // Айди продукта => количество баллов массив
                    }
                }
                if (!empty($points)) {
                    foreach ($points as $key=>$value) {
                        $new_points[$key] = $value->goods->points;  //Айди продукта=> количество баллов
                    }
                }
                if(!isset($new_points)){
                    $new_points = '0';
                }
                $data['points'] = $new_points;
                //Забираем баллы
                $this->load->view('/admin/main', $data);
            }
            else {
                redirect('admin/ord');
            }
        }
        else {
            redirect('/admin/ord');
        }
    }
    
    function edit_info() {
        $id = $this->uri->segment(4, '');
        if (!empty($id)) {
            $data = array();                            // Создается новый пустой массив
            $data['template'] = 'admin/orders/edit_info';//Подключается шаблон
            $data['res'] = $this->router->fetch_class();//Узнаем какой контроллер задействован и передаем его в шаблон
            $this->load->model('admin/orders', 'orders', true);//загружаем модель
            $orders = $this->orders->get_one($id);
            if (!empty($orders[0])) {
                $data['orders'] = $orders;
                $this->load->library('form_validation');
                $this->form_validation->set_rules('name', 'Имя', 'trim|required|min_length[3]|max_length[250]|xss_clean');
                $this->form_validation->set_rules('phone', 'Телефон', 'trim|required|min_length[5]|xss_clean');
                $this->form_validation->set_rules('description', 'Дополнительная информация', 'trim|max_length[400]|xss_clean');
                $this->form_validation->set_rules('country', 'Страна', 'trim|required|min_length[3]|max_length[250]|xss_clean');
                $this->form_validation->set_rules('city', 'Город', 'trim|min_length[3]|max_length[250]|xss_clean');
                $this->form_validation->set_rules('street', 'Улица', 'trim|min_length[3]|max_length[250]|xss_clean');
                $this->form_validation->set_rules('home', 'Номер дома', 'trim|min_length[1]|max_length[100]|xss_clean');
                $this->form_validation->set_rules('flat', 'Квартира', 'trim|min_length[1]|max_length[100]|xss_clean');
                $this->form_validation->set_rules('email', 'email', 'trim|required|min_length[4]|max_length[200]|valid_email|xss_clean');
                if ($this->input->post('action', '') == 'save') {
                    if ($this->form_validation->run() == FALSE) {
                        $data['error'] = ' ';
                        $this->load->view('admin/main', $data);//загружаем вьювер
                    }
                    else {
                        $result = array(
                            'name'=>$this->input->post('name'),
                            'description'=>$this->input->post('description'),
                            'phone'=>$this->input->post('phone'),
                            'country'=>$this->input->post('country'),
                            'city'=>$this->input->post('city'),
                            'street'=>$this->input->post('street'),
                            'home'=>$this->input->post('home'),
                            'flat'=>$this->input->post('flat'),
                            'email'=>$this->input->post('email'),
                            'status'=>$this->input->post('status'),
                            'ref'=>$this->input->post('ref'),
                            'id'=>$id
                        );

                        $this->orders->update_contact($result);
                        $this->session->set_userdata('message', 'Контакты клиента обновлены');
                        redirect('/admin/ord/');
                    }
                }
                else {
                    $this->load->view('/admin/main', $data);//загружаем вьювер
                }
            }
            else {
                redirect('/admin/ord');
            }
        }
        else {
            redirect('/admin/ord');
        }
    }
    
    function edit_product() {
        $id = $this->uri->segment(4, '');
        if (!empty($id)) {
            $data = array();                            // Создается новый пустой массив
            $data['template'] = 'admin/orders/edit_products';//Подключается шаблон
            $data['res'] = $this->router->fetch_class();//Узнаем какой контроллер задействован и передаем его в шаблон
            $this->load->model('admin/orders', 'orders', true);//загружаем модель
            $this->load->model('admin/product_model', 'product', true);//загружаем модель
            $this->load->model('admin/currency_model', 'currency', true);//загружаем модель
            $data['currency'] = $this->currency->get_all();
            $product = $this->product->get_all();
            if (!empty($product)) {
                foreach ($product as $key=>$value) {
                    $new_product[$value->id] = $value->name;
                }
            }
            else {
                $new_product = NULL;
            }
            $cart = $this->orders->get_item($id);
            $count_cart = count($cart[0]);
            $count_cart = $count_cart - 1;
            $this->load->library('form_validation');
            $data['id'] = $id;
            $this->form_validation->set_rules('count[]', 'Количество', 'trim|integer|xss_clean');
            if (!empty($cart)) {
                $data['cart'] = $cart;
                $data['product'] = $new_product;
                if ($this->input->post('action', '') == 'save') {
                    if ($this->form_validation->run() == FALSE) {
                        $data['error'] = 'Проверьте пожалуйста данные перед заполнением. Вы где-то ошиблись. ';
                        $this->load->view('admin/main', $data);//загружаем вьювер
                    }
                    else {
                        $count = $this->input->post('count');
                        $product_id = $this->input->post('product_id');
                        $price = $this->input->post('price');
                        $exchange = $this->input->post('exchange_name');
                        $order_id = $this->input->post('order_id');
                        $all = count($count); // Количество заказанных продуктов
                        $all = $all - 1;
                        for ($i = 0;$i <= $all;$i++) {
                            $resultproduct[$i]['order_id'] = $order_id;          //Номер заказа
                            $resultproduct[$i]['price'] = $price[$i];          //Валюта
                            $resultproduct[$i]['exchange'] = $exchange;          //Валюта заказа
                            $resultproduct[$i]['product_id'] = $product_id[$i]; //  Номер айди
                            $resultproduct[$i]['count'] = $count[$i]; //Количество

                        }
                        $this->orders->delete_item($order_id);
                        foreach ($resultproduct as $key=>$value) {
                            $result = array(
                                'order_id'=>$value['order_id'],
                                'product_id'=>$value['product_id'],
                                'price'=>$value['price'],
                                'count'=>$value['count'],
                                'exchange'=>$value['exchange'],
                            );
                            $this->orders->save_item($result);
                        }
                        $this->session->set_userdata('message', 'Заказанные продукты отредактированы');
                        redirect('/admin/ord');
                    }
                }
                else {
                    $this->load->view('admin/main', $data);//загружаем вьювер
                }
            }
            else {
                redirect('/admin/ord');
            }
        }
        else {
            redirect('/admin/ord');
        }
    }
    
    function status() {
        $id = $this->uri->segment(4, '');
        if (!empty($id)) {
            $status = $this->input->post('status');
            switch ($status) // переключающее выражение 'canceled','new','inprocess','paid','money_back','sent','pending'
            {
                case 'canceled':
                    break;
                case 'new':
                    break;
                case 'not_paid':
                    break;
                case 'paid_not_sent':
                    break;
                case 'paid_sent':
                    break;
                case 'problem':
                    break;
                case 'sent':
                    break;
                case 'remove':
                    break;
                case 'received_not_phone':
                    break;
                default:
                    $status = 'new';
            }
            $this->load->model('admin/orders', '', true);
            $this->orders->status_update($id, $status);
            $this->session->set_userdata('message', 'Статус успешно отредактирован');
            redirect('admin/ord');
        }
        else {
            $this->session->set_userdata('message', 'Статус не отредактирован');
            redirect('/admin/ord');
        }
    }
    
    function region() {
        $id = $this->uri->segment(4, '');
        if (!empty($id)) {
            $region_id = $this->input->post('region');
            $this->load->model('admin/orders', '', true);
            $this->orders->region_update($region_id, $id);
            $this->session->set_userdata('message', 'Регион успешно отредактирован');
            redirect('admin/ord');
        }
        else {
            $this->session->set_userdata('message', 'Регион не отредактирован');
            redirect('/admin/ord');
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

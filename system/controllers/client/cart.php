<?php

class Cart extends Controller {
    
    function Cart() {
        parent::Controller();
    }
    
    function index() {
        $method = $this->input->server('REQUEST_METHOD');
        $add = 'true';
        if ($method == 'POST') {
            $id = $this->input->post('id');
            $this->load->model('admin/product_model', 'product', true);
            $this->load->model('admin/currency_model', 'currency', true);
            if ($exchange = $this->session->userdata('exchange')) { // Получить валюту
                $exchange_all = $this->currency->get_all();
                foreach ($exchange_all as $value) {
                    if ($value['id'] == $exchange) {
                        $exchange = $value['name'];
                        $exchange_id = $value['id'];
                    }
                }
            }
            else {
                $currency_now = $this->currency->getFirst();
                if (!empty($currency_now)) {
                    $exchange = $currency_now[0]['name'];
                    $exchange_id = $currency_now[0]['id'];
                } else {
                    $exchange = NULL;
                }

            }
            if (!empty($id)) {
                $query = $this->product->get_one($id);
                $product_price = $this->product->GetPriceId($id, $exchange_id);
                foreach ($product_price as $key => $value) {
                    $key_product['price'][$value['product_id']] = $value['price'];
                    $key_product['price_for_partner'][$value['product_id']] = $value['price_for_partner'];
                }
                if ($authorization = $this->session->userdata('authorization')) {
                    $price = $key_product['price_for_partner'][$id];
                }
                else {
                    $price = $key_product['price'][$id];
                }
                $cart = $this->session->userdata('cart');
                if (!empty($cart)) {
                    foreach ($cart as $item) {
                        if ($item['id'] == $id) {
                            $add = 'false';
                        }
                    }
                }
                if ($add == 'true') {
                    $result = array(
                        'id'=> $id,
                        'price'=>$price,
                        'exchange'=>$exchange,
                        'name'=>$query[0]->name,
                        'status'=>'ok',
                    );
                    $cart[] = $result;

                    $this->session->set_userdata('cart', $cart);
                    echo json_encode($result);
                }
                else {
                    $result = array(
                        'status'=>'alredy exist',
                    );
                    echo json_encode($result);
                }
            }
            else {
                $result = array(
                    'status'=>'error',
                );
                echo json_encode($result);
            }
        }
        else {
            redirect('/product');
        }
    }
    
    function buy() {
        /////Продукты сайтбар
        if ($cart = $this->session->userdata('cart')) {
            $data['count_cart'] = count($cart);
        }
        else {
            $data['count_cart'] = 0;
        }
        if ($came_from = $this->session->userdata('error')) {
            $data['came_from'] = $came_from;
            $this->session->unset_userdata('error');
        }
        $this->load->model('admin/product_model', 'product', true); // Для трех продуктов которые
        $data['sidebarproduct'] = $this->product->get_three();//находятся всегда сбоку
        $data['sidebarproductimg'] = $this->product->get_all_img();//Так же достаем картинки
        if ($authorization = $this->session->userdata('authorization')) {
            $data['auth'] = $authorization;
        }
        /////Продукты, сайтбар
        $cart = $this->session->userdata('cart');
        $data['cart'] = $cart;
        $data['title'] = 'Корзина товаров';
        $data['template'] = 'client/cart/buy';
        $data['sidebar_product'] = 'false';
        $this->load->library('form_validation');
        //Валюта
        $this->load->model('admin/currency_model', 'currency', true); // Для трех продуктов которые
        if ($exchange = $this->session->userdata('exchange')) { // Получить валюту
            $exchange_all = $this->currency->get_all();
            foreach ($exchange_all as $value) {
                if ($value['id'] == $exchange) {
                    $exchange = $value['name'];
                }
            }
        }
        else {
            $currency_now = $this->currency->getFirst();
            if (!empty($currency_now)) {
                $exchange = $currency_now[0]['name'];
            } else {
                $exchange = NULL;
            }

        }
        //Конец валюты
        $count_cart = count($cart);
        $count_cart = $count_cart - 1;
        for ($i = 0; $i <= $count_cart; $i++) {
            $this->form_validation->set_rules('count[' . $i . ']', 'Количество', 'trim|required|integer|xss_clean');
        }
        if ($this->input->post('action', '') == 'save') {
            if ($this->form_validation->run() == FALSE) {
                $data['error'] = ' ';
                $this->load->view('client/main', $data);//загружаем вьювер
            }
            else {
                $count = $this->input->post('count');
                $data['count'] = $count;
                $ids = $this->input->post('ids');
                $all = count($count); // Количество заказанных продуктов
                $all = $all - 1;
                if ($all < 0) {
                    redirect('/cart/buy');
                }
                else {
                    for ($i = 0;$i <= $all;$i++) {
                        $resultproduct[$ids[$i]] = $count[$i]; // Создаем массив айди => количество
                    }
                    $this->session->set_userdata('buy', '1');
                    $this->session->set_userdata('resultproduct', $resultproduct);

                }
                $submit = $this->input->post('submit');
                if ($submit == 'Далее') {
                    redirect('cart/info');
                }
                else {
                    $summ = 0;
                    if (!empty($cart)) {
                        foreach ($cart as $item) {
                            $q = $item['price'] * $resultproduct[$item['id']];
                            $summ = $summ + $q;
                        }
                        $data['summ'] = $summ;
                        $this->load->view('client/main', $data);//загружаем вьювер
                    }
                    else {
                        redirect('/cart/buy');
                    }
                }
            }
        }
        else {
            $this->load->view('client/main', $data);//загружаем вьювер
        }
    }
    
    function info() {
        /////Продукты сайтбар
        if ($cart = $this->session->userdata('cart')) {
            $data['count_cart'] = count($cart);
        }
        else {
            $data['count_cart'] = 0;
        }
        if ($came_from = $this->session->userdata('error')) { // Ошибка аутентификации
            $data['came_from'] = $came_from;
            $this->session->unset_userdata('error');
        }
        $this->load->model('admin/product_model', 'product', true); // Для трех продуктов которые
        $data['sidebarproduct'] = $this->product->get_three();//находятся всегда сбоку
        $data['sidebarproductimg'] = $this->product->get_all_img();//Так же достаем картинки
        if ($authorization = $this->session->userdata('authorization')) {
            $data['auth'] = $authorization;
        }
        /////Продукты, сайтбар
        $cart = $this->session->userdata('cart');
        $data['cart'] = $cart;
        $data['title'] = 'Оформление заказа: Ввод персональных данных';
        $data['template'] = 'client/cart/info';
        $data['sidebar_product'] = 'false';
        $buy = $this->session->userdata('buy');
        if ($buy == '1') {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('name', 'Имя', 'trim|required|min_length[3]|max_length[250]|xss_clean');
            $this->form_validation->set_rules('phone', 'Телефон', 'trim|required|min_length[5]|xss_clean');
            $this->form_validation->set_rules('description', 'Дополнительная информация', 'trim|max_length[400]|xss_clean');
            $this->form_validation->set_rules('country', 'Страна', 'trim|required|min_length[3]|max_length[250]|xss_clean');
            $this->form_validation->set_rules('city', 'Город', 'trim|required|min_length[3]|max_length[250]|xss_clean');
            $this->form_validation->set_rules('street', 'Улица', 'trim|min_length[3]|max_length[250]|xss_clean');
            $this->form_validation->set_rules('home', 'Номер дома', 'trim|min_length[1]|max_length[100]|xss_clean');
            $this->form_validation->set_rules('flat', 'Квартира', 'trim|min_length[1]|max_length[100]|xss_clean');
            $this->form_validation->set_rules('email', 'e-mail', 'trim|required|min_length[4]|max_length[200]|valid_email|xss_clean');
            if ($this->input->post('action', '') == 'save') {
                if ($this->form_validation->run() == FALSE) {
                    $data['error'] = ' ';
                    $this->load->view('client/main', $data);//загружаем вьювер
                }
                else {
                    $ref = $this->session->userdata('ref');
                    if (empty($ref)) {
                        $ref = 0;
                    }
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
                        'status'=>'new',
                        'ref'=>$ref
                    );
                    $this->session->set_userdata('details', $result);
                    $this->session->set_userdata('buy', '2');
                    redirect('/cart/add');
                }
            }
            else {
                $this->load->view('client/main', $data);//загружаем вьювер
            }
        }
        else {
            redirect('/cart/buy');
        }
    }
    
    function add() {
        if ($cart = $this->session->userdata('cart')) {
            $data['count_cart'] = count($cart);
        }
        else {
            $data['count_cart'] = 0;
        }
        if ($came_from = $this->session->userdata('error')) { // Ошибка аутентификации
            $data['came_from'] = $came_from;
            $this->session->unset_userdata('error');
        }
        $details = $this->session->userdata('details');
        $resultproduct = $this->session->userdata('resultproduct');
        $cart = $this->session->userdata('cart');
        $method = $this->input->server('REQUEST_METHOD');
        $add = 'true';
        //Валюта
        $this->load->model('admin/currency_model', 'currency', true); // Для трех продуктов которые
        if ($exchange = $this->session->userdata('exchange')) { // Получить валюту
            $exchange_all = $this->currency->get_all();
            foreach ($exchange_all as $value) {
                if ($value['id'] == $exchange) {
                    $exchange = $value['name'];
                }
            }
        }
        else {
            $currency_now = $this->currency->getFirst();
            if (!empty($currency_now)) {
                $exchange = $currency_now[0]['name'];
            } else {
                $exchange = NULL;
            }

        }
        //Конец валюты
        if ($method != 'POST') {
            $buy = $this->session->userdata('buy');
            if ($buy == '2') {
                /////Продукты сайтбар
                $this->load->model('admin/product_model', 'product', true); // Для трех продуктов которые
                $data['sidebarproduct'] = $this->product->get_three();//находятся всегда сбоку
                $data['sidebarproductimg'] = $this->product->get_all_img();//Так же достаем картинки
                if ($authorization = $this->session->userdata('authorization')) {
                    $data['auth'] = $authorization;
                }
                /////Продукты, сайтбар
                $cart = $this->session->userdata('cart');
                $data['cart'] = $cart;
                $data['title'] = 'Оформление заказа: Подтверждение данных';
                $data['template'] = 'client/cart/add';
                $data['sidebar_product'] = 'false';
                $data['details'] = $details;
                $data['result'] = $resultproduct;
                $data['cart'] = $cart;
                $summ = 0;
                foreach ($cart as $item) {
                    $q = $item['price'] * $resultproduct[$item['id']];
                    $summ = $summ + $q;
                }
                $data['exchange'] = $exchange;
                $data['summ'] = $summ;
                $this->load->view('client/main', $data);//загружаем вьювер
            }
            else {
                redirect('cart/info');
            }
        }
        else {
            if ($this->input->post('action', '') == 'save') {
                if (!empty($details)) {
                    $details['create_date'] = date("Y-m-d"); // Дата оформления заказа
                    $data['save'] = '1';
                    /////Продукты сайтбар
                    $this->load->model('admin/product_model', 'product', true); // Для трех продуктов которые
                    $data['sidebarproduct'] = $this->product->get_three();//находятся всегда сбоку
                    $data['sidebarproductimg'] = $this->product->get_all_img();//Так же достаем картинки
                    if ($authorization = $this->session->userdata('authorization')) {
                        $data['auth'] = $authorization;
                    }
                    /////Продукты, сайтбар
                    $cart = $this->session->userdata('cart');
                    $data['cart'] = $cart;
                    $data['title'] = 'Успешное оформление';
                    $data['template'] = 'client/cart/add';
                    $data['sidebar_product'] = 'false';
                    //Сохранение в бд
                    $this->load->model('admin/orders', '', true);
                    $this->orders->save_contact($details);
                    $id = $this->orders->get_last_id();
                    $id = $id[0]->id;
                    foreach ($cart as $item) {
                        $price = $item['price'];
                        $query = array(
                            'order_id'=>$id,
                            'product_id'=>$item['id'],
                            'price'=>$price,
                            'count'=>$resultproduct[$item['id']],
                            'exchange'=>$exchange,
                        );
                        $this->orders->save_item($query);
                    }
                    //Сохранение в бд
                    //Посылаем почтовое сообщение
                    $mess = 'Контактные данные:
        Имя: '.$details['name'].'
        Телефон: '.$details['phone'].'
        Описание: '.$details['description'].'
        Страна: '. $details['country'].'
        Город: '.$details['city'].'
        Улица: '.$details['street'].'
        Дом: '.$details['home'].'
        Квартира: '.$details['flat'].'
        Почта: '.$details['email'].'
        Реферал: '.$details['ref'].'

Заказ:

';
                    $cart = $this->session->userdata('cart');
                    foreach ($cart  as $key => $value) {
                        $mess .= $value['name'].' в количестве '.$resultproduct[$value['id']].' по цене '.$value['price'].$value['exchange'].' за 1 шт.
';
                    }
                    $this->config->load('mailconf');
                    $mailconf = $this->config->item('mailconf');
                    $admin_mail = $mailconf['admin_mail'];
                    unset ($mailconf['admin_mail']);
                    $from = $mailconf['from'];
                    unset ($mailconf['from']);
                    $this->load->library('email', $mailconf);
                    $this->email->set_newline("\r\n");
                    $this->email->from($from, 'Aquastrong.com');
                    $this->email->to($admin_mail);
                    $this->email->subject('На сайте aquastrong.com был сделан заказ');
                    $this->email->message('Здравствуйте, на сайте сделан заказ. Для просмотра перейдите по ссылке:{unwrap}http://aqustrong.net/admin/ord/view/'.$id.'{/unwrap}

'.$mess.'');
                    @$this->email->send();
//                    show_error($this->email->print_debugger());
                    //Посылаем почтовое сообщение
                    $this->session->sess_destroy(); // Уничтожение сессии
                    $this->load->view('client/main', $data);//загружаем вьювер
                }
                else {
                    redirect('/product');
                }
            }
            else {
                redirect('/cart/add');
            }
        }
    }
    
    function list_product() {
        $cart = $this->session->userdata('cart');
        if (!empty($cart)) {
            echo json_encode($cart);
        }
        else {
            $cart = array('status' => 'not faund');
            echo json_encode($cart);
        }
    }
    
    function remove() {
        $method = $this->input->server('REQUEST_METHOD');
        if ($method == 'POST') {
            $id = $this->input->post('id');
            if (!empty($id)) {
                $cart = $this->session->userdata('cart');
                if (!empty($cart)) {
                    foreach ($cart as $key=>$value) {
                        if ($value['id'] == $id) {
                            unset($cart[$key]);
                            $this->session->set_userdata('cart', $cart);
                            echo 'done';
                            break;
                        }
                    }
                }
                else {
                    echo 'error';
                }
            }
        }
        else {
            redirect('cart/buy');
        }
    }
    
    function _remap($method) {
        $params = null;
        if ($this->uri->segment(5)) {
            $params = $this->uri->segment(5);
        }
        check_parther();
        $this->$method($params);
    }
}

?>

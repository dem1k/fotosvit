<?php

class Staticreviews extends Controller {
    
    function Staticreviews() {
        parent::Controller();
    }
    
    function index() {
        $data = array();
        /////Продукты сайтбар
        if ($came_from = $this->session->userdata('error')) { // Ошибка аутентификации
            $data['came_from'] = $came_from;
            $this->session->unset_userdata('error');
        }
        $cart = $this->session->userdata('cart');
        $data['count_cart'] = count($cart);
        $this->load->model('admin/product_model', 'prod', true); // Для трех продуктов которые
        $data['sidebarproduct'] = $this->prod->get_three();//находятся всегда сбоку
        $data['sidebarproductimg'] = $this->prod->get_all_img();//Так же достаем картинки
        if ($authorization = $this->session->userdata('authorization')) {
            $data['auth'] = $authorization;
        }
        //Валюта, Цена
        $this->load->model('admin/currency_model', 'currency', true); // Для трех продуктов которые
        if ($exchange = $this->session->userdata('exchange')) {
            $data['exchange'] = $exchange;
        }
        else {
            $currency_now = $this->currency->getFirst();
            if (!empty($currency_now)) {
                $data['exchange'] = $currency_now[0]['id'];
            } else {
                $data['exchange'] = NULL;
            }

        }
        $product_price = $this->prod->GetPrice();
        $currency = $this->currency->get_all();
        foreach ($product_price as $key => $value) { //Создаем массив, где по айди продукта можно будет получить его цену
            if ($value['currency_id'] == $data['exchange']) {
                $key_product_price['price'][$value['product_id']] = $value['price'];
                $key_product_price['price_for_partner'][$value['product_id']] = $value['price_for_partner'];
            }
        }
        $data['price'] = $key_product_price;
        $data['currency'] = $currency;
        foreach ($currency as $key => $value) {
            $key_currency[$value['id']] = $value['name'];
        }
        $data['key_currency'] = $key_currency;
        //Цены
        /////Продукты, сайтбар
        $data['template'] = 'client/staticreviews/view.php';
        $data['sidebar_product'] = 'true';
        $this->load->model('admin/reviews_model', 'reviews', true);
        $data['title'] = 'Отзывы';
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Имя', 'trim|required|min_length[2]|max_length[400]|xss_clean');
        $this->form_validation->set_rules('mail', 'Mail', 'trim|required|valid_email|xss_clean');
        $this->form_validation->set_rules('description', 'Отзыв', 'trim|required|min_length[2]|xss_clean');
        if ($message = $this->session->userdata('message')) {
            $data['message'] = $message;
            $this->session->unset_userdata('message');
        }
        if ($this->input->post('action', '') == 'save') {
            if ($this->form_validation->run() == FALSE) {
                $data['error'] = ' ';
                $this->load->view('client/main', $data);//загружаем вьювер
            }
            else {
                $result = array(
                    'name'=>$this->input->post('name'),
                    'description'=>$this->input->post('description'),
                    'mail'=>$this->input->post('mail'),
                    'status'=>'0',
                    'date_create'=>date("Y-m-d"),
                    'type'=>'text'
                );
                $save = $this->reviews->save($result);
                $this->session->set_userdata('message', 'Отзыв успешно добавлен и будет помещен на сайте после модерации');
                redirect('/staticreviews');
            }
        }
        else {
            $this->load->view('client/main', $data);//загружаем вьювер
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
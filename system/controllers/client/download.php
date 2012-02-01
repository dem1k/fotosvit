<?php

class Download extends Controller {
    
    function Download() {
        parent::Controller();
    }
    
    function index() {
        $alias = $this->uri->segment(3, '0');
        $data = array();
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
        $data['template'] = 'client/download/view';
        $data['sidebar_product'] = 'true';
        $this->load->model('admin/download_model', 'download', true);
        $query = $this->download->get_all();
        $data['title'] = 'Файлы';
        if (empty($query)) {
            redirect('/');
        }
        $data['download'] = $query;
        $this->load->view('/client/main', $data);
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

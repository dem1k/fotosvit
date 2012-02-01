<?php

class Articles extends Controller {
    
    function Articles() {
        parent::Controller();
    }
    
    function _remap() {
        $viewer = $this->uri->segment(2, '0');
        if ($viewer != 'view') {
            //Начало постраничной навигации
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
            $this->load->model('admin/news_model', 'news', true);// Подключаем модель
            $this->load->library('pagination'); // Загружаем библиотеку
            $base_url = base_url();
            $one_page_list = '5';
            $config['base_url'] = $base_url . 'articles/start/'; // Урл
            $config['total_rows'] = $this->news->count_news(); // Всего записей
            $config['per_page'] = $one_page_list; // На одной страницe
            $config['num_links'] = '2';    // количество ссылок в пейджере
            $config['first_link'] = 'В начало';
            $config['next_link'] = '>> ';
            $config['prev_link'] = '<< ';
            $config['last_link'] = 'В конец';
            $this->pagination->initialize($config);
            $data['pager'] = $this->pagination->create_links();
            //Конец постраничной навигации
            $data['template'] = 'client/articles/view';
            $data['sidebar_product'] = 'true';
            $page = intval($this->uri->segment(3)); //Узнаем с какой странички выводить
            $query = $this->news->get_all_pager($page, $one_page_list);
            $data['news'] = $query;
            $data['title'] = 'Статьи о воде';
            $this->load->view('/client/main', $data);
        }
        else {
            $this->view();
        }
    }
    
    function view() {
        $id = $this->uri->segment(3, '0');
        if ($id == '0') {
            redirect('news');
        }
        else {
            $data = array();
            /////Продукты сайтбар
            if ($cart = $this->session->userdata('cart')) {
                $data['count_cart'] = count($cart);
            }
            else {
                $data['count_cart'] = 0;
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
            $data['template'] = 'client/articles/display';
            $data['sidebar_product'] = 'true';
            $this->load->model('admin/news_model', 'news', true);
            $query = $this->news->get_one($id);
            $data['title'] = $query[0]->title;
            $data['news'] = $query;
            $this->load->view('/client/main', $data);
        }
    }
}

?>

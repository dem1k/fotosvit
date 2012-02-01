<?php

class Product extends Controller {

    function Product() {
        parent::Controller();
    }

    function index() {
        $this->load->model('admin/product_model', 'product', true); // Для трех продуктов которые
        $id = $this->product->get_first_id();
        if (!empty($id)) {
            if ($id[0]->id != '0') {
                /////Продукты сайтбар
                if ($cart = $this->session->userdata('cart')) {
                    $data['count_cart'] = count($cart);
                } else {
                    $data['count_cart'] = 0;
                }
                $data['sidebarproduct'] = $this->product->get_three(); //находятся всегда сбоку
                $data['sidebarproductimg'] = $this->product->get_all_img(); //Так же достаем картинки
                if ($authorization = $this->session->userdata('authorization')) {
                    $data['auth'] = $authorization;
                }
                //Валюта, Цена
                $this->load->model('admin/currency_model', 'currency', true); // Для трех продуктов которые
                if ($exchange = $this->session->userdata('exchange')) {
                    $data['exchange'] = $exchange;
                } else {
                    $currency_now = $this->currency->getFirst();
                    if (!empty($currency_now)) {
                        $data['exchange'] = $currency_now[0]['id'];
                    } else {
                        $data['exchange'] = NULL;
                    }
                }
                $product_price = $this->product->GetPrice();
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
                $data['template'] = 'client/product/list';
                $data['sidebar_product'] = 'true';
                if ($came_from = $this->session->userdata('error')) { // Ошибка аутентификации
                    $data['came_from'] = $came_from;
                    $this->session->unset_userdata('error');
                }
                $query = $this->product->get_all();
                $imgs = $this->product->get_all_img();
                if (!empty($query)) {
                    $data['title'] = "Список продукции";
                    $data['product'] = $query;
                    $data['imgs'] = $imgs;
                    $this->load->view('/client/main', $data);
                } else {
                    redirect('pages');
                }
            } else {
                redirect('pages');
            }
        } else {
            redirect('pages');
        }
    }

    function view() {
        $id = $this->uri->segment(3, '0');
        if ($id != '0') {
            /////Продукты сайтбар
            if ($cart = $this->session->userdata('cart')) {
                $data['count_cart'] = count($cart);
            } else {
                $data['count_cart'] = 0;
            }
            $this->load->model('admin/product_model', 'product', true); // Для трех продуктов которые
            $data['sidebarproduct'] = $this->product->get_three(); //находятся всегда сбоку
            $data['sidebarproductimg'] = $this->product->get_all_img(); //Так же достаем картинки
            if ($came_from = $this->session->userdata('error')) { // Ошибка аутентификации
                $data['came_from'] = $came_from;
                $this->session->unset_userdata('error');
            }
            if ($authorization = $this->session->userdata('authorization')) {
                $data['auth'] = $authorization;
            }
            //Валюта, Цена
            $this->load->model('admin/currency_model', 'currency', true); // Для трех продуктов которые
            if ($exchange = $this->session->userdata('exchange')) {
                $data['exchange'] = $exchange;
            } else {
                $currency_now = $this->currency->getFirst();
                if (!empty($currency_now)) {
                    $data['exchange'] = $currency_now[0]['id'];
                } else {
                    $data['exchange'] = NULL;
                }
            }
            $product_price = $this->product->GetPrice();
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
            $data['template'] = 'client/product/display';
            $data['sidebar_product'] = 'true';
            $query = $this->product->get_one($id);
            if (!empty($query)) {
                $data['title'] = $query[0]->name;
                $imgs = $this->product->get_img($id);
                $data['product'] = $query;
                $data['imgs'] = $imgs;
                $this->load->view('/client/main', $data);
            } else {
                redirect('product');
            }
        } else {
            redirect('product');
        }
    }

    function display() {
        $id = $this->uri->segment(3, '');
        if ($came_from = $this->session->userdata('error')) { // Ошибка аутентификации
            $data['came_from'] = $came_from;
            $this->session->unset_userdata('error');
        }
        if (!empty($id)) {
            $data = array();                            // Создается новый пустой массив
            $this->load->model('admin/product_model', 'product', true); //загружаем модель
            $data['product'] = $this->product->get_one($id); //получаем список статичных страниц в массиве
            $this->load->view('/client/product/long_text', $data);
        } else {
            redirect('/product');
        }
    }

    function exchange() {
        $method = $this->input->server('REQUEST_METHOD');
        $add = 'true';
        if ($came_from = $this->session->userdata('error')) { // Ошибка аутентификации
            $data['came_from'] = $came_from;
            $this->session->unset_userdata('error');
        }
        if ($method == 'POST') {
            $exchange = $this->input->post('exchange');
            if (!empty($exchange)) {
                $exchange = (integer) $exchange;
                if ($exchange != 0) {
                    $result = array('status' => 'ok');
                    $this->session->set_userdata('exchange', $exchange);
                    //Поменять во всей сессии валюту на только что выбранную
                    $cart = $this->session->userdata('cart'); // Cуществующий массив корзины
                    $this->load->model('admin/currency_model', 'currency', true);
                    $this->load->model('admin/product_model', 'product', true);
                    $currency = $this->currency->getById($exchange);
                    $new_cart = array();
                    if ($cart) {
                        foreach ($cart as $key => $value) {
                            $product_price = $this->product->GetPriceByIdAndCurrency($exchange, $value['id']);
                            if (!$authorization = $this->session->userdata('authorization')) {
                                $price = $product_price[0]['price'];
                            } else {
                                $price = $product_price[0]['price_for_partner'];
                            }
                            $result = array(
                                'id' => $value['id'],
                                'price' => $price,
                                'exchange' => $currency[0]['name'],
                                'name' => $value['name'],
                                'status' => $value['status'],
                            );
                            $new_cart[] = $result;
                        }
                    }
                    $this->session->set_userdata('cart', $new_cart);
                    //Поменяли во всей сессии валюту на только что выбранную
                    echo json_encode($result);
                } else {
                    $result = array(
                        'status' => 'error',
                    );
                    echo json_encode($result);
                }
            } else {
                $result = array(
                    'status' => 'alredy exist',
                );
                echo json_encode($result);
            }
        } else {
            $result = array(
                'status' => 'error',
            );
            echo json_encode($result);
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

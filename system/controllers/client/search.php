<?php

class Search extends Controller {
    
    function Search() {
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
        $data['template'] = 'client/search/view';
        $data['sidebar_product'] = 'true';
        $data['title'] = 'Поиск по сайту';
        //Непосредственно поиск
        //Массив в котором все поисковые опции
        //по базам
        $data_table = array(
            'aquastrong'=>true,
            'aquastrong_pages' => true,
            'aquastrong_articles'=>true,
        );
        $this->load->model('admin/product_model', 'prod', true); //Загружаем модель по продуктам
        $this->load->model('admin/news_model', 'articles', true);  //Модель статичные странички
        $this->load->model('admin/stat_model', 'stat', true);   //Статичные странички
        $this->load->library('SphinxClient');
        $query = $this->input->post('query');
        $sphinx = new SphinxClient();
        $i = 0;
        $all_result = array();
        foreach ($data_table as $key=>$value) {
            // Подсоединяемся к Sphinx-серверу
            $sphinx->SetServer('localhost', 3312);
            // Совпадение по любому слову
            $sphinx->SetMatchMode(SPH_MATCH_ANY);
            // Результаты сортировать по релевантности
            $sphinx->SetSortMode(SPH_SORT_RELEVANCE);
            // Задаем полям веса (для подсчета релевантности)
            $sphinx->SetFieldWeights(array ('name' => 20));
            // Результат по запросу ($query - запрос, $key - индекс)
            $result = $sphinx->Query($query, $key);
            if (isset($result['matches'])) {
                foreach ($result['matches'] as $id => $row) {
                    switch ($key) // переключающее выражение
                    {
                        case 'aquastrong': // Выбираем продукты по айди
                            $from_data = $this->prod->get_one($id);
                            $name_table = 'product';
                            break;
                        case 'aquastrong_pages': // Выбираем статичные странички по айди
                            $from_data = $this->stat->getByID($id);
                            $name_table = 'static';
                            break;
                        case 'aquastrong_articles': // Выбираем смотрим на работу по айди
                            $from_data = $this->articles->get_one($id);
                            $name_table = 'articles';
                            break;
                        default:
                            return false;
                    }
                    //Заносим в массив
                    $all_result[$i]['data'] = $from_data;
                    $all_result[$i]['title'] = $name_table;
                    $i++;
                }
            }
        }
        //Поиск закончился
        $data['query'] = $query;
        $data['all_result'] = $all_result;
        $this->load->view('client/main', $data);
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

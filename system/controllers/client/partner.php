<?php

class Partner extends Controller {

    function Partner() {
        parent::Controller();
    }

    function _remap() {
        $method = $this->input->server('REQUEST_METHOD');
        header("Content-Type: text/html; charset=UTF-8;Accept: text/html;");
        if ($method == 'GET') {
            $id = $this->uri->segment(2, '0');
            $url = '/product';
        } else {
            $id = $this->input->post('id');
            $url = $this->input->server('HTTP_REFERER');
        }

        if (!empty($id)) {
            $rr = $this->_check_vip($id);
            if ($rr) {
                $this->session->set_userdata('ref', $id);
                $this->session->set_userdata('authorization', 'ok');
                //Поменять во всей сессии валюту на только что выбранную
                $cart = $this->session->userdata('cart'); // Cуществующий массив корзины
                $this->load->model('admin/currency_model', 'currency', true);
                $this->load->model('admin/product_model', 'product', true);
                foreach ($cart as $key => $value) {
                    $exchange = $this->currency->getByName($value['exchange']);
                    $product_price = $this->product->GetPriceByIdAndCurrency($exchange[0]['id'], $value['id']);
                    if (!$authorization = $this->session->userdata('authorization')) {
                        $price = $product_price[0]['price'];
                    } else {
                        $price = $product_price[0]['price_for_partner'];
                    }
                    $result = array(
                        'id' => $value['id'],
                        'price' => $price,
                        'exchange' => $value['exchange'],
                        'name' => $value['name'],
                        'status' => $value['status'],
                    );
                    $new_cart[] = $result;
                }
                $this->session->set_userdata('cart', $new_cart);
                //Поменяли во всей сессии валюту на только что выбранную
            } else {
                $this->session->set_userdata('error', 'ok');
            }
        }
        header("Content-Type: text/html; charset=UTF-8;Accept: text/html;");
        redirect($url);
    }

    function _check_vip($id) {
        $maindomain = $this->config->item('domains');
        $url_service = $maindomain['service'] . "/user/" . $id . "/status";
        $this->load->library('curl');
        $this->curl->open();
        $content = $this->curl->http_get($url_service, $headers = array('Accept : application/json'));
        $headers = $this->curl->get_http_code();
        $this->curl->close();
        //Заканчиваю проверять
        $content = json_decode($content);
        if ($headers == '200') {
            return true;
//            if ($content->isPartner || $content->isVip) {
//                return true;
//            } else {
//                return false;
//            }
        } else {
            return false;
        }
    }

}

?>

<?php
function check_parther() {
    $CI = & get_instance();
    if (!$CI->session->userdata('authorization')) {
        $url = $_SERVER['HTTP_HOST'];
        $result = explode(".", $url);
        $id = $result[0];
        $id_two = $result[1];
        $id_two = (integer) $id_two;
        $id = (integer) $id;
        if ($id_two >= $id) {
            $id = $id_two;
        }
        if ($id != 0) {
            $maindomain = $CI->config->item('domains');
            $url_service = $maindomain['service'] . "/user/" . $id . "/status";
            $CI->load->library('curl');
            $CI->curl->open();
            $content = $CI->curl->http_get($url_service, $headers = array('Accept : application/json'));
            $headers = $CI->curl->get_http_code();
            $CI->curl->close();
            //
            $content = json_decode($content);
            //var_dump($content);
            if ($headers == '200') {
//                if ($content->isPartner || $content->isVip) {
                    $CI->session->set_userdata('ref', $id);
                    $CI->session->set_userdata('authorization', 'ok');
//                } else {
//                     show_error('Такого сайта не существует. Пожалуйста перейдите на главную страницу.', 500);
//                }
            } else {
                show_error('Сервис временно не работает. Попробуйте обратиться позже.', 500);
            }
        }
    }
}

?>

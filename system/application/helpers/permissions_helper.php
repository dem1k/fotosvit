<?php

function check_perms() {
    $CI = & get_instance();
    $controller_name = $CI->uri->rsegment(1);
    // получаем имя текущего метода
    $method_name = $CI->uri->rsegment(2);
    //Получаем айди юзверя
    $user_id = $CI->session->userdata('user_id');
    //Получаем тип юзверя
    $user_type = $CI->session->userdata('user_type');
    if ($user_type == 'admin') {
        return true;
        break;
    }
    else {
        //Получаю список прав для этого пользователя
        $CI->db->select('*')
                ->from('permission')
                ->where('users_id', $user_id);
        $query = $CI->db->get();
        $result = $query->result_array();
        //Создаю переменную, 'куда хочет попасть пользователь'
        $permission = $controller_name . '/' . $method_name;
        //создаю новый массив, чтобы потом по ключу определить
        //можно ли пользователю заходить на страничку
        if ($result) {
            foreach ($result as $key=>$value) {
                $new_permissions[$value['permissions']] = 'true';
            }
        }
        else {
            $new_permissions['array'] = 'array';
        }
        //Проверяем, если ключ есть, то разрешено, нету ключу запрещено
        if (!array_key_exists($permission, $new_permissions)) {
            show_error('У вас нет прав на доступ к данной операции');
        }
    }
}

?>

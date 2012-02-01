<?php

class Product extends Controller {
    
    function Product() {
        parent::Controller();
    }
    
    function index() {
        $data = array();                            // Создается новый пустой массив
        $data['template'] = 'admin/product/view';//Подключается шаблон
        $data['res'] = $this->router->fetch_class();//Узнаем какой контроллер задействован и передаем его в шаблон
        $this->load->model('admin/product_model', 'product', true);//загружаем модель
        $product = $this->product->get_all();//получаем список статичных страниц в массиве
        $data['product'] = $product;//передаем во вьювер

        if ($message = $this->session->userdata('message')) {
            $data['message'] = $message;
            $this->session->unset_userdata('message');
        }
        $this->load->view('/admin/main', $data);
    }
    
    function create() {
        $data = array();                            // Создается новый пустой массив
        $data['template'] = 'admin/product/create';//Подключается шаблон
        $data['res'] = $this->router->fetch_class();//Узнаем какой контроллер задействован и передаем его в шаблон
        $this->load->model('admin/product_model', 'product', true);//загружаем модель продуктов
        $this->load->model('admin/currency_model', 'currency', true); //загружаем валюты
        $currency = $this->currency->get_all(); //Массив из валют
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Название', 'trim|required|min_length[2]|max_length[400]|xss_clean');
        $this->form_validation->set_rules('description', 'Описание', 'trim|required|min_length[2]|xss_clean');
        $this->form_validation->set_rules('sku', 'Артикуль', 'trim|required|min_length[1]|max_length[10]');
        $this->form_validation->set_rules('text', 'Большое описание', 'trim|required|min_length[1]');
        $this->form_validation->set_rules('long_text', 'Огромное описание', 'trim|xss_clean');
        $this->form_validation->set_rules('sort', 'Сортировка', 'trim|required|min_length[1]');
        $this->form_validation->set_rules('id_in_price', 'ID в прайсе', 'trim|xss_clean');
        //Валидация валют
        foreach ($currency as $key => $value) {
            $this->form_validation->set_rules('currency' . $value['id'], $value['name'], 'trim|min_length[1]|max_length[10]|xss_clean');
            $this->form_validation->set_rules('currency_for_partner' . $value['id'], $value['name'], 'trim|min_length[1]|max_length[10]|xss_clean');
        }
        $data['currency'] = $currency;
        //Валидация валют
        $config['upload_path'] = './uploads/product/mini_images';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size']	= '1000';
        $config['max_width'] = '1024';
        $config['max_height'] = '1024';

        $this->load->library('upload', $config);

        if ($this->input->post('action', '') == 'save') {
            if ($this->form_validation->run() == FALSE) {
                $filename = $this->input->post('file_mini');
                if (empty($filename)) {
                    if (!$this->upload->do_upload()) {
                        $data['error'] = $this->upload->display_errors();
                    }
                    else {
                        $mini_file_setting = $this->upload->data();
                        $data['mini_file_setting'] = $mini_file_setting;
                    }
                }
                else {
                    $mini_file_setting['file_name'] = $filename;
                    $data['mini_file_setting'] = $mini_file_setting;
                }
                $this->load->view('admin/main', $data);//загружаем вьювер
            }
            else {

                $filename = $this->input->post('file_mini');
                //Если имя файла пустое, тогда загружаем файл
                if (empty($filename)) {
                    if (!$this->upload->do_upload()) {
                        $data['error'] = $this->upload->display_errors();
                        $this->load->view('admin/main', $data);
                    }
                }
                $mini_file_setting = $this->upload->data();
                if (empty($mini_file_setting["file_name"])) {
                    $mini_file_setting['file_name'] = $filename;
                }
                $files = $this->input->post('files');
                $path = $this->input->post('path');
                $result = array(
                    'name'=>$this->input->post('name'),
                    'description'=>$this->input->post('description'),
                    'text'=>$this->input->post('text'),
                    'sku'=>$this->input->post('sku'),
                    'long_text'=>$this->input->post('long_text'),
                    'sort'=>$this->input->post('sort'),
                    'thumb'=>$mini_file_setting['file_name'],
                    'id_in_price'=>$this->input->post('id_in_price'),

                );

                $product_id = $this->product->save($result);
                $this->session->set_userdata('message', 'Продукт успешно добавлен');
                //Работа с картинками
                $this->load->library('image_lib');
                if (!empty($files)) {
                    $countfiles = count($files);
                    $countfiles = $countfiles - 1;
                    for ($i = 0; $i <= $countfiles; $i++) {
                        $result = array(
                            'product_id' => $product_id,
                            'name' => $files[$i],
                            'path' => $path[$i],
                        );
                        $this->product->insert_file($result);
                        list($width, $height, $type, $attr) = getimagesize("./uploads/product/" . $path[$i] . "");
                        if ($width > 800) {
                            $width = 800;
                        }
                        if ($height > 800) {
                            $height = 800;
                        }
                        //Ресайз картинок
                        $config['image_library'] = 'gd2'; // выбираем библиотеку
                        $config['source_image'] = './uploads/product/' . $path[$i]; // Путь к картинки ресайза
                        $config['maintain_ratio'] = TRUE; // сохранять пропорции
                        $config['width'] = $width; // и задаем размеры
                        $config['height'] = $height;
                        $this->image_lib->initialize($config);
                        $this->image_lib->resize(); // и вызываем функцию
                        //Ресайз картинок
                        $result = '';
                    }
                }
                //Работа с картинками
                //Начало цикла по внесению валюты в БД
                foreach ($currency as $key => $value) {
                    $price = preg_replace('/,/', '.', $this->input->post('currency' . $value['id']));
                    $price_for_partner = preg_replace('/,/', '.', $this->input->post('currency_for_partner' . $value['id']));
                    $currency_id = $value['id'];
                    if (empty($price)) {
                        $price = 0;
                    }
                    else {
                        $price = (double) $price;
                    }
                    if (empty($price_for_partner)) {
                        $price_for_partner = 0;
                    }
                    else {
                        $price_for_partner = (double) $price_for_partner;
                    }
                    $result = array(
                        'product_id'=> $product_id,
                        'currency_id'=> $currency_id,
                        'price'=> $price,
                        'price_for_partner'=>$price_for_partner,
                    );
                    $this->currency->SavePrice($result);
                }
                //Конец цикла по внесению валюты в БД
                redirect('admin/product');
            }
        }
        else {
            $this->load->view('admin/main', $data);//загружаем вьювер
        }
    }
    
    function edit() {
        $data = array();                            // Создается новый пустой массив
        $data['template'] = 'admin/product/edit';//Подключается шаблон
        $data['res'] = $this->router->fetch_class();//Узнаем какой контроллер задействован и передаем его в шаблон
        $this->load->model('admin/product_model', 'product', true);//загружаем модель
        $this->load->model('admin/currency_model', 'currency', true); //загружаем валюты
        $currency = $this->currency->get_all(); //Массив из валют
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Название', 'trim|required|min_length[2]|max_length[400]|xss_clean');
        $this->form_validation->set_rules('description', 'Описание', 'trim|required|min_length[2]');
        $this->form_validation->set_rules('sku', 'Артикуль', 'trim|required|min_length[1]|max_length[10]|xss_clean');
        $this->form_validation->set_rules('text', 'Большое описание', 'trim|required|min_length[1]');
        $this->form_validation->set_rules('long_text', 'Огромное описание', 'trim');
        $this->form_validation->set_rules('id_in_price', 'ID в прайсе', 'trim|xss_clean');
        $id = $this->uri->segment(4, '');
        $data['id'] = $id;
        //Валидация валют
        foreach ($currency as $key => $value) {
            $this->form_validation->set_rules('currency' . $value['id'], $value['name'], 'trim|min_length[1]|max_length[10]|xss_clean');
            $this->form_validation->set_rules('currency_for_partner' . $value['id'], $value['name'], 'trim|min_length[1]|max_length[10]|xss_clean');
        }
        $data['currency'] = $currency;
        //Валидация валют
        $config['upload_path'] = './uploads/product/mini_images';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size']	= '10000';
        $config['max_width'] = '1024';
        $config['max_height'] = '768';
        $this->load->library('upload', $config);
        if (!empty($id)) {
            $product = $this->product->get_one($id);//Получаем продукт по айди
            $product_price = $this->product->getPriceById($id); //Получаем цены на продукт
            foreach ($product_price as $key => $value) {
                $new_product_price['price'][$value['currency_id']] = $value['price'];
                $new_product_price['price_for_partner'][$value['currency_id']] = $value['price_for_partner'];
            }
            if (isset($new_product_price)) {
                $data['price'] = $new_product_price;
            }
            if (!empty($product)) {
                $data['product'] = $product;
                $img = $this->product->get_img($product[0]->id);
                $data['img'] = $img;
                if ($this->input->post('action', '') == 'save') {
                    if ($this->form_validation->run() == FALSE) {
                        $data['error'] = ' ';
                        $this->load->view('admin/main', $data);//загружаем вьювер
                    }
                    else {

                        if (!$this->upload->do_upload('userfile')) {
                            $thumb = $product[0]->thumb;
                        }
                        else {
                            $mini_file_setting = $this->upload->data();
                            $thumb = $mini_file_setting['file_name'];
                            if (file_exists('./uploads/product/mini_images/' . $product[0]->thumb)) {
                                unlink('./uploads/product/mini_images/' . $product[0]->thumb);
                            }
                        }
                        $this->product->delete_img($product[0]->id); //Удалить все картинки связанные с продуктом
                        $files = $this->input->post('files');
                        $path = $this->input->post('path');
                        $result = array(
                            'name'=>$this->input->post('name'),
                            'description'=>$this->input->post('description'),
                            'text'=>$this->input->post('text'),
                            'sku'=>$this->input->post('sku'),
                            'id'=>$id,
                            'long_text'=>$this->input->post('long_text'),
                            'sort'=>$this->input->post('sort'),
                            'thumb'=> $thumb,
                            'id_in_price'=>$this->input->post('id_in_price'),
                        );
                        $update = $this->product->update($result);
                        $this->session->set_userdata('message', 'Продукт успешно отредактирован');
                        if (!empty($files)) {
                            $countfiles = count($files);
                            $countfiles = $countfiles - 1;
                            $this->load->library('image_lib');
                            for ($i = 0; $i <= $countfiles; $i++) {
                                $result = array(
                                    'product_id' => $id,
                                    'name' => $files[$i],
                                    'path' => $path[$i],
                                );
                                $this->product->insert_file($result);
                                list($width, $height, $type, $attr) = getimagesize("./uploads/product/" . $path[$i] . "");
                                if ($width > 800) {
                                    $width = 800;
                                }
                                if ($height > 800) {
                                    $height = 800;
                                }
//                            Ресайз картинок
                                $config['image_library'] = 'gd2'; // выбираем библиотеку
                                $config['source_image'] = './uploads/product/' . $path[$i]; // Путь к картинки ресайза
                                $config['maintain_ratio'] = TRUE; // сохранять пропорции
                                $config['width'] = $width; // и задаем размеры
                                $config['height'] = $height;
                                $this->image_lib->initialize($config);
                                $this->image_lib->resize(); // и вызываем функцию
//                            Ресайз картинок
                                $result = '';
                            }
                        }
                        //Начало цикла по внесению валюты в БД
                        $this->product->DeletePrice($id); // Удаление цен по айди продукта
                        foreach ($currency as $key => $value) {
                            $price = preg_replace('/,/', '.', $this->input->post('currency' . $value['id']));
                            $price_for_partner = preg_replace('/,/', '.', $this->input->post('currency_for_partner' . $value['id']));
                            $currency_id = $value['id'];
                            if (empty($price)) {
                                $price = 0;
                            }
                            else {
                                $price = (double) $price;
                            }
                            if (empty($price_for_partner)) {
                                $price_for_partner = 0;
                            }
                            else {
                                $price_for_partner = (double) $price_for_partner;
                            }
                            $result = array(
                                'product_id'=> $id,
                                'currency_id'=> $currency_id,
                                'price'=> $price,
                                'price_for_partner'=>$price_for_partner,
                            );
                            $this->currency->SavePrice($result);
                        }
                        //Конец цикла по внесению валюты в БД
                        redirect('admin/product');
                    }
                }
                else {
                    $this->load->view('admin/main', $data);//загружаем вьювер
                }
            }
            else {
                redirect('admin/product');
            }
        }
        else {
            redirect('admin/product');
        }
    }
    
    function view() {
        $id = $this->uri->segment(4, '');
        if (!empty($id)) {
            $data = array();                            // Создается новый пустой массив
            $data['template'] = 'admin/product/display';//Подключается шаблон
            $data['res'] = $this->router->fetch_class();//Узнаем какой контроллер задействован и передаем его в шаблон
            $this->load->model('admin/product_model', 'product', true);//загружаем модель
            $this->load->model('admin/currency_model', 'currency', true);//загружаем модель
            $data['product_price'] = $this->product->getPriceById($id);
            $currency = $this->currency->get_all();
            foreach ($currency as $key => $value) {
                $new_currency[$value['id']] = $value['name']; //Ключ сокращение имени валюты
            }
            $data['currency'] = $new_currency;
            $product = $this->product->get_one($id);//получаем список статичных страниц в массиве
            if (!empty($product[0])) {
                $img = $this->product->get_img($id);
                $data['product'] = $product;//передаем во вьювер
                $data['img'] = $img;
                $this->load->view('/admin/main', $data);
            }
            else {
                redirect('admin/product');
            }
        }
        else {
            redirect('/admin/product');
        }
    }
    
    function delete() {
        $id = $this->uri->segment(4, '');
        if (!empty($id)) {
            $this->load->model('admin/product_model', 'product', true);//загружаем модель
            $product_no = $this->product->get_one($id);
            $img_file = $this->product->get_img($id);
            $product = $this->product->delete_product($id);
            $img = $this->product->delete_img($id);
            foreach ($img_file as $item) {
                if (file_exists('./uploads/product/' . $item->path)) {
                    unlink('./uploads/product/' . $item->path);
                    if (file_exists('./uploads/product/thumb_' . $item->path)) {
                        unlink('./uploads/product/thumb_' . $item->path);
                    }
                }
            }
            if (file_exists('./uploads/product/mini_images/' . $product_no[0]->thumb)) {
                unlink('./uploads/product/mini_images/' . $product_no[0]->thumb);
            }
            if ($product) {
                $this->session->set_userdata('message', 'Продукт успешно удален');
            } else {
                $this->session->set_userdata('message', 'Ошибка. Продукт не удален');
            }
            $this->product->DeletePrice($id);
            redirect('/admin/product');
        }
        else {
            redirect('/admin/product');
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

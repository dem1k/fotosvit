<?php

class Product extends Controller {

    function Product() {
        parent::Controller();
        $this->load->model('admin/product_model', '', true);
        $this->load->model('admin/category_model', '', true);
        $this->load->model('admin/manufacturers_model', '', true);
    }

    function index() {
        $data['template'] = 'admin/product/view';
        $data['res'] = $this->router->fetch_class();

        $product = $this->product_model->getAll();
        $data['product'] = $product;

        if ($message = $this->session->userdata('message')) {
            $data['message'] = $message;
            $this->session->unset_userdata('message');
        }
        $this->load->view('/admin/main', $data);
    }

    function create() {
        $data = array();
        $data['template'] = 'admin/product/create';
        $data['res'] = $this->router->fetch_class();
        $data['categories'] = $this->category_model->getAll();
        $data['manufacturers'] = $this->manufacturers_model->getAll();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('article', 'Артикул', 'trim|required|min_length[1]|max_length[255]|xss_clean');
        $this->form_validation->set_rules('name', 'Название', 'trim|required|min_length[1]|max_length[255]|xss_clean');
        $this->form_validation->set_rules('description', 'Описание', 'trim|required|min_length[1]|xss_clean');
        $this->form_validation->set_rules('manufacturer', 'Производитель', 'trim|required|min_length[1]|xss_clean');
        $this->form_validation->set_rules('category', 'Категория', 'trim|required|min_length[1]|xss_clean');
        $this->form_validation->set_rules('price_uah', 'Описание', 'trim|required|min_length[1]|xss_clean');
        $this->form_validation->set_rules('price_usd', 'Описание', 'trim|required|min_length[1]|xss_clean');
        $this->form_validation->set_rules('sort', 'Сортировка', 'trim|required|min_length[1]');

        if ($this->input->post('action', '') == 'save') {
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('admin/main', $data);
            }
            else {

                $result = array(
                        'article'=>set_value('article'),
                        'name'=>$this->input->post('name'),
                        'slug'=>$this->generateSlug($this->input->post('name')),
                        'description'=>$this->input->post('description'),
                        'sort'=>$this->input->post('sort'),
                        'price_uah'=>set_value('manufacturer'),
                        'price_uah'=>set_value('category'),
                        'price_uah'=>set_value('price_uah'),
                        'price_usd'=>set_value('price_usd'),
                        'image_big'=>$this->input->post('image_big'),
                        'image_small'=>$this->input->post('image_small'),

                );

                $product_id = $this->product_model->save($result);
                $this->session->set_userdata('message', 'Продукт успешно добавлен');
                redirect('admin/product');
            }
        }
        else {
            $this->load->view('admin/main', $data);
        }
    }

    function edit() {
        if($id = $this->uri->segment(4)) {
            $data['template'] = 'admin/product/edit';
            $data['res'] = $this->router->fetch_class();
            $data['id']=$id;
            $data['categories'] = $this->category_model->getAll();
            $data['manufacturers'] = $this->manufacturers_model->getAll();
            $this->load->library('form_validation');
            $this->form_validation->set_rules('article', 'Артикул', 'trim|required|min_length[1]|max_length[255]|xss_clean');
            $this->form_validation->set_rules('name', 'Название', 'trim|required|min_length[1]|max_length[255]|xss_clean');
            $this->form_validation->set_rules('description', 'Описание', 'trim|xss_clean');
            $this->form_validation->set_rules('manufacturer', 'Производитель', 'trim|required|min_length[1]|xss_clean');
            $this->form_validation->set_rules('category', 'Категория', 'trim|required|min_length[1]|xss_clean');
            $this->form_validation->set_rules('price_uah', 'Цена грн.', 'trim|required|min_length[1]|xss_clean');
            $this->form_validation->set_rules('price_usd', 'Цена долл.', 'trim|required|min_length[1]|xss_clean');
            $this->form_validation->set_rules('sort', 'Сортировка', 'trim|required|min_length[1]');
            $data['object']=$this->product_model->getById($id);
            if ($this->input->post('action', '') == 'save') {
                if ($this->form_validation->run() == FALSE) {
                    $this->load->view('admin/main', $data);
                }
                else {

                    $result = array(
                            'id'=>$id,
                            'article'=>set_value('article'),
                            'name'=>$this->input->post('name'),
                            'slug'=>$this->generateSlug($this->input->post('name')),
                            'description'=>$this->input->post('description'),
                            'sort'=>$this->input->post('sort'),
                            'manufacturer'=>set_value('manufacturer'),
                            'category'=>set_value('category'),
                            'price_uah'=>set_value('price_uah'),
                            'price_usd'=>set_value('price_usd'),
                            'image_big'=>$this->input->post('image_big'),
                            'image_small'=>$this->input->post('image_small'),

                    );

                    $product_id = $this->product_model->update($result);
                    $this->session->set_userdata('message', 'Продукт успешно добавлен');
                    redirect('admin/product');
                }
            }
            else {
                $this->load->view('admin/main', $data);
            }


        }
        else {
            redirect('admin/product');
        }
    }

    function view() {
        if($id = $this->uri->segment(4)) {
            $data['template'] = 'admin/product/display';
            $data['object']=$this->product_model->getById($id);
            $data['res'] = $this->router->fetch_class();
            $this->load->view('admin/main', $data);
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
    function upload() {
        /**
         * upload.php
         *
         * Copyright 2009, Moxiecode Systems AB
         * Released under GPL License.
         *
         * License: http://www.plupload.com/license
         * Contributing: http://www.plupload.com/contributing
         */

// HTTP headers for no cache etc
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

// Settings
//$targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
        $targetDir = 'uploads/products';

//$cleanupTargetDir = false; // Remove old files
//$maxFileAge = 60 * 60; // Temp file age in seconds

// 5 minutes execution time
        @set_time_limit(5 * 60);

// Uncomment this one to fake upload time
// usleep(5000);

// Get parameters
        $chunk = isset($_REQUEST["chunk"]) ? $_REQUEST["chunk"] : 0;
        $chunks = isset($_REQUEST["chunks"]) ? $_REQUEST["chunks"] : 0;
        $fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

// Clean the fileName for security reasons
        $fileName = preg_replace('/[^\w\._]+/', '', $fileName);

// Make sure the fileName is unique but only if chunking is disabled
        if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {
            $ext = strrpos($fileName, '.');
            $fileName_a = substr($fileName, 0, $ext);
            $fileName_b = substr($fileName, $ext);

            $count = 1;
            while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
                $count++;

            $fileName = $fileName_a . '_' . $count . $fileName_b;
        }

// Create target dir
        if (!file_exists($targetDir))
            @mkdir($targetDir);

// Remove old temp files
        /* this doesn't really work by now

if (is_dir($targetDir) && ($dir = opendir($targetDir))) {
	while (($file = readdir($dir)) !== false) {
		$filePath = $targetDir . DIRECTORY_SEPARATOR . $file;

		// Remove temp files if they are older than the max age
		if (preg_match('/\\.tmp$/', $file) && (filemtime($filePath) < time() - $maxFileAge))
			@unlink($filePath);
	}

	closedir($dir);
} else
	die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
        */

// Look for the content type header
        if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
            $contentType = $_SERVER["HTTP_CONTENT_TYPE"];

        if (isset($_SERVER["CONTENT_TYPE"]))
            $contentType = $_SERVER["CONTENT_TYPE"];

// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
        if (strpos($contentType, "multipart") !== false) {
            if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
                // Open temp file
                $out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName, $chunk == 0 ? "wb" : "ab");
                if ($out) {
                    // Read binary input stream and append it to temp file
                    $in = fopen($_FILES['file']['tmp_name'], "rb");

                    if ($in) {
                        while ($buff = fread($in, 4096))
                            fwrite($out, $buff);
                    } else
                        var_dump($in) or  die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
                    fclose($in);
                    fclose($out);
                    @unlink($_FILES['file']['tmp_name']);
                } else
                    die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
            } else
                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
        } else {
            // Open temp file
            $out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName, $chunk == 0 ? "wb" : "ab");
            if ($out) {
                // Read binary input stream and append it to temp file
                $in = fopen("php://input", "rb");

                if ($in) {
                    while ($buff = fread($in, 4096))
                        fwrite($out, $buff);
                } else
                    die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');

                fclose($in);
                fclose($out);
            } else
                die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        }

// Return JSON-RPC response
        die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');

    }

    private function generateSlug($text) {
        $converter = array(
                'а' => 'a',   'б' => 'b',   'в' => 'v',
                'г' => 'g',   'д' => 'd',   'е' => 'e',
                'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
                'и' => 'i',   'й' => 'y',   'к' => 'k',
                'л' => 'l',   'м' => 'm',   'н' => 'n',
                'о' => 'o',   'п' => 'p',   'р' => 'r',
                'с' => 's',   'т' => 't',   'у' => 'u',
                'ф' => 'f',   'х' => 'h',   'ц' => 'c',
                'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
                'ь' => "'",  'ы' => 'y',   'ъ' => "'",
                'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

                'А' => 'A',   'Б' => 'B',   'В' => 'V',
                'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
                'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
                'И' => 'I',   'Й' => 'Y',   'К' => 'K',
                'Л' => 'L',   'М' => 'M',   'Н' => 'N',
                'О' => 'O',   'П' => 'P',   'Р' => 'R',
                'С' => 'S',   'Т' => 'T',   'У' => 'U',
                'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
                'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
                'Ь' => "'",  'Ы' => 'Y',   'Ъ' => "'",
                'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
        );
        $st = strtr($text, $converter);
        $slug = strtolower($st);
        $slug = preg_replace("/[^a-z0-9\s-]/", "", $slug);
        $slug = trim(preg_replace("/[\s-]+/", " ", $slug));
        $slug = trim(substr($slug, 0, 64));
        $slug = preg_replace("/\s/", "-", $slug);
        return $slug;
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

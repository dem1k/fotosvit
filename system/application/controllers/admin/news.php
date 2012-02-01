<?php
class News extends Controller {

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
    function News() {
        parent::Controller();
        $this->load->model('admin/news_model', '', true);
//        $this->load->model('admin/parametrs_model', '', true);
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');


    }

    function index() {
        $data['template'] = 'admin/news/index';
        $data['res'] = $this->router->fetch_class();
        $data['object'] = $this->news_model->getAll();
        $this->load->view('admin/main', $data);
    }
    function create() {
        $data['template'] = 'admin/news/create';
        $data['res'] = $this->router->fetch_class();
        $this->form_validation->set_rules('name', 'Название', 'trim|required|min_length[1]|max_length[230]|xss_clean');
        $data['news'] = $this->news_model->getAll();
        if ($this->input->post('action', '') == 'save') {
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('admin/main', $data);
            }else {
                $slug=$this->generateSlug(set_value('title').' '.date('Y-m-d H:i:s'));
                $result=array(
                        'name'=>set_value('name'),
                        'slug'=>$slug,
                        'description'=>$this->input->post('description'),
                        'date'=>date('Y-m-d H:i:s')
                );
                $this->news_model->save($result);
                redirect('/admin/news/');
            }
        }else {
            $this->load->view('admin/main', $data);
        }
    }

    function edit() {
        $id = $this->uri->segment(4);

        if (!empty($id)) {
            $data['id']=$id;
            $data['template'] = 'admin/news/edit';
            $data['res'] = $this->router->fetch_class();
            $this->form_validation->set_rules('news', 'Название', 'trim|required|min_length[1]|max_length[32]|xss_clean');
            $data['news'] =$this->news_model->getById($id);
//            var_dump($data);exit;
            if ($this->input->post('action') == 'save') {
                if ($this->form_validation->run() == FALSE) {
                    $this->load->view('admin/main', $data);
                }else {
                    if (!in_array($article['slug'], $this->noedit)) {
                        $slug=$this->generateSlug(set_value('title').' '.date('Y-m-d H:i:s'));
                    }else {
                        $slug = $article['slug'];

                    }
                    $result=array(
                            'name'=>set_value('name'),
                            'slug'=>$slug,
                            'content'=>$this->input->post('content'),
                            'date'=>date('Y-m-d H:i:s')
                    );
                    $this->news_model->updateById($result,$id);
                    redirect('/admin/news/');
                }
            }else {
                $this->load->view('admin/main', $data);
            }
        }else {
            redirect('/admin/news/');
        }
    }
    function delete() {
        $id = $this->uri->segment(4);

        if (!empty($id)) {
            $this->news_model->deleteById($id);
        }redirect
        ('/admin/news');
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
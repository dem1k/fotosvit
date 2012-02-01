<?php

class Document extends Controller {
    
    function Document() {
        parent::Controller();
    }
    
    function index() {
        $data = array();                            // Создается новый пустой массив
        $data['template'] = 'admin/document/view';//Подключается шаблон
        $data['res'] = $this->router->fetch_class();//Узнаем какой контроллер задействован и передаем его в шаблон
        $this->load->model('admin/document_model', 'doc', true);//загружаем модель
        $doc = $this->doc->get_all();//получаем список статичных страниц в массиве
        $data['doc'] = $doc;//передаем во вьювер

        if ($message = $this->session->userdata('message')) {
            $data['message'] = $message;
            $this->session->unset_userdata('message');
        }
        $this->load->view('/admin/main', $data);
    }
    
    function create() {
        $data = array();                            // Создается новый пустой массив
        $data['error_upload'] = '';
        $data['template'] = 'admin/document/create';//Подключается шаблон
        $data['res'] = $this->router->fetch_class();//Узнаем какой контроллер задействован и передаем его в шаблон
        $this->load->model('admin/document_model', 'document', true);//загружаем модель
        $this->load->library('form_validation');
        $this->form_validation->set_rules('description', 'Описание', 'trim|required|min_length[2]|xss_clean');
        if ($this->input->post('action', '') == 'save') {
            if ($this->form_validation->run() == FALSE) {
                $data['error'] = ' ';
                $this->load->view('admin/main', $data);//загружаем вьювер
            }
            else {
                $config['upload_path'] = './uploads/document';
                $config['allowed_types'] = 'pdf|zip|jpg|png|doc|avi|swf|rar|txt|gif|bmp|mpeg';
                $config['max_size']	= '100';

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('userfile')) {
                    $data['error_upload'] = $this->upload->display_errors();
                    $this->load->view('admin/main', $data);
                }
                else {
                    $type = $this->upload->file_ext; // Узнаю расширение
                    srand((double) microtime( ) * 1000000); // Делаем случайное имя
                    $uniq_id = uniqid(rand( ));
                    rename('./uploads/document/' . $this->upload->file_name, './uploads/document/' . $uniq_id . $type);
                    $result = array(
                        'name'=>$this->upload->file_name,
                        'description'=>$this->input->post('description'),
                        'path'=>$uniq_id . $type,
                    );
                    $save = $this->document->save($result);
                    $this->session->set_userdata('message', 'Документ успешно добавлен');
                    redirect('/admin/document');
                }
            }
        }
        else {
            $this->load->view('admin/main', $data);//загружаем вьювер
        }
    }
    
    function edit() {
        $id = $this->uri->segment(4, '0');
        if ($id != '0') {
            $data = array();                            // Создается новый пустой массив
            $data['error_upload'] = '';
            $data['template'] = 'admin/document/edit';//Подключается шаблон
            $data['res'] = $this->router->fetch_class();//Узнаем какой контроллер задействован и передаем его в шаблон
            $this->load->model('admin/document_model', 'document', true);//загружаем модель
            $doc = $this->document->get_By_id($id);
            if (!empty($doc)) {
                $data['doc'] = $doc[0];
                $this->load->library('form_validation');
                $this->form_validation->set_rules('description', 'Описание', 'trim|required|min_length[2]|xss_clean');
                if ($this->input->post('action', '') == 'save') {
                    if ($this->form_validation->run() == FALSE) {
                        $data['error'] = ' ';
                        $this->load->view('admin/main', $data);//загружаем вьювер
                    }
                    else {
                        $file = $this->input->post('nametwo');
                        if ($file != $doc[0]->name) {
                            unlink('./uploads/document/' . $doc[0]->path) or die("Could not delete " . $doc[0]->name . " file");
                            $config['upload_path'] = './uploads/document';
                            $config['allowed_types'] = 'pdf|zip|jpg|png|doc|avi|swf|rar|txt|gif|bmp|mpeg';
                            $config['max_size']	= '100';

                            $this->load->library('upload', $config);

                            if (!$this->upload->do_upload('userfile')) {
                                $data['error_upload'] = $this->upload->display_errors();
                                $this->load->view('admin/main', $data);
                            }
                            else {
                                $type = $this->upload->file_ext; // Узнаю расширение
                                srand((double) microtime( ) * 1000000); // Делаем случайное имя
                                $uniq_id = uniqid(rand( ));
                                rename('./uploads/document/' . $this->upload->file_name, './uploads/document/' . $uniq_id . $type);
                                $result = array(
                                    'name'=>$this->upload->file_name,
                                    'description'=>$this->input->post('description'),
                                    'path'=>$uniq_id . $type,
                                    'id'=>$id
                                );
                                $save = $this->document->update($result);
                                $this->session->set_userdata('message', 'Документ успешно отредактирован');
                                redirect('/admin/document');
                            }
                        }
                        else {
                            $result = array(
                                'name'=>$this->input->post('nametwo', ''),
                                'description'=>$this->input->post('description', ''),
                                'path'=>$this->input->post('pathtwo'),
                                'id'=>$id
                            );
//                            var_dump($result);
                            $save = $this->document->update($result);
                            $this->session->set_userdata('message', 'Документ успешно отредактирован');
                            redirect('/admin/document');
                        }
                    }

                }
                else {
                    $this->load->view('admin/main', $data);//загружаем вьювер
                }
            }
            else {
                redirect('admin/document');
            }
        }
        else {
            redirect('admin/document');
        }
    }
    
    function view() {
        $id = $this->uri->segment(4, '0');
        $data = array();
        $data['template'] = 'admin/document/display';//Подключается шаблон
        $data['res'] = $this->router->fetch_class();//Узнаем какой контроллер задействован и передаем его в шаблон
        $this->load->model('admin/document_model', 'document', true);//загружаем модель
        if ($id != '0') {
            $doc = $this->document->get_By_id($id);
            if (!empty($doc)) {
                $data['doc'] = $doc[0];
                $this->load->view('admin/main', $data);
            }
            else {
                redirect('admin/document');
            }
        }
        else {
            redirect('admin/document');
        }
    }
    
    function delete() {
        $id = $this->uri->segment(4, '0');
        $data = array();
        $this->load->model('admin/document_model', 'document', true); //загружаем модель
        if ($id != '0') {
            $doc = $this->document->get_By_id($id);
            if (!empty($doc)) {
                unlink('./uploads/document/' . $doc[0]->path) or die("Could not delete " . $doc[0]->name . " file");
                $this->document->delete($id);
                $this->session->set_userdata('message', 'Документ успешно удален');
                redirect('admin/document');
            }
            else {
                redirect('admin/document');
            }
        }
        else {
            redirect('admin/document');
        }
    }
    
    function _remap($method) {
        $this->load->library('authorization');
        $params = null;
        $user_type = $this->session->userdata('user_type');
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

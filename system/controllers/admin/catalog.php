<?php

class Catalog extends Controller {

    function Catalog() {
        parent::Controller();
        $this->load->model('admin/catalog_model', '', true);
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
    }

    function index() {
        $data['template'] = 'admin/catalog/view';
        $data['res'] = $this->router->fetch_class();
        $data['categories'] = $this->catalog_model->getCatalog();
        $this->load->view('admin/main', $data);
    }

    function showSubsection() {
        $catId = $this->uri->segment(4);
        if(!$catId) {
            show_404();
        }
        else {
            $data['template'] = 'admin/catalog/subsections';
            $data['res'] = $this->router->fetch_class();
            $data['catId']=$catId;
            $catName=$this->catalog_model->getCatById($catId);
            $data['catName']=$catName[0];
            $data['subsections'] = $this->catalog_model->getCatalogSubsections($catId);
            $this->load->view('admin/main', $data);
        }

    }

    function createSubSection() {
        $catId = $this->uri->segment(4);
        if(!$catId) {
            show_404();
        }
        else {
            $data['template'] = 'admin/catalog/createsubsection';
            $data['res'] = $this->router->fetch_class();
            $data['catId']=$catId;
            $catName=$this->catalog_model->getCatById($catId);
            $data['catName']=$catName[0];
            $this->form_validation->set_rules('name', 'Имя подраздела', 'trim|required|xss_clean');
            $data['name']=set_value('name');

            if ($this->input->post('action', '') == 'save') {
                if ($this->form_validation->run() == FALSE) {
                    $this->load->view('admin/main', $data);
                }else {
                    $save=array(
                        'cat_id'=>$catId,
                        'name'=>set_value('name'),

                    );
                    $this->catalog_model->createCatalogSubsection($save);
                    redirect('/admin/catalog/showSubsection/'.$catId);
                }
            }else {
                $this->load->view('admin/main', $data);
            }


        }

    }
function editSubSection() {
        $subId = $this->uri->segment(4);
        if(!$subId) {
            show_404();
        }
        else {
            $data['template'] = 'admin/catalog/editsubsection';
            $data['res'] = $this->router->fetch_class();
            $data['subId']=$subId;
            $subName=$this->catalog_model->getSubById($subId);
            $data['subName']=$subName[0];
            $catId=$subName[0]['cat_id'];
            $catName=$this->catalog_model->getCatById($catId);
            $data['catName']=$catName[0];
            $data['catId']=$catId;
            $this->form_validation->set_rules('name', 'Имя подраздела', 'trim|required|xss_clean');
            $data['name']=set_value('name');

            if ($this->input->post('action', '') == 'save') {
                if ($this->form_validation->run() == FALSE) {
                    $this->load->view('admin/main', $data);
                }else {
                    $save=array(
                        'cat_id'=>$catId,
                        'name'=>set_value('name'),

                    );
                    $this->catalog_model->createCatalogSubsection($save);
                    redirect('/admin/catalog/showSubsection/'.$catId);
                }
            }else {
                $this->load->view('admin/main', $data);
            }


        }

    }
    function showProducts() {
        $subsectionId = $this->uri->segment(4);
        if(!$subsectionId) {
            show_404();
        }
        else {
            $data['template'] = 'admin/catalog/showproducts';
            $data['res'] = $this->router->fetch_class();
            $subName=$this->catalog_model->getSubById($subsectionId);
            $data['subName']=$subName[0];
            
            $catId=$subName[0]['cat_id'];
            $catName=$this->catalog_model->getCatById($catId);
            $data['catName']=$catName[0];
//            var_dump($data);exit;
            $data['id']=$subsectionId;
            $data['products'] = $this->catalog_model->getSubsectionProducts($subsectionId);
            $this->load->view('admin/main', $data);
        }
    }


    function createProduct() {
        $subsectionId = $this->uri->segment(4);
        if(!$subsectionId) {
            show_404();
        }
        else {

            $subName=$this->catalog_model->getSubById($subsectionId);
            $data['subName']=$subName[0];

            $catId=$subName[0]['cat_id'];
            $catName=$this->catalog_model->getCatById($catId);
            $data['catName']=$catName[0];
            
            $data['template'] = 'admin/catalog/create_product';
            $data['res'] = $this->router->fetch_class();

            $this->form_validation->set_rules('name', 'name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('description', 'description', 'trim|required|xss_clean');
            $data['id']=$subsectionId;
            $data['name']=set_value('name');
            $data['description']=set_value('description');

            if ($this->input->post('action', '') == 'save') {
                if ($this->form_validation->run() == FALSE) {
                    $data['name']=$this->input->post('name');
                    $data['description']=$this->input->post('description');
                    $this->load->view('admin/main',$data);
                } else {
                    $config['upload_path'] = './uploads/products';
                    $config['allowed_types'] = 'gif|jpg|png|bmp';
                    $config['max_size']	= '10000';
                    $this->load->library('upload', $config);

                    if (!$this->upload->do_upload('image')) {
                        $data['error_upload'] = $this->upload->display_errors();
                        $this->load->view('admin/main', $data);
                    }
                    else {
                        $type = $this->upload->file_ext;
                        srand((double) microtime( ) * 1000000);
                        $uniq_id = uniqid(rand( ));
                        rename('./uploads/products/' . $this->upload->file_name, './uploads/products/' . $uniq_id . $type);
                        $product = array(
                                'name'=>set_value('name'),
                                'description'=>set_value('description'),
                                'image'=>$uniq_id . $type,
                                'subsection_id'=>$subsectionId
                        );
                        $this->catalog_model->createProduct($product);
                        redirect('/admin/catalog/showProducts/'.$subsectionId.'/');
                    }

                    $this->load->view('admin/main',$data);
                }
            }else {
                $this->load->view('admin/main',$data);
            }
        }
    }

    function editProduct() {
        $productId= $this->uri->segment(4);
        if(!$productId) {
            show_404();
        }
        else {

            

            $data['template'] = 'admin/catalog/edit_product';
            $data['res'] = $this->router->fetch_class();
            $this->form_validation->set_rules('name', 'name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('description', 'description', 'trim|required|xss_clean');
            $data['id'] = $productId;
            $product = $this->catalog_model->getProductById($productId);
            $data['product']=$product[0];
            $subsectionId=$product[0]['subsection_id'];
            $subName=$this->catalog_model->getSubById($subsectionId);
            $data['subName']=$subName[0];

            $catId=$subName[0]['cat_id'];
            $catName=$this->catalog_model->getCatById($catId);
            $data['catName']=$catName[0];
            // var_dump($data['product']);exit;
            if ($this->input->post('action', '') == 'save') {
                if ($this->form_validation->run() == FALSE) {
                    $data['name']=$this->input->post('name');
                    $data['description']=$this->input->post('description');
                    $this->load->view('admin/main',$data);
                } else {
                    $config['upload_path'] = './uploads/products';
                    $config['allowed_types'] = 'gif|jpg|png|bmp';
                    $config['max_size']	= '10000';
                    $this->load->library('upload', $config);
                    $filechange=$_POST;
                    //var_dump($filechange);exit;
                    if (!$this->upload->do_upload('image')) {
                        //$data['error_upload'] = $this->upload->display_errors();
                        //$this->load->view('admin/main', $data);
                        $product = array(
                                'name'=>set_value('name'),
                                'description'=>set_value('description'),
                        );
                        $this->catalog_model->editProduct($product,$productId);
                        redirect('/admin/catalog/showProducts/'.$subsectionId.'/');
                    }
                    else {
                        $type = $this->upload->file_ext;
                        srand((double) microtime( ) * 1000000);
                        $uniq_id = uniqid(rand( ));
                        rename('./uploads/products/' . $this->upload->file_name, './uploads/products/' . $uniq_id . $type);
                        $product = array(
                                'name'=>set_value('name'),
                                'description'=>set_value('description'),
                                'image'=>$uniq_id . $type,
                        );
                        $this->catalog_model->editProduct($product,$productId);
                        redirect('/admin/catalog/showProducts/'.$subsectionId.'/');
                    }

                    $this->load->view('admin/main',$data);
                }
            }else {
                $this->load->view('admin/main',$data);
            }

        }
    }

    function deleteProduct() {
        $productId= $this->uri->segment(4);
        if(!$productId) {
            show_404();
        }
        else {
            $data['template'] = 'admin/catalog/edit_product';
            $data['res'] = $this->router->fetch_class();
            $product = $this->catalog_model->getProductById($productId);
            $subsectionId=$product[0]['subsection_id'];
            $this->catalog_model->deleteProduct($productId);
            redirect('/admin/catalog/showProducts/'.$subsectionId.'/');

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

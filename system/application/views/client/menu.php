<?php
class Menu extends Controller {

    function Menu() {
        parent::Controller();
        
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
    }

    function index() {
        $data['template'] = 'admin/catalog/view';
        $data['res'] = $this->router->fetch_class();
        $data['categories'] = $this->catalog_model->getCatalog();
        $this->load->view('admin/main', $data);
    }
}

?>
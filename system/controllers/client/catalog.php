<?php

class Catalog extends Controller {

    function Catalog() {
        parent::Controller();
    }
    function index() {
        $this->load->model('admin/seo_model','',true);
        $seo=$this->seo_model->getseo();
        $data['seo']=$seo[0];
        $data['template']='client/catalog/view';
        $data['title']='CAtalog Dem1k';
        $this->load->view('/client/main',$data);
    }


}

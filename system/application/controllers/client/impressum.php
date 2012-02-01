<?php

class Impressum extends Controller {

    function Impressum() {
        parent::Controller();
        $this->load->model('admin/catalog_model','',true);
        $this->load->model('admin/seo_model','',true);
        $this->load->model('admin/impressum_model','',true);
    }
    function index() {
        
        $seo=$this->seo_model->getseo();
        $data['seo']=$seo[0];
        $data['template']='content';
        
        $impressum=$this->impressum_model->getImpressum();
        $data['text']=$impressum[0]->text ;
        $data['title']='Elena';
        $data['categories'] = $this->catalog_model->getCatalog();
        $this->load->view('/client/main',$data);
    }

}
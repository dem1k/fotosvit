<?php

class Pages extends Controller {

    function Pages() {
        parent::Controller();
        $this->load->model('admin/seo_model','',true);
        $this->load->model('admin/startpage_model','',true);
        $this->load->model('admin/catalog_model','',true);
    }
    function index() {
        $data['template']='content';

        $startpage=$this->startpage_model->getStartpage();
        $categories = $this->catalog_model->getCatalog();
        $data['categories']=$categories;
        $arr=array();
        foreach ($categories as $catId=>$category){

        }
//        var_dump($arr);exit;
        $seo=$this->seo_model->getseo();
        $data['seo']=$seo[0];
        $data['text']=$startpage[0]->text ;
        $data['title']='Elena';
        $this->load->view('/client/main',$data);
    }


}

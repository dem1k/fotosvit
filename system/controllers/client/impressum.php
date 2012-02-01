<?php

class Impressum extends Controller {

    function Impressum() {
        parent::Controller();
    }
    function index() {
        $this->load->model('admin/seo_model','',true);
        $seo=$this->seo_model->getseo();
        $data['seo']=$seo[0];
        $data['template']='content';
        $this->load->model('admin/impressum_model','',true);
        $impressum=$this->impressum_model->getImpressum();
        $data['text']=$impressum[0]->text ;
        $data['title']='Elena';
        $this->load->view('/client/main',$data);
    }

}
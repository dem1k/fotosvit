<?php
class News extends Controller {
    function News() {
        parent::Controller();
        $this->load->model('admin/news_model','',true);
        $this->load->model('admin/category_model','',true);
        $this->load->model('admin/seo_model','',true);
        $this->load->library('mybasket');
        $this->mybasket->init();
    }
    function index() {
        $basket=$this->mybasket->getBasket();
        $total_prc=$total_qty=0;
        foreach ($basket as $val) {
            $total_qty+=(int) $val['qty'];
            $total_prc+=(float) $val['total_price_uah'];
        }
        $data['basket']=array(
                'basket_total'=>$total_qty,
                'basket_total_price'=>$total_prc
        );
        $data['seo']=$this->seo_model->getSeo();
        $data['popular']=false;
        $data['topMenu']=true;
        $data['sidebar']=$this->category_model->getAll();
        $data['template']='/client/news/show';
        $data['news']=$this->news_model->getAll();
        $this->load->view('/client/main',$data);

    }

}
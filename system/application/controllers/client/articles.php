<?php
class Articles extends Controller {
    function Articles() {
        parent::Controller();
        $this->load->model('admin/articles_model','',true);
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
        $data['popular']=false;
        $data['topMenu']=true;
        $data['seo']=$this->seo_model->getSeo();
        $data['sidebar']=$this->category_model->getAll();
        $data['template']='/client/articles/show';
        $content=$this->articles_model->getBySlug($this->uri->segment(1));
        $data['content']=$content?$content:false;
        $this->load->view('/client/main',$data);

    }

}
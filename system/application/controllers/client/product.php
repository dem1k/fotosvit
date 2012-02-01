<?php

class Product extends Controller {

    function Product() {
        parent::Controller();
        $this->load->model('admin/product_model','',true);
        $this->load->model('admin/seo_model','',true);
        $this->load->model('admin/articles_model','',true);
        $this->load->model('admin/category_model','',true);
        $this->load->library('mybasket');
        $this->mybasket->init();
    }
    function details() {
        if($this->uri->segment(3)) {
            $basket=$this->mybasket->getBasket();
            $total_prc=$total_qty=0;
            foreach ($basket as $val) {
                $total_qty+=(int) $val['qty'];
                $total_prc+=(float) $val['total_price_uah'];
            }
            $data['basket']=array('basket_total'=>$total_qty,
                    'basket_total_price'=>$total_prc);
            $data['seo']=$this->seo_model->getSeo();
            $data['popular']=false;
            $data['topMenu']=true;
            $data['sidebar']=$this->category_model->getAll();
            $data['content']=$this->articles_model->getBySlug('startpage');
            $data['product']=$this->product_model->getBySlug($this->uri->segment(3));
//            echo 'hello';exit;
            $data['template']='client/product/details';
            $this->load->view('/client/main',$data);
        }else {
            redirect('/');
        }
    }

//    function _remap($method) {
//        $params = null;
//        if ($this->uri->segment(5)) {
//            $params = $this->uri->segment(5);
//        }
//        check_parther();
//        $this->$method($params);
//    }

}

?>

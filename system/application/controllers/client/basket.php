<?php
class Basket extends Controller {
    function Basket() {
        parent::Controller();
        $this->load->library('mybasket');
        $this->mybasket->init();
        $this->load->model('admin/seo_model','',true);
        $this->load->model('admin/product_model','',true);
        $this->load->model('admin/category_model','',true);
    }
    function index () {
        $data['basket_prod']=$basket=$this->mybasket->getBasket();
        $total_prc=$total_qty=0;
        foreach ($basket as $val) {
            $total_qty+=(int) $val['qty'];
            $total_prc+=(float) $val['total_price_uah'];
        }
        $data['basket']=array(
                'basket_total'=>$total_qty,
                'basket_total_price'=>$total_prc
        );

        $data['template']='client/basket/index';
        $data['popular']=false;
        $data['topMenu']=true;
        $data['sidebar']=$this->category_model->getAll();
        $seo=$this->seo_model->getSeo();
        $data['seo']=$seo;
        $this->load->view('/client/main',$data);
    }
    function order() {
        $this->load->model('admin/shiping_model','',true);
        $this->load->model('admin/payment_model','',true);
        $data['basket_prod']=$basket=$this->mybasket->getBasket();
        $total_prc=$total_qty=0;
        foreach ($basket as $val) {
            $total_qty+=(int) $val['qty'];
            $total_prc+=(float) $val['total_price_uah'];
        }
        $data['basket']=array(
                'basket_total'=>$total_qty,
                'basket_total_price'=>$total_prc
        );
        $data['template']='client/basket/order';
        $data['popular']=false;
        $data['topMenu']=true;
        $data['sidebar']=$this->category_model->getAll();
        $data['shipings']=$this->shiping_model->getAll();
        $data['payments']=$this->payment_model->getAll();
        $data['seo']=$this->seo_model->getSeo();
        $this->load->view('/client/main',$data);
    }

    function putProduct() {
        $to_basket=$this->input->post('to_basket');
        if($to_basket=='ok') {
            $arr=$this->session->userdata('basket_product');
            $rewrite=(int)$this->input->post('rewrite');
            if(isset($arr[$this->input->post('prod_id')]) && !$rewrite) {
                $arr[$this->input->post('prod_id')]+=(int)$this->input->post('prod_qty');
            }else {
                $arr[$this->input->post('prod_id')]=(int)$this->input->post('prod_qty');
            }
            $this->session->set_userdata(array('basket_product'=>$arr));
            $basket=$this->mybasket->getBasket();
            $total_prc=$total_qty=0;
            foreach ($basket as $val) {
                $total_qty+=(int) $val['qty'];
                $total_prc+=(float) $val['price_uah'];
            }
            $basket=array('basket_total'=>$total_qty,
                    'basket_total_price'=>(float)$total_prc*(int)$total_qty);


            echo json_encode($basket);
        }else {
            show_404();
        }
    }
    function removeProduct() {
        $to_basket=$this->input->post('to_basket');
        if($to_basket=='ok') {
            $arr=$this->session->userdata('basket_product');

            if(isset($arr[$this->input->post('prod_id')])) {
                unset($arr[$this->input->post('prod_id')]);
            }
            $this->session->set_userdata(array('basket_product'=>$arr));
            $basket=$this->mybasket->getBasket();
            $total_prc=$total_qty=0;
            foreach ($basket as $val) {
                $total_qty+=(int) $val['qty'];
                $total_prc+=(float) $val['price_uah'];
            }
            $basket=array('basket_total'=>$total_qty,
                    'basket_total_price'=>(float)$total_prc*(int)$total_qty);


            echo json_encode($basket);
        }else {
            show_404();
        }
    }


}
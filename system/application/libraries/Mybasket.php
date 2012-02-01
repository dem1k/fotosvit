<?php if (!defined('BASEPATH')) exit('Нет доступа к скрипту');

class Mybasket {
    function __construct () {
        $this->CI =& get_instance();
        $this->CI->load->model('admin/product_model','',true);
    }
    function init() {
//        $this->CI->session->sess_destroy();
        if(!$this->CI->session->userdata('session_id')) {
            $array['session_id']    = md5(date('Y-m-d h:i:s').'eomemvoei');
            $array['session_live'] = time();
            $array['basket_product'] = array();
            $this->CI->session->set_userdata($array);
        }
        $session_live= $this->CI->session->userdata('session_live');
        $bs= $this->CI->session->userdata('basket_product');
        if((time()-(int)$session_live)>3600) {
            $this->CI->session->sess_destroy();
        }else {
            $array['session_live'] = time();
            $this->CI->session->set_userdata($array);
        }
    }
    function getBasket() {
        if( $basket_product= $this->CI->session->userdata('basket_product')) {
            foreach ($basket_product as $id => $qty) {
                if($product=$this->CI->product_model->getById($id)) {
                    $prodArr[]=array(
                            'id'=>$product->id,
                            'name'=>$product->name,
                            'image_small'=>$product->image_small,
                            'image_big'=>$product->image_big,
                            'slug'=>$product->slug,
                            'qty'=>(int)$qty,
                            'price_uah'=>(float)$product->price_uah,
                            'price_usd'=>(float)$product->price_usd,
                            'total_price_uah'=>(int)$qty*(float)$product->price_uah,
                            'total_price_usd'=>(int)$qty*(float)$product->price_usd
                    );
                }else {
                    continue;
                }
            }
            ;
            return isset($prodArr)?$prodArr:array();
        } return
        array();



        ;
    }
}
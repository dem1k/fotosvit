<?php

class Category extends Controller {

    function Category() {
        parent::Controller();
        $this->load->model('admin/category_model','',true);
        $this->load->model('admin/product_model','',true);
        $this->load->model('admin/seo_model','',true);
        $this->load->library('mybasket');
        $this->mybasket->init();

    }
    function show() {
        if(!$slug=$this->uri->segment(2)) {
            redirect('/');
        }else {

            $perpage=$this->input->post('per_page')?$this->input->post('per_page'):25;
            $page=$this->input->post('page')?$this->input->post('page'):1;
            $basket=$this->mybasket->getBasket();
            $total_prc=$total_qty=0;
            foreach ($basket as $val) {
                $total_qty+=(int) $val['qty'];
                $total_prc+=(float) $val['total_price_uah'];
            }
            $data['basket']=array('basket_total'=>$total_qty,
                    'basket_total_price'=>$total_prc);
            $data['seo']=$this->seo_model->getSeo();
            $data['topMenu']=true;
            $data['popular']=false;
            $data['big_ruler']=true;
            $data['sidebar']=$this->category_model->getAll();
            $data['category']=$this->category_model->getBySlug($slug);
            $data['products']=$this->product_model->getProductByCategorySlug($slug,$perpage,$page);
            $this->load->library('pagination');

            $config['base_url'] = '/category/'.$slug.'/page/';
            $config['total_rows'] = 10;
            $config['per_page'] = $perpage;
            $config['prev_link'] = 'Назад';
            $config['next_link'] = 'Вперед';
            $config['last_link'] = false;
            $config['first_link'] = false;
            $config['cur_tag_open'] = '<b style="color:00cccc">';
            $config['cur_tag_close'] = '</b>';

            $this->pagination->initialize($config);
            $data['pagination']=$this->pagination->create_links();
            $data['template']='client/category/view';
            $data['title']='Cвiт фото ';
            $this->load->view('/client/main',$data);
        }
    }


}

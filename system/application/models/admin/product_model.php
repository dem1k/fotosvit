<?php

class Product_model extends Model {

    function getAll() {
      return  $this->db->select()
                ->from('products')
                ->order_by('date', 'asc')
                ->get()->result();
    }
    function getById($id) {
        return  $this->db->select()
                ->from('products')
                ->where('id', $id)
                ->get()
                ->row();
    }
    function getBySlug($slug) {
        return  $this->db->select()
                ->from('products')
                ->where('slug', $slug)
                ->get()
                ->row();
    }
    function getByCategoryId($catId) {
        return  $this->db->select()
                ->from('products')
                ->where('cat_id', $catId)
                ->get()
                ->result_array();
    }
    function getMostPopular($limit) {
       return $this->db->select()
                ->from('products')
                ->order_by('popular', 'asc')
                ->limit($limit)
                ->get()
                ->result();
        
    }
    function getProductByCategorySlug($slug,$perpage) {
        return $this->db
                ->select('
                    products.name,
                    products.id,
                    products.category,
                    products.price_uah,
                    products.price_usd,
                    products.description,
                    products.image_small,
                    products.slug,
                    category.name as cat_name,
                    `category`.`id` as category_id
')
                ->from('products')
                ->join('category','products.category = category.id' )
                ->where('category.slug',$slug)
                ->limit($perpage)
                ->get()
                ->result_array()
        ;
    }
    function save($data) {

        $query = $this->db->insert('products', $data);
        $id = $this->db->insert_id();
        return $id;
    }

     function update($data) {
       return $query = $this->db->update('products', $data, array('id' => $data['id']));
    }

    function get_last_id() {
        $this->db->select('id')->from('products')->order_by('id', 'DESC')->limit(1);
        $query = $this->db->get();
        return $query->result();
    }

    function get_first_id() {
        $this->db->select('id')->from('products')->order_by('id', 'asc')->limit(1);
        $query = $this->db->get();
        return $query->result();
    }

    function insert_file($result) {
        $data = array(
                'product_id' => $result['product_id'],
                'path' => $result['path'],
                'name' => $result['name']
        );

        $query = $this->db->insert('img', $data);
        return $query;
    }



    function get_img($id) {
        $this->db->select('*')
                ->from('img')
                ->where('product_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    function delete_img($id) {
        $this->db->where('product_id', $id);
        $query = $this->db->delete('img');
        return $query;
    }

   

    function delete_product($id) {
        $this->db->where('id', $id);
        $query = $this->db->delete('product');
        return $query;
    }

    function get_all_img() {
        $this->db->select('product_id, path')
                ->from('img');
        $query = $this->db->get();
        return $query->result();
    }

    function get_three() {
        $this->db->select('*')
                ->from('product')
                ->order_by('sort', 'asc');
        $q = $this->db->get();
        return $q->result();
    }

    function DeletePrice($id) {
        $this->db->delete('product_price', array('product_id' => $id));
    }

    function GetPriceId($id, $exchange_id) {
        $this->db->select('*')
                ->from('product_price')
                ->where('product_id', $id)
                ->where('currency_id', $exchange_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    function getPriceById($id) {
        $this->db->select('*')
                ->from('product_price')
                ->where('product_id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    function GetPriceByIdAndCurrency($currency_id, $product_id) {
        $this->db->select('*')
                ->from('product_price')
                ->where('product_id', $product_id)
                ->where('currency_Id', $currency_id)
                ->limit(1);
        $query = $this->db->get();
        return $query->result_array();
    }

    function GetPrice() {
        $this->db->select('*')
                ->from('product_price');
        $query = $this->db->get();
        return $query->result_array();
    }

}

?>

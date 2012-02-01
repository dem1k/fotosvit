<?php

class Currency_model extends Model {
    
    function get_all() {
        $this->db->select('*')
                ->from('currency');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    function save($result) {
        $query = $this->db->insert('currency', $result);
        $id = $this->db->insert_id();
        return $id;
    }
    
    function update($result) {
        $query = $this->db->update('currency', $result, array('id' => $result['id']));
        return $query;
    }
    
    function getById($id) {
        $this->db->select('*')
                ->from('currency')
                ->where('id', $id);

        $query = $this->db->get();
        return $query->result_array();
    }
    
    function delete($id) {
        $this->db->delete('currency', array('id' => $id));
        $this->db->delete('product_price', array('currency_id' => $id));
    }
    
    function SavePrice($result) {
        $query = $this->db->insert('product_price', $result);
    }
    
    function getFirst() {
        $this->db->select('*')
                ->from('currency')
                ->order_by('id', 'asc')
                ->limit(1);
        $query = $this->db->get();
        return $query->result_array();
    }

    function getByName($exchange_name) {
        $this->db->select('*')
                ->from('currency')
                ->order_by('id', 'asc')
                ->where('name', $exchange_name);
        $query = $this->db->get();
        return $query->result_array();
    }
}

?>

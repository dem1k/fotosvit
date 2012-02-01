<?php

class Orders extends Model {
    
    function save_contact($result) {
        $data = array(
            'name'=>$result['name'],
            'phone'=>$result['phone'],
            'description'=>$result['description'],
            'country'=>$result['country'],
            'city'=>$result['city'],
            'street'=>$result['street'],
            'home'=>$result['home'],
            'flat'=>$result['flat'],
            'email'=>$result['email'],
            'ref'=>$result['ref'],
            'status'=>$result['status'],
            'create_date'=>$result['create_date'],
        );
        $query = $this->db->insert('order', $data);
        return $query;
    }
    
    function update_contact($result) {
        $data = array(
            'name'=>$result['name'],
            'phone'=>$result['phone'],
            'description'=>$result['description'],
            'country'=>$result['country'],
            'city'=>$result['city'],
            'street'=>$result['street'],
            'home'=>$result['home'],
            'flat'=>$result['flat'],
            'email'=>$result['email'],
            'ref'=>$result['ref'],
            'status'=>$result['status'],
        );
        $this->db->where('id', $result['id']);
        $query = $this->db->update('order', $data);
        return $query;
    }
    
    function get_last_id() {
        $this->db->select('id')
                ->from('order')
                ->limit(1)
                ->order_by('id', 'desc');
        $result = $this->db->get();
        return $result->result();
    }
    
    function save_item($result) {
        $data = array(
            'order_id'=>$result['order_id'],
            'product_id'=>$result['product_id'],
            'price'=>$result['price'],
            'count'=>$result['count'],
            'exchange'=>$result['exchange']
        );
        $query = $this->db->insert('order_item', $data);
    }
    
    function get_all() {
        $this->db->select('*')
                ->from('order')
                ->order_by('id', 'desc');

        $query = $this->db->get();
        return $query->result();
    }
    
    function delete_orders($id) {
        $query = $this->db->delete('order', array('id' => $id));
        return $query;
    }
    
    function delete_item($id) {
        $query = $this->db->delete('order_item', array('order_id' => $id));
        return $query;
    }
    
    function get_one($id) {
        $this->db->select('*')
                ->from('order')
                ->where('id', $id);
        $query = $this->db->get();
        return  $query->result();
    }
    
    function get_item($id) {
        $this->db->select('*')
                ->from('order_item')
                ->where('order_id', $id);
        $query = $this->db->get();
        return  $query->result();
    }
    
    function status_update($id, $status) {
        $data = array(
            'status' => $status,
        );

        $this->db->where('id', $id);
        $query = $this->db->update('order', $data);
        return $query;
    }
    
    function region_update($region_id, $id) {
        $data = array(
            'region_id' => $region_id,
        );

        $this->db->where('id', $id);
        $query = $this->db->update('order', $data);
        return $query;
    }
}
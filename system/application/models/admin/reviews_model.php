<?php

class Reviews_model extends Model {
    
    function get_all() {
        $this->db->select('*, DATE_FORMAT(date_create, "%d/%m/%Y") AS date_create', false)
                ->from('reviews');
        $query = $this->db->get();
        return $query->result();
    }
    
    function save($result) {
        $data = array(
            'name'=>$result['name'],
            'description'=>$result['description'],
            'mail'=>$result['mail'],
            'status'=>$result['status'],
            'date_create'=>$result['date_create'],
        );
        $query = $this->db->insert('reviews', $data);
        return $query;
    }
    
    function get_By_id($id) {
        $this->db->select('*')
                ->from('reviews')
                ->where('id', $id);
        $query = $this->db->get();
        return $query->result();
    }
    
    function update($result) {
        $data = array(
            'name'=>$result['name'],
            'description'=>$result['description'],
            'mail'=>$result['mail'],
            'status'=>$result['status'],
        );
        $this->db->where('id', $result['id']);
        $query = $this->db->update('reviews', $data);
        return $query;
    }
    
    function delete($id) {
        $q = $this->db->delete('reviews', array('id' => $id));
        return $q;
    }
    
    function get_all_moderate() {
        $this->db->select('*, DATE_FORMAT(date_create, "%d/%m/%Y") AS date_create', false)
                ->from('reviews')
                ->where('status', '1')
                ->order_by('date_create', 'desc');
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_all_moderate_text() {
        $this->db->select('*, DATE_FORMAT(date_create, "%d/%m/%Y") AS date_create', false)
                ->from('reviews')
                ->where('status', '1')
                ->order_by('date_create', 'desc');
        $query = $this->db->get();
        return $query->result();
    }
}

?>

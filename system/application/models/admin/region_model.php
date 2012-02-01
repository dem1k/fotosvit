<?php

class Region_model extends Model {
    
    function get_all() {
        $this->db->select('*')
                ->from('region');
        $query = $this->db->get();
        return $query->result();
    }
    
    function save($result) {
        $query = $this->db->insert('region', $result);
        $id = $this->db->insert_id();
        return $id;
    }
    function update($result) {
        $query = $this->db->update('region', $result, array('id' => $result['id']));
        return $query;
    }
    function getById($id){
        $this->db->select('*')
                ->from('region')
                ->where('id', $id);

        $query = $this->db->get();
        return $query->result_array();
    }

    function delete($id){
        $query = $this->db->delete('region', array('id' => $id));
        return $query;
    }
}

?>

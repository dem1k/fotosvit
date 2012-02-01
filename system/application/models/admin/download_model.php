<?php

/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/
class Download_model extends Model {
    
    function get_all() {
        $this->db->select('*')
                ->from('download');
        $q = $this->db->get();
        return $q->result();
    }
    
    function save($result) {
        $data = array(
            'name'=>$result['name'],
            'description'=>$result['description'],
            'path'=>$result['path'],
        );
        $query = $this->db->insert('download', $data);
        return $query;
    }
    
    function update($result) {
        $data = array(
            'path'=>$result['path'],
            'name'=>$result['name'],
            'description'=>$result['description']
        );
        $this->db->where('id', $result['id']);
        $query = $this->db->update('download', $data);
        return $query;
    }
    
    function get_By_id($id) {
        $this->db->select('*')
                ->from('download')
                ->where('id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    function delete($id) {
        $q = $this->db->delete('download', array('id' => $id));
        return $q;
    }
}

?>

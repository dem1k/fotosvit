<?php

class Document_model extends Model {
    
    function get_all() {
        $this->db->select('*')
                ->from('document');
        $query = $this->db->get();
        return $query->result();
    }

    function save($result) {
        $data = array(
            'name'=>$result['name'],
            'description'=>$result['description'],
            'path'=>$result['path'],
        );
        $query = $this->db->insert('document', $data);
        return $query;
    }
    function get_By_id($id){
        $this->db->select('*')
                ->from('document')
                ->where('id', $id);
        $query = $this->db->get();
        return $query->result();
    }
    function update($result){
        $data = array(
            'path'=>$result['path'],
            'name'=>$result['name'],
            'description'=>$result['description']
        );
        $this->db->where('id',$result['id']);
        $query = $this->db->update('document', $data);
        return $query;
    }
    function delete($id){
        $q = $this->db->delete('document', array('id' => $id));
        return $q;

    }
}

?>

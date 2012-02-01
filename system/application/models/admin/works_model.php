<?php

class Works_model extends Model {
    
    function get_all() {
        $this->db->select('*')
                ->from('works_video')
                ->order_by('sort', 'asc');
        $query = $this->db->get();
        return $query->result();
    }
    
    function save($result) {
        $data = array(
            'path'=>$result['path'],
            'text'=>$result['description'],
            'sort'=>$result['sort'],
        );
        $query = $this->db->insert('works_video', $data);
        return $query;
    }
    
    function get_By_id($id) {
        $this->db->select('*')
                ->from('works_video')
                ->where('id', $id);
        $query = $this->db->get();
        return $query->result();
    }
    
    function update($result) {
        $data = array(
            'path'=>$result['path'],
            'text'=>$result['description'],
            'sort'=>$result['sort'],
        );
        $this->db->where('id', $result['id']);
        $query = $this->db->update('works_video', $data);
        return $query;
    }

    function delete($id) {
        $q = $this->db->delete('works_video', array('id' => $id));
        return $q;
    }
}

?>

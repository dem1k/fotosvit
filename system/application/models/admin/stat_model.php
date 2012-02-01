<?php

class Stat_model extends Model {
    
    function get_all() {
        $query = $this->db->get('static');
        return $query->result();
    }
    
    function get_one($alias) {
        $this->db->select('*')
                ->from('static')
                ->where('alias', $alias);
        $query = $this->db->get();
        return $query->result();
    }

    function getByID($id) {
        $this->db->select('*')
                ->from('static')
                ->where('id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    function delete($alias) {
        $query = $this->db->delete('static', array('alias' => $alias));
        return $query;
    }
    
    function save($result) {
        $data = array(
            'title' => $result['title'],
            'text' => $result['text'],
            'description' => $result['description'],
            'alias' => $result['alias']
        );

        $query = $this->db->insert('static', $data);
        return $query;
    }
    
    function update($result) {
        $data = array(
            'title' => $result['title'],
            'text' => $result['text'],
            'description' => $result['description'],
            'alias' => $result['alias'],
        );
//        $this->db->set($data);
        $this->db->where('alias', $result['old_alias']);
        $query = $this->db->update('static', $data);
        return $query;
    }
}

?>

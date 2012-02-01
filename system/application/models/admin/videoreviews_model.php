<?php

class Videoreviews_model extends Model {

    function updateSort($id, $sort) {
        $data = array(
            'sort'=>$sort,
        );
        $this->db->where('id', $id);
        $query = $this->db->update('video_reviews', $data);
    }
    
    function get_all() {
        $this->db->select('*')
                ->from('video_reviews')
                ->order_by('sort', ' ASC');
        $query = $this->db->get();
        return $query->result();
    }
    
    function save($result) {
        $data = array(
            'path'=>$result['path'],
            'description'=>$result['description'],
        );
        $query = $this->db->insert('video_reviews', $data);
        return $query;
    }
    
    function get_By_id($id) {
        $this->db->select('*')
                ->from('video_reviews')
                ->where('id', $id);
        $query = $this->db->get();
        return $query->result();
    }
    
    function update($result) {
        $data = array(
            'path'=>$result['path'],
            'description'=>$result['description'],
        );
        $this->db->where('id', $result['id']);
        $query = $this->db->update('video_reviews', $data);
        return $query;
    }
    
    function delete($id) {
        $q = $this->db->delete('video_reviews', array('id' => $id));
        return $q;
    }
}

?>

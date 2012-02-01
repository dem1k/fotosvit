<?php

class Articles_model extends Model {

    function getAll() {
        return   $this->db->select()
                ->from('articles')
                ->order_by('date', 'desc')
                ->get()->result_array();
    }

    function getBySlug($slug) {
        return $this->db->select()
                ->from('articles')
                ->where('slug',$slug)
                ->get()
                ->row();
    }
    function getById($id) {
        return $this->db->select()
                ->from('articles')
                ->where('id',$id)
                ->get()
                ->row();
    }
    function updateById($data,$id) {
        $this->db
                ->where('id', $id)
                ->update('articles', $data);

    }
    function save($data) {

        $query = $this->db->insert('articles', $data);
        $id = $this->db->insert_id();
        return $id;
    }
    function deleteById($id) {
        return  $this->db->where('id', $id)->delete('articles');
    }
}
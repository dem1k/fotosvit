<?php

class News_model extends Model {

    function getAll() {
        return   $this->db->select()
                ->from('news')
                ->order_by('date', 'desc')
                ->get()->result_array();
    }

    function getBySlug($slug) {
        return $this->db->select()
                ->from('news')
                ->where('slug',$slug)
                ->get()
                ->row();
    }
    function getById($id) {
        return $this->db->select()
                ->from('news')
                ->where('id',$id)
                ->get()
                ->row();
    }
    function updateById($data,$id) {
        $this->db
                ->where('id', $id)
                ->update('news', $data);

    }
    function save($data) {

        $query = $this->db->insert('news', $data);
        $id = $this->db->insert_id();
        return $id;
    }
    function deleteById($id) {
        return  $this->db->where('id', $id)->delete('news');
    }
}
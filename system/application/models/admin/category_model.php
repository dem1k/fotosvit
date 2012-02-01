<?php

class Category_model extends Model {

    function getAll() {
        return  $this->db->select()
                ->from('category')
                ->order_by('slug', 'asc')
                ->get()
                ->result_array()
        ;
    }

    function getBySlug($slug) {
        return $this->db
                ->select()
                ->from('category')
                ->where('slug',$slug)
                ->get()
                ->row()
        ;
    }
    
    function updateById($data,$id) {
        $this->db->where('id', $id);
        $this->db->update('category', $data);

    }
    function getById($id) {
        return $this->db
                ->select()
                ->from('category')
                ->where('id',$id)
                ->get()
                ->row()
        ;
    }
    function deleteById($id) {
        return  $this->db->where('id', $id)->delete('category');
    }

    function save($data) {

         $this->db
                ->insert('category', $data);
//                ->insert_id();
    }
}
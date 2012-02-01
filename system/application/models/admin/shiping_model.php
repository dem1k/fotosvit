<?php

class Shiping_model extends Model {

    function getAll() {
        return  $this->db->select()
                ->from('shiping')
                ->order_by('slug', 'asc')
                ->get()
                ->result_array()
        ;
    }

    function getBySlug($slug) {
        return $this->db
                ->select()
                ->from('shiping')
                ->where('slug',$slug)
                ->get()
                ->row()
        ;
    }
    
    function updateById($data,$id) {
        $this->db->where('id', $id);
        $this->db->update('shiping', $data);

    }
    function getById($id) {
        return $this->db
                ->select()
                ->from('shiping')
                ->where('id',$id)
                ->get()
                ->row()
        ;
    }
    function deleteById($id) {
        return  $this->db->where('id', $id)->delete('shiping');
    }

    function save($data) {

         $this->db
                ->insert('shiping', $data);
//                ->insert_id();
    }
}
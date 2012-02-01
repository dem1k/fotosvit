<?php

class Payment_model extends Model {

    function getAll() {
        return  $this->db->select()
                ->from('payment')
                ->order_by('slug', 'asc')
                ->get()
                ->result_array()
        ;
    }

    function getBySlug($slug) {
        return $this->db
                ->select()
                ->from('payment')
                ->where('slug',$slug)
                ->get()
                ->row()
        ;
    }
    
    function updateById($data,$id) {
        $this->db->where('id', $id);
        $this->db->update('payment', $data);

    }
    function getById($id) {
        return $this->db
                ->select()
                ->from('payment')
                ->where('id',$id)
                ->get()
                ->row()
        ;
    }
    function deleteById($id) {
        return  $this->db->where('id', $id)->delete('payment');
    }

    function save($data) {

         $this->db
                ->insert('payment', $data);
//                ->insert_id();
    }
}
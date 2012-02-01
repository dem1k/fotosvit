<?php

class Manufacturers_model extends Model {

    function getAll() {
        return  $this->db->select()
                ->from('manufacturers')
                ->order_by('slug', 'asc')
                ->get()
                ->result_array()
        ;
    }

    function getBySlug($slug) {
        return $this->db
                ->select()
                ->from('manufacturers')
                ->where('slug',$slug)
                ->get()
                ->result()
        ;
    }
    function updateById($data,$id) {
        $this->db->where('id', $id);
        $this->db->update('manufacturers', $data);

    }
    function getById($id) {
        return $this->db
                ->select()
                ->from('manufacturers')
                ->where('id',$id)
                ->get()
                ->result()
        ;
    }
    function deleteById($id) {
        return  $this->db->where('id', $id)->delete('manufacturers');
    }

    function save($data) {

        $this->db->insert('category', $data);
    }
}
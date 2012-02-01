<?php
/**
 * Description of abstract
 *
 * @author tobur
 */
class changelink_model extends Model {

    function getAllTables() {
        $sql = 'SHOW TABLES';
        $result = $this->db->query($sql);
        $a = $result->result();
        return $a;
    }

    function getAllEntriesFromTable($table){
        $this->db->select()->from($table);
        $q = $this->db->get();
        return $q->result();

    }

    function update($table, $data){
        if(isset($data['id'])){
            $this->db->where('id', $data['id']);
            unset ($data['id']);
        }
        if(isset($data['slug'])){
            $this->db->where('slug',$data['slug']);
            unset($data['slug']);
        }
        if(isset($data['title'])){
            $this->db->where('title',$data['title']);
            unset($data['title']);
        }
        $this->db->update($table, $data);
    }
}
?>

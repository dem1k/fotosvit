<?php
class Messages_model extends Model {

    function getMessages() {
        $this->db->select('')
                ->from('messages')
                ->order_by('id','DESC');
        $q = $this->db->get();
        return $q->result();
    }


}


?>
